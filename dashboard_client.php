<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client'){
    header('Location: login.php');
    exit();
}

// On récupère tous les articles
$SQL = "SELECT * FROM article WHERE stock > 0 ORDER BY date_ajout DESC";
$result = $conn->query($SQL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f0f2f5;
            padding: 30px; 
        }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 30px; 
            border-radius: 15px; 
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
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
        }
        .logout:hover { background: rgba(255,255,255,0.3); }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        .article {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: 0.3s;
        }
        .article:hover { transform: translateY(-5px); }
        .article img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .info { padding: 20px; }
        .info h3 { 
            margin-bottom: 10px; 
            color: #333;
            font-size: 1.2rem;
        }
        .info p { 
            color: #666; 
            font-size: 0.9rem; 
            margin-bottom: 15px;
            height: 40px;
            overflow: hidden;
        }
        .prix {
            font-size: 1.6rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
        }
        .stock {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 15px;
        }
        .btn-acheter {
            display: block;
            width: 100%;
            background: #667eea;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .btn-acheter:hover { background: #764ba2; }
    </style>
</head>
<body>
    
    <div class="header">
    <a href="logout.php" class="logout" style="margin-right: 10px; background: #28a745;">Se déconnecter</a>
    <a href="mes_commandes.php" class="logout"  style="margin-right: 10px; background: #28a745;">📦 Mes Commandes</a>
    <a href="panier.php" class="logout" style="margin-right: 10px; background: #28a745;">🛒 Mon Panier</a>
    <h1>Bienvenue <?php echo $_SESSION['prenom']; ?> 👋</h1>
    <div class="header-info">
        <p>Nom : <?php echo $_SESSION['no']; ?> | Rôle : <?php echo $_SESSION['role']; ?></p>
    </div>
</div>

    <h2 style="margin-bottom: 25px; color: #333;">Articles disponibles 🛍️</h2>
    <?php if(isset($_GET['added'])): ?>
    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
        Article ajouté au panier ! 🛒
    </div>
<?php endif; ?>
    <div class="grid">
        <?php
        if ($result->num_rows > 0) {
            while($article = $result->fetch_assoc()) {
                echo '<div class="article">';
                
                if(!empty($article['image']) && file_exists($article['image'])){
                    echo '<img src="'.$article['image'].'" alt="'.$article['nom_article'].'">';
                } else {
                    echo '<img src="https://via.placeholder.com/280x220?text=Pas+d%27image" alt="Pas d\'image">';
                }
                
                echo '<div class="info">';
                echo '<h3>'.$article['nom_article'].'</h3>';
                echo '<p>'.$article['description'].'</p>';
                echo '<div class="prix">'.number_format($article['prix'], 0, ',', ' ').' FCFA</div>';
                echo '<span class="stock">Stock: '.$article['stock'].'</span>';
                echo '<a href="ajouter_panier.php?id='.$article['id_article'].'" class="btn-acheter">Ajouter au panier</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>Aucun article disponible pour le moment.</p>";
        }
        ?>
    </div>
    
</body>
</html>