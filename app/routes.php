<?php

use Symfony\Component\HttpFoundation\Request;

// Home page
$app->get('/', function () use ($app) {
    $articles = $app['dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
});

// Detailed info about an article
$app->get('/article/{id}', function ($id) use ($app) {
    $article = $app['dao.article']->find($id);
    $comments = $app['dao.comment']->findAllByArticle($id);
    return $app['twig']->render('article.html.twig', array('article' => $article, 'comments' => $comments));
});

$app->get('/admin', function() {
    return "Admin zone";
});

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});