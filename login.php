<?php
session_start();
require 'autoload.php';

use App\Repository\UserRepository;

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    if (empty($mail)) {
        $errors[] = "L'email est obligatoire.";
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire.";
    }

    if (empty($errors)) {
        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($mail);

        if ($user && password_verify($password, $user->getPassword())) {
            try {
                $userRepo->updateLastConnection($user->getId());
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUsername();
                $_SESSION['admin'] = $user->getRoleAdmin();
                
                header('Location: index.php');
                exit;
            } catch(Exception $e) {
                echo "Erreur: " . $e->getMessage();
            }
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .errors {
            color: red;
            margin-bottom: 10px;
        }
        a {
            display: block;
            margin-top: 10px;
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
        <h1>Connexion</h1>

        <?php
        // Affichage des erreurs
        if (!empty($errors)) {
            echo "<ul class='errors'>";
            foreach ($errors as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul>";
        }
        ?>

        <form method="POST" action="login.php">
            <label for="mail">E-mail:</label>
            <input type="email" id="mail" name="mail" required>
            <br>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Se connecter</button>
        </form>

        <br>
        <a href="create.php">Créer un compte</a>
    </div>
</body>
</html>
