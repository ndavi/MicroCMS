<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <link href="microcms.css" rel="stylesheet" />
    <title>MicroCMS - Home</title>
</head>
<body>
    <header>
        <h1>MicroCMS</h1>
    </header>
    <?php foreach ($articles as $article): ?>
        <article>
            <h2><?= $article['art_title'] ?></h2>
            <p><?= $article['art_content'] ?></p>
        </article>
    <?php endforeach; ?>
    <footer class="footer">
        <a href="https://github.com/bpesquet/MicroCMS">MicroCMS</a> is a minimalist CMS built as a showcase for modern PHP development.
    </footer>
</body>
</html>