<?php
    require 'autoload.php';
    
    use App\Repository\ArticleRepository;
    use App\Repository\LikeRepository;
    use App\Repository\CommentRepository;

    session_start();
    
    $articleRepo = new ArticleRepository();
    $findArticles = $articleRepo->findAll();
    
    //inverser les articles
    $articles = array_reverse($findArticles);

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    // Si l'utilisateur est connecté, on récupère son nom
    $userName = $_SESSION['username'];
    $id = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des articles</title>
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
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        li h2 {
            margin: 0 0 10px 0;
        }
        li img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        li a {
            color: #007BFF;
            text-decoration: none;
        }
        li a:hover {
            text-decoration: underline;
        }
        .welcome {
            margin-bottom: 20px;
        }
        .logout {
            margin-bottom: 20px;
        }
        .create-article {
            display: block;
            margin-bottom: 20px;
            color: #007BFF;
            text-decoration: none;
        }
        .create-article:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="welcome">Welcome <?php echo $userName ?></p>
        <a class="logout" href="logout.php">Deconnexion</a>
        <a href="read.php?id=<?php echo $id ?>" >Profile</a>
        <h1>Liste des articles</h1>
        <a class="create-article" href="create_article.php">Créer un article</a>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <?php 
                        $id = $article->getId();
                        $art = $articleRepo->find($id);

                        $likeRepo = new LikeRepository();
                        $likes = $likeRepo->getLikesCount($id);

                        $commentRepo = new CommentRepository();
                        $comments = $commentRepo->getCommentsByArticleId($id);
                        ?>

                    <h2><a href="read.php?id=<?= $art->getUserId() ?>" style="text-decoration:none;"><?= $art->getAuthorName() ?> </h2>
                    <?php if ($article->getImage()): ?>
                        <img src="<?= htmlspecialchars($article->getImage()) ?>" alt="Image de l'article">
                    <?php endif; ?>
                    <br/>
                    <button><a href="like_article.php?id=<?= $article->getId() ?>"style="text-decoration:none;">like <?php echo $likes  ?></a></button>
                    <a href="article.php?id=<?= $article->getId() ?>">Lire l'article</a>
                    <a href="delete_articles.php?id=<?= $article->getId() ?>">Supprimer</a>
                    <br>
                    <a href="add_comment.php?id=<?= $article->getId() ?>">Ajouter un commentaire</a>
                    <h3>Commentaires</h3>
                    <ul id="comment-list-<?= $article->getId() ?>">
                        <?php foreach ($comments as $comment): ?>
                            <li><?= htmlspecialchars($comment->getContent()) ?> - <em><?= htmlspecialchars($comment->getCreatedAt()->format('Y-m-d H:i:s')) ?></em></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>