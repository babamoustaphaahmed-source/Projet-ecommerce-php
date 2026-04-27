<?php
session_start();
include "connexion_base.php";

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM article");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Articles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5;
            padding: 30px;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }
        .boutons {
            margin-bottom: 25px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            transition: 0.3s;
        }
        .btn-ajouter {
            background: #27ae60;
        }
        .btn-ajouter:hover {
            background: #229954;
        }
        .btn-dashboard {
            background: #3498db;
        }
        .btn-dashboard:hover {
            background: #2980b9;
        }
        .btn-deconnexion {
            background: #e74c3c;
            margin-left: auto;
        }
        .btn-deconnexion:hover {
            background: #c0392b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #34495e;
            color: white;
            padding: 15px;
            text-align: left;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background: #f9f9f9;
        }
        img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .prix {
            font-weight: bold;
            color: #27ae60;
        }
        .btn-action {
            padding: 8px 18px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin-right: 8px;
            transition: 0.3s;
            color: white;
        }
        .btn-modifier {
            background: #3498db;
        }
        .btn-modifier:hover {
            background: #2980b9;
        }
        .btn-supprimer {
            background: #e74c3c;
        }
        .btn-supprimer:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Articles</h1>
        
        <div class="boutons">
            <a href="ajout_article.php" class="btn btn-ajouter">➕ Ajouter un article</a>
            <a href="dashboard_admin.php" class="btn btn-dashboard">🏠 Dashboard</a>
            <a href="logout.php" class="btn btn-deconnexion">🚪 Se déconnecter</a>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php while($a = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $a['id_article']; ?></td>
                <td><img src="<?php echo $a['image']; ?>" alt=""></td>
                <td><strong><?php echo htmlspecialchars($a['nom_article']); ?></strong></td>
                <td class="prix"><?php echo number_format($a['prix'], 0, ',', ' '); ?> FCFA</td>
                <td><?php echo $a['stock']; ?> unités</td>
                <td>
                    <a href="modifier_article.php?id=<?php echo $a['id_article']; ?>" class="btn-action btn-modifier">✏️ Modifier</a>
                    <a href="supprimer_article.php?id=<?php echo $a['id_article']; ?>" class="btn-action btn-supprimer" onclick="return confirm('Supprimer cet article ?')">🗑️ Supprimer</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>