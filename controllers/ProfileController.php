<?php

namespace app\controllers;

use app\core\Application;
use app\core\CloudinaryUtil;
use app\core\Collection;
use app\core\Request;
use app\core\Response;
use app\mappers\CommentMapper;
use app\mappers\dtoMappers\PostDtoMapper;
use app\mappers\FavoriteMapper;
use app\mappers\GenreMapper;
use app\mappers\PostMapper;
use app\mappers\UserMapper;
use app\models\dto\PostDto;
use Exception;

class ProfileController
{
    private static string $DEFAULT_PHOTO = "../web/img/profilePhoto.jpg";

    public function getProfileView(Request $request): void
    {
        if (isset($_COOKIE['userId'])) {
            $userMapper = new UserMapper();

            $userId = $_COOKIE['userId'];
            $user = $userMapper->select($userId);

            Application::$app->getRouter()->renderTemplate("profile.html", ["user" => $user]);

        } else {
            Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
        }
    }

    public function getProfileEditView(Request $request): void
    {
        try {
            if (isset($_COOKIE['userId'])) {
                $userMapper = new UserMapper();

                $userId = $_COOKIE['userId'];
                $user = $userMapper->select($userId);

                Application::$app->getRouter()->renderTemplate("edit.html", ["action" => "edit", "user" => $user]);

            } else {
                Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
            }
        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }

    public function handleProfileEditView(Request $request): void
    {
        try {
            if (isset($_COOKIE['userId'])) {
                $userMapper = new UserMapper();

                $userId = (int)$_COOKIE['userId'];
                $user = $userMapper->select($userId);

                $body = $request->getBody();
                $name = $body['username'];
                $desc = $body['desc'];

                $photoUrl = self::$DEFAULT_PHOTO;
                if ((int) ($_FILES['photo']['size']) != 0) {
                    $photoUrl = CloudinaryUtil::upload($_FILES['photo']['tmp_name']);
                }

                $user->setUsername($name);
                $user->setDescription($desc);
                $user->setPhoto($photoUrl);

                $userMapper->Update($user);

                Application::$app->getRouter()->renderTemplate("profile.html", ["user" => $user]);
            } else {
                Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
            }
        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }

    // posts
    public function getProfilePostsView(Request $request): void
    {
        try {
            $userId = (int)($_COOKIE['userId']);
            if (isset($userId)) {

                $postMapper = new PostMapper();
                $posts = $postMapper->SelectByUserId($userId);


                $userMapper = new UserMapper();
                $user = $userMapper->select($userId);

                $genreMapper = new GenreMapper();
                $favoriteMapper = new FavoriteMapper();

                $postDtos = array();
                foreach ($posts->getNextRow() as $post) {
                    $postId = (int)$post->getId();

                    $genre = $genreMapper->Select($post->getGenreId());
                    $userInfo = $userMapper->Select($post->getAuthorId());

                    $isLiked = 'false';
                    if ($favoriteMapper->findByIds($userId, $postId)) $isLiked = 'true';

                    $postDtos[] = (new PostDto($post->getId(), $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked))->getProperties();
                }
                $posts = new Collection($postDtos, new PostDtoMapper());

                $genreMapper = new GenreMapper();
                $genres = $genreMapper->SelectAll();

                Application::$app->getRouter()->renderTemplate("my_posts.html", ["posts" => $posts, "user" => $user, 'genres'=>$genres]);

            } else {
                Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
            }

        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }

    //likes
    public function getProfileLikesView(Request $request): void
    {
        try {
            $userId = (int)($_COOKIE['userId']);
            if (isset($userId)) {
                $postMapper = new PostMapper();
                $posts = [];

                $favoriteMapper = new FavoriteMapper();
                $postsIds = $favoriteMapper->selectByUserId($userId);
                foreach ($postsIds as $postId) {
                    $posts[] = $postMapper->Select($postId);
                }

                $userMapper = new UserMapper();
                $user = $userMapper->select($userId);

                $genreMapper = new GenreMapper();

                $postDtos = array();
                foreach ($posts as $post) {
                    $genre = $genreMapper->Select($post->getGenreId());
                    $userInfo = $userMapper->Select($post->getAuthorId());
                    $isLiked = 'true';

                    $postDtos[] = (new PostDto($post->getId(), $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked))->getProperties();
                }
                $posts = new Collection($postDtos, new PostDtoMapper());

                $genreMapper = new GenreMapper();
                $genres = $genreMapper->SelectAll();

                Application::$app->getRouter()->renderTemplate("likes.html", ["posts" => $posts, "user" => $user, 'genres'=>$genres]);

            } else {
                Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
            }

        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }

    //like?postId=9
    public function saveLike(Request $request): void
    {
        try {
            if (isset($_COOKIE['userId'])) {
                $userMapper = new UserMapper();
                $userId = $_COOKIE['userId'];
                $user = $userMapper->select((int)$userId);

                $body = $request->getBody();
                $postId = $body['postId'];

                $favoriteMapper = new FavoriteMapper();
                $favoriteMapper->saveFavorite($userId, $postId);

                Application::$app->getResponse()->setStatusCode(200);
            } else {
                Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
            }

        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }

    //unlike?postId=9
    public function removeLike(Request $request): void
    {
        try {
            if (isset($_COOKIE['userId'])) {
                $userMapper = new UserMapper();
                $userId = $_COOKIE['userId'];
                $user = $userMapper->select((int)$userId);

                $body = $request->getBody();
                $postId = $body['postId'];

                $favoriteMapper = new FavoriteMapper();
                $favoriteMapper->deleteFavorite($userId, $postId);

                Application::$app->getResponse()->setStatusCode(200);
            } else {
                Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
            }
        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }

    //delete?postId=9
    public function removePost(Request $request): void
    {
        try {
            if (isset($_COOKIE['userId'])) {
                $body = $request->getBody();
                $postId = $body['postId'];

                $postMapper = new PostMapper();
                $postMapper->Delete($postId);

                $commentMapper = new CommentMapper();
                $commentMapper->DeleteByPostId($postId);

                $favoriteMapper = new FavoriteMapper();
                $favoriteMapper->deleteFavoriteByPostId($postId);

                Application::$app->getResponse()->setStatusCode(200);
            } else {
                Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
            }
        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }
}