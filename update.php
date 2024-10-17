<?php
require 'autoload.php';

session_start();

use App\Entity\User;
use App\Repository\UserRepository;

// Vérifier si l'utilisateur est connecté

$admin = $_SESSION['admin'];
$userId = $_SESSION['user_id'];

// Si l'utilisateur est connecté, on récupère son nom
$userName = $_SESSION['username'];

$userRepo = new UserRepository();
$user = null;

if (isset($_GET['id'])) {
    $user = $userRepo->read($_GET['id']);
}

if (!isset($_SESSION['user_id']) || $_GET['id'] != $userId) {
    header('Location: index.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if ( $user->getRoleAdmin() === true){
        $role_admin = true;
    }else{
        $role_admin = 0;
    }
    
    $errors = [];

    function contientUniquementDesChiffres($chaine) {
        return ctype_digit($chaine);
    }

    if (empty($name)) {
        $errors[] = "Le nom d'utilisateur est obligatoire.";
    } elseif (contientUniquementDesChiffres($name)) {
        $errors[] = "Le nom d'utilisateur ne doit pas contenir uniquement des chiffres.";
    }

    if(empty($mail)){
        $errors[] = "L'email est manquant !";
    }elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Le format de l'email n'est pas valide.";
    }
    

    if (!empty($password) && !empty($confirm_password)){
        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
    
        if ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photo = $_FILES['photo']['name'];
        $tailleFichier = $_FILES['photo']['size'];
        $typeFichier = $_FILES['photo']['type'];
        $target_dir = "uploads/";

        if ($tailleFichier > 25 * 1024 * 1024) {
            $errors[] = "Le fichier est trop grand. Maximum 25 Mo.";
        }

        $typesAutorises = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($typeFichier, $typesAutorises)) {
            $errors[] = "Seuls les fichiers JPG, PNG et GIF sont autorisés.";
        }

        $nouveauNomFichier = uniqid() . '-' . $photo;
        $target_file = $target_dir . $nouveauNomFichier;

        if (!empty($user->getMediaObject()) && file_exists($user->getMediaObject())) {
            unlink($user->getMediaObject());
        }

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $errors[] = "Erreur lors du téléchargement du fichier.";
        }
    } else {
        $target_file = $user->getMediaObject();
    }

    if (empty($errors)) {
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
        } else {
            $password_hash = $user->getPassword();
        }

        // Créer l'objet utilisateur avec les données mises à jour
        $updatedUser = new User($name, $mail, $password_hash, $target_file, $user->getId());

        try {
            $userRepo->update($updatedUser);
            var_dump($updatedUser);
            header('Location: index.php');
            exit;
        } catch(Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    } else {
        echo "<h2>Des erreurs ont été trouvées :</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }
}

if ($user === null) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            width: 400px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        label {
            margin-top: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: block;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modifier un Utilisateur</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $user->getId() ?>">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->getUsername()) ?>" required>
            <label for="mail">E-mail:</label>
            <input type="email" id="mail" name="mail" value="<?= htmlspecialchars($user->getMail()) ?>" required>
            <label>Nouveau mot de passe (optionnel):</label>
            <input type="password" name="password">
            <label>Confirmez votre mot de passe:</label>
            <input type="password" name="confirm_password">
            <label>Changer la photo de profil (optionnel):</label>
            <input type="file" name="photo">
            <button type="submit" name="modification">Modifier l'utilisateur</button>
        </form>
        <a href="list_users.php">Retour à la liste des utilisateurs</a>

    </div>
</body>
</html>