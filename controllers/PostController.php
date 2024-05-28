<?php

namespace app\controllers;

use app\core\Application;
use app\core\CloudinaryUtil;
use app\core\Collection;
use app\core\Request;
use app\mappers\CommentMapper;
use app\mappers\dtoMappers\PostDtoMapper;
use app\mappers\FavoriteMapper;
use app\mappers\GenreMapper;
use app\mappers\PostMapper;
use app\mappers\UserMapper;
use app\models\dto\PostDto;

class PostController
{
    public function getPostFormView(Request $request): void
    {
        if (isset($_COOKIE['userId'])) {
            $genreMapper = new GenreMapper();
            $genres = $genreMapper->SelectAll();

            $userMapper = new UserMapper();
            $userId = $_COOKIE['userId'];
            $user = $userMapper->Select($userId);

            Application::$app->getRouter()->renderTemplate("make_post.html", ['action' => 'make_post', 'genres' => $genres, 'user' => $user]);
        } else {
            Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
        }
    }

    public function createPost(Request $request): void
    {
        if (isset($_COOKIE['userId'])) {
            $userId = (int)$_COOKIE['userId'];
            $userMapper = new UserMapper();
            $user = $userMapper->Select($userId);

            $body = $request->getBody();
            $title = $body['title'];
            $postText = $body['post_text'];
            $genre = $body['genre'];
            $currentDateTime = date('Y-m-d H:i:s');

            $photoUrl = '';
            if (count($_FILES) != 0) {
                $photoUrl = CloudinaryUtil::upload($_FILES['photo']['tmp_name']);
            }

            $postMapper = new PostMapper();
            $post = $postMapper->createObject(["title" => $title,
                "post_text" => $postText,
                "genre_id" => $genre,
                "photo" => $photoUrl,
                "author_id" => $userId,
                "date" => $currentDateTime]);
            $postMapper->Insert($post);


            $genreMapper = new GenreMapper();
            $genre = $genreMapper->Select($post->getGenreId());
            $userInfo = $userMapper->Select($post->getAuthorId());

            $favoriteMapper = new FavoriteMapper();
            $isLiked = 'false';
            if ($favoriteMapper->findByIds($userId, $post->getId())) $isLiked = 'true';

            $postDto = new PostDto($post->getId(), $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked);

            Application::$app->getRouter()->renderTemplate("post.html", ['post' => $postDto, 'user' => $user]);
        } else {
            Application::$app->getRouter()->renderTemplate("login.html", ["action" => 'login']);
        }
    }

    // search post by title
    public function searchByTitle(Request $request): void
    {
        $body = $request->getBody();
        $title = $body['title'];

        $postMapper = new PostMapper();
        $userMapper = new UserMapper();
        $posts = $postMapper->SelectByTitle($title);

        $genreMapper = new GenreMapper();
        $postDtos = array();
        foreach ($posts->getNextRow() as $post) {
            $postId = $post->getId();
            $genre = $genreMapper->Select($post->getGenreId());
            $userInfo = $userMapper->Select($post->getAuthorId());
            $isLiked = 'false';

            $postDtos[] = (new PostDto($postId, $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked))->getProperties();
        }
        $posts = new Collection($postDtos, new PostDtoMapper());

        Application::$app->getRouter()->renderTemplate("filteredPosts.html", ["posts" => $posts]);
    }

    // filter post by genre
    public function filterByGenre(Request $request): void
    {
        $body = $request->getBody();
        $genreId = (int) $body['genre'];

        $genreMapper = new GenreMapper();
        $userMapper = new UserMapper();

        $postMapper = new PostMapper();

        $posts = $postMapper->SelectAll();
        if ($genreId != 0) {
            $posts = $postMapper->SelectByGenreId($genreId);
        }

        $postDtos = array();
        foreach ($posts->getNextRow() as $post) {
            $postId = $post->getId();
            $genre = $genreMapper->Select($post->getGenreId());
            $userInfo = $userMapper->Select($post->getAuthorId());
            $isLiked = 'false';

            $postDtos[] = (new PostDto($postId, $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked))->getProperties();
        }
        $posts = new Collection($postDtos, new PostDtoMapper());

        Application::$app->getRouter()->renderTemplate("filteredPosts.html", ["posts" => $posts]);
    }
}