<?php

// Register global error and exception handlers
use Symfony\Component\Debug\ErrorHandler;
ErrorHandler::register();
use Symfony\Component\Debug\ExceptionHandler;
ExceptionHandler::register();
use Symfony\Component\HttpFoundation\Response;

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => $app->share(function () use ($app) {
                return new MicroCMS\DAO\UserDAO($app['db']);
            }),
        ),
    ),
));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => $app['monolog.logfile'],
    'monolog.name' => 'MicroCMS',
    'monolog.level' => $app['monolog.level']
));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
if (isset($app['debug']) and $app['debug']) {
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir' => __DIR__.'/../var/cache/profiler'
    ));
}
$app->error(function (\Exception $e, $code) use ($app) {
    switch ($code) {
        case 404:
            $message = 'The requested resource could not be found.';
            break;
        default:
            $message = "Something went wrong.";
    }
    return $app['twig']->render('error.html.twig', array('message' => $message));
});

// Register services.
$app['dao.article'] = $app->share(function ($app) {
    return new MicroCMS\DAO\ArticleDAO($app['db']);
});
$app['dao.user'] = $app->share(function ($app) {
    return new MicroCMS\DAO\UserDAO($app['db']);
});
$app['dao.comment'] = $app->share(function ($app) {
    $commentDAO = new MicroCMS\DAO\CommentDAO($app['db']);
    $commentDAO->setArticleDAO($app['dao.article']);
    $commentDAO->setUserDAO($app['dao.user']);
    return $commentDAO;
});

