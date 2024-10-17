<?php
require 'autoload.php';

use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];
$articleId = $_GET['id'];

$commentRepo = new CommentRepository();
$articleRepo = new ArticleRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['content']) || empty(trim($_POST['content']))) {
        $error = 'Content cannot be empty';
    } else {
        $content = trim($_POST['content']);
        $commentRepo->addComment($userId, $articleId, $content);
        $success = 'Comment added successfully';
    }
}

$article = $articleRepo->find($articleId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un commentaire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            width: 600px;
            text-align: center;
            overflow-y: auto;
        }
        h1 {
            margin-bottom: 20px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un commentaire pour l'article: <?= htmlspecialchars($article->getTitle()) ?></h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="post">
            <textarea name="content" rows="4" cols="50" placeholder="Votre commentaire"></textarea><br>
            <button type="submit"><a href="index.php">Ajouter</a></button>
        </form>
    </div>
</body>
</html>