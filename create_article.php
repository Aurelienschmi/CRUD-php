<?php
    require 'autoload.php';

    use App\Entity\Article;
    use App\Repository\ArticleRepository;

    session_start();
    
    $articleRepo = new ArticleRepository();
    $articles = $articleRepo->findAll();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $userId = $_SESSION['user_id']; // Assurez-vous que l'utilisateur est connecté
        
        // Gestion de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image = $_FILES['image']['name'];
            $imagePath = 'uploads/' . uniqid() . '-' . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            $imagePath = null;
        }

        // Enregistrement dans la base de données
        $article = new Article($userId, $title, $content, $imagePath);
        $articleRepo = new ArticleRepository();
        $articleRepo->create($article);

        header('Location: index.php'); // Redirection vers la liste des articles
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un article</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        textarea,
        input[type="file"] {
            width: 90%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
        resize: vertical; /* Prevent horizontal resizing */
    }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <form action="create_article.php" method="POST" enctype="multipart/form-data">
        <label for="title">Titre :</label>
        <input type="text" name="title" required>
        <br>

        <label for="content">Contenu :</label>
        <textarea name="content" required></textarea>
        <br>

        <label for="image">Image :</label>
        <input type="file" name="image" required>
        <br>

        <button type="submit">Ajouter l'article</button>
    </form>
</body>
</html>
