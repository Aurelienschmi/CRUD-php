<?php
    require 'autoload.php';

    use App\Repository\UserRepository;

    session_start();

    if ($_SESSION['admin'] === false) {
        header('Location: login.php');
        exit;
    }

    // Si l'utilisateur est connecté, on récupère son nom
    $userName = $_SESSION['username'];
    $admin = $_SESSION['admin'];

    $userRepo = new UserRepository();
    $users = $userRepo->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            border-radius: 50%;
        }
        .actions a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <p>Welcome <?php echo $userName ?></p>
    <h1>Liste des Utilisateurs</h1>
    <?php
        if($admin) {
            echo '<a href="create.php">Créer un Nouvel Utilisateur</a>';
        }
    ?>
    <br/><br/>
    <a href="logout.php">Deconnexion</a>
    <br/><br/>
    <a href="index.php">Liste des articles</a>
    <table>
        <tr>
            <th>Username</th>
            <th>E-mail</th>
            <th>Photo</th>
            <?php
                if($admin) {
                    echo '<th>Actions</th>';
                }
            ?>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><a href="read.php?id=<?= $user->getId() ?>" style="text-decoration:none;"><?= $user->getUsername() ?></a></td>
            <td><a href="read.php?id=<?= $user->getId() ?>" style="text-decoration:none;"><?= $user->getMail() ?></a></td>
            <td><a href="read.php?id=<?= $user->getId() ?>" style="text-decoration:none;"><img src="<?= htmlspecialchars($user->getMediaObject()); ?>" width="50" height="50"></a></td>
            <?php
                if($admin) {
                    echo '<td class="actions">
                        <a href="update.php?id='.$user->getId().'">Modifier</a>
                        <a href="delete.php?id='.$user->getId().'" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet utilisateur ?\');">Supprimer</a>
                    </td>';
                }
            ?>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
