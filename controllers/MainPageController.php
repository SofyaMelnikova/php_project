<?php

declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Collection;
use app\core\Request;
use app\core\Response;
use app\mappers\dtoMappers\PostDtoMapper;
use app\mappers\FavoriteMapper;
use app\mappers\GenreMapper;
use app\mappers\PostMapper;
use app\mappers\UserMapper;
use app\models\dto\PostDto;
use Exception;

class MainPageController
{
    // main page
    public function getMainView(Request $request): void
    {
        try {
            if (isset($_COOKIE['userId'])) {

                $genreMapper = new GenreMapper();
                $genres = $genreMapper->SelectAll();

                $postMapper = new PostMapper();
                $posts = $postMapper->SelectAll();

                $userMapper = new UserMapper();
                $userId = (int)$_COOKIE['userId'];
                $user = $userMapper->Select($userId);

                $favoriteMapper = new FavoriteMapper();
                $genreMapper = new GenreMapper();
                $postDtos = array();
                foreach ($posts->getNextRow() as $post) {
                    $postId = $post->getId();
                    $genre = $genreMapper->Select($post->getGenreId());
                    $userInfo = $userMapper->Select($post->getAuthorId());
                    $isLiked = 'false';
                    if ($favoriteMapper->findByIds($userId, $postId)) $isLiked = 'true';

                    $postDtos[] = (new PostDto($postId, $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked))->getProperties();
                }
                $posts = new Collection($postDtos, new PostDtoMapper());

                Application::$app->getRouter()->renderTemplate("main.html", ["posts" => $posts, "user" => $user, 'genres' => $genres]);

            } else {
                Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
            }

        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }

    // get User
    public function getUserView(Request $request): void
    {
        try {
            $body = $request->getBody();
            $uId = (int)$body['id'];
            $postMapper = new PostMapper();
            $posts = $postMapper->SelectByUserId($uId);

            $userMapper = new UserMapper();
            $u = $userMapper->Select($uId);

            $posts = $postMapper->SelectByUserId($uId);
            $postDtos = array();
            $genreMapper = new GenreMapper();
            foreach ($posts->getNextRow() as $post) {
                $postId = $post->getId();
                $genre = $genreMapper->Select($post->getGenreId());
                $userInfo = $userMapper->Select($post->getAuthorId());
                $isLiked = 'false';

                $postDtos[] = (new PostDto($postId, $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked))->getProperties();
            }
            $posts = new Collection($postDtos, new PostDtoMapper());

            if (isset($_COOKIE['userId'])) {
                $userId = (int)$_COOKIE['userId'];
                $user = $userMapper->Select($userId);
                Application::$app->getRouter()->renderTemplate("user.html", ["u" => $u, "user" => $user, "posts" => $posts]);

            } else {
                Application::$app->getRouter()->renderTemplate("anon_user.html", ["u" => $u, "posts" => $posts]);
            }

        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }


    // post&id=8
    public function getPostView(Request $request): void
    {
        try {
            $postMapper = new PostMapper();
            $body = $request->getBody();
            $postId = (int)$body['id'];
            $post = $postMapper->Select($postId);

            $userMapper = new UserMapper();
            $u = $userMapper->Select($post->getAuthorId());

            $genreMapper = new GenreMapper();
            $genre = $genreMapper->Select($post->getGenreId());
            $userInfo = $userMapper->Select($post->getAuthorId());

            $favoriteMapper = new FavoriteMapper();
            $isLiked = 'false';

            $postDto = new PostDto($post->getId(), $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked);


            if (isset($_COOKIE['userId'])) {
                $userId = (int)$_COOKIE['userId'];
                $user = $userMapper->Select($userId);
                Application::$app->getRouter()->renderTemplate("post.html", ["u" => $u, "user" => $user, "post" => $postDto]);

            } else {
                Application::$app->getRouter()->renderTemplate("anon_post.html", ["u" => $u, "post" => $postDto]);
            }

        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }

//        if (isset($_COOKIE['userId'])) {
//            $userMapper = new UserMapper();
//            $userId = (int)$_COOKIE['userId'];
//            $user = $userMapper->Select($userId);
//
//            $postMapper = new PostMapper();
//            $body = $request->getBody();
//            $postId = (int)$body['id'];
//            $post = $postMapper->Select($postId);
//
//
//            $genreMapper = new GenreMapper();
//            $genre = $genreMapper->Select($post->getGenreId());
//            $userInfo = $userMapper->Select($post->getAuthorId());
//
//            $favoriteMapper = new FavoriteMapper();
//            $isLiked = 'false';
//            if ($favoriteMapper->findByIds($userId, $postId)) $isLiked = 'true';
//
//            $postDto = new PostDto($post->getId(), $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked);
//
//
//            Application::$app->getRouter()->renderTemplate("post.html", ['action' => 'comment', 'user' => $user, 'post' => $postDto]);
//        } else {
//            Application::$app->getRouter()->renderTemplate("anon_post.html",);
//        }
    }
}