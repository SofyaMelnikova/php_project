<?php

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

class WelcomeController
{
    public function getWelcomeView(Request $request): void
    {
        try {

            $genreMapper = new GenreMapper();
            $genres = $genreMapper->SelectAll();

            $postMapper = new PostMapper();
            $userMapper = new UserMapper();
            $posts = $postMapper->SelectAll();

            $favoriteMapper = new FavoriteMapper();
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

            Application::$app->getRouter()->renderTemplate("welcome.html", ["posts" => $posts, 'genres' => $genres]);
        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }


    // post&id=8
    public function getPostView(Request $request): void
    {
        try {
            $userMapper = new UserMapper();
            $postMapper = new PostMapper();
            $body = $request->getBody();
            $postId = (int)$body['id'];

            $post = $postMapper->Select($postId);

            $genreMapper = new GenreMapper();
            $genre = $genreMapper->Select($post->getGenreId());
            $userInfo = $userMapper->Select($post->getAuthorId());

            $isLiked = 'false';

            $postDto = new PostDto($post->getId(), $post->getTitle(), $post->getPostText(), $post->getPhoto(), $genre->getName(), $post->getAuthorId(), $userInfo->getPhoto(), $userInfo->getUsername(), $post->getDate(), $isLiked);

            Application::$app->getRouter()->renderTemplate("anon_post.html", ["post" => $postDto]);
        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }

}