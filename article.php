<?php
    require 'autoload.php';

    use App\Repository\ArticleRepository;

    session_start();

    if (!isset($_GET['id'])) {
        echo "Aucun article spécifié.";
        exit;
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $articleId = $_GET['id'];
    $articleRepo = new ArticleRepository();
    $article = $articleRepo->find($articleId);

    if (!$article) {
        echo "L'article n'existe pas.";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
        }
        img {
            display: block;
            margin: 20px auto;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><?= htmlspecialchars($article->getTitle()) ?></h1>
    <p><?= htmlspecialchars($article->getContent()) ?></p>

    <?php if ($article->getImage()): ?>
        <img src="<?= htmlspecialchars($article->getImage()) ?>" alt="Image de l'article" width="300">
    <?php endif; ?>

    <br/>

    <p><strong>Auteur :</strong> <?= htmlspecialchars($article->getAuthorName()) ?></p>

    <br>
    <a href="index.php">Retour à la liste des articles</a>
</div>
</body>
</html>