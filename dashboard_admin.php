<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header('Location: login.php');
    exit();
}

// 1. COMPTER LES UTILISATEURS
$SQL_users = "SELECT COUNT(*) as total FROM utilisateur";
$result_users = $conn->query($SQL_users);
$nb_users = $result_users->fetch_assoc()['total'];

// 2. COMPTER LES ARTICLES - ICI LA CORRECTION
$SQL_articles = "SELECT COUNT(*) as total FROM article";  // article SANS S
$result_articles = $conn->query($SQL_articles);
$nb_articles = $result_articles->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f0f2f5;
            padding: 30px; 
        }
        .header { 
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white; 
            padding: 30px; 
            border-radius: 15px; 
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(238,90,36,0.3);
        }
        .header h1 { font-size: 2.2rem; margin-bottom: 10px; }
        .header-info { opacity: 0.9; font-size: 0.95rem; }
        .logout { 
            float: right; 
            color: white; 
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        .logout:hover { 
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border-left: 5px solid #ff6b6b;
        }
        .card h3 { 
            color: #666; 
            font-size: 0.9rem; 
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .card .number { 
            font-size: 2.5rem; 
            font-weight: bold; 
            color: #ee5a24;
        }
        .menu {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .menu h2 { margin-bottom: 20px; color: #333; }
        .menu a {
            display: inline-block;
            background: #ff6b6b;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            margin-right: 15px;
            margin-bottom: 10px;
            font-weight: bold;
            transition: 0.3s;
        }
        .menu a:hover { 
            background: #ee5a24;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="logout.php" class="logout">Se déconnecter</a>
        <h1>Panel Admin 👑</h1>
        <div class="header-info">
            <p><strong>Bienvenue <?php echo $_SESSION['prenom']; ?></strong></p>
            <p>Matricule : <?php echo $_SESSION['no']; ?> | Rôle : <?php echo $_SESSION['role']; ?></p>
        </div>
    </div>

    <div class="stats">
        <div class="card">
            <h3>Utilisateurs inscrits</h3>
            <div class="number"><?php echo $nb_users; ?></div>
        </div>
        <div class="card">
            <h3>Articles publiés</h3>
            <div class="number"><?php echo $nb_articles; ?></div>
        </div>
        <div class="card">
            <h3>Ventes du jour</h3>
            <div class="number">0</div>
        </div>
    </div>
    
    <div class="menu">
    <h2>Menu Administration</h2>
    <a href="ajout_article.php">➕ Ajouter un article</a>
    <a href="liste_article.php">📦 Voir/Gérer les articles</a>
    <a href="liste_utilisateur.php">👥 Gérer les utilisateurs</a>
    <a href="#">📊 Statistiques</a>
    <a href="admin_commandes.php" class="btn">📦 Gérer les Commandes</a>
    
</div>
    
</body>
</html>