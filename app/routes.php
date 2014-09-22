<?php

use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\Domain\Article;
use MicroCMS\Domain\User;
use MicroCMS\Form\Type\CommentType;

// Home page
$app->get('/', function () use ($app) {
    $articles = $app['dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
});

// Detailed info about an article
$app->match('/article/{id}', function ($id, Request $request) use ($app) {
    $article = $app['dao.article']->find($id);
    $user = $app['security']->getToken()->getUser();
    $commentFormView = null;
    if ($user != 'anon.') {
        // A user is fully authenticated
        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setAuthor($user);
        $commentForm = $app['form.factory']->create(new CommentType(), $comment);
        if ($request->isMethod('POST')) {
            $commentForm->bind($request);
            if ($commentForm->isValid()) {
                $app['dao.comment']->save($comment);
                $app['session']->getFlashBag()->add('success', 'Your comment was succesfully added.');
            }
        }
        $commentFormView = $commentForm->createView();
    }
    $comments = $app['dao.comment']->findAllByArticle($id);
    return $app['twig']->render('article.html.twig', array(
        'article' => $article, 
        'comments' => $comments,
        'commentFormView' => $commentFormView));
});

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        ));
})->bind('login');  // named route so that path('login') works in Twig templates