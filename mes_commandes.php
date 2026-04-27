<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client'){
    header('Location: login.php');
    exit();
}

$id_user = $_SESSION['id'];

// On récupère toutes les commandes du client connecté
$SQL = "SELECT c.*, a.nom_article, a.image 
        FROM commande c
        JOIN article a ON c.id_article = a.id_article
        WHERE c.id_client = ?
        ORDER BY c.date_commande DESC";
        
$stmt = $conn->prepare($SQL);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f0f2f5;
            padding: 30px; 
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .nav {
            margin-bottom: 30px;
        }
        .nav a {
            display: inline-block;
            margin-right: 10px;
            background: #667eea;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        h1 { margin-bottom: 30px; color: #333; }
        .commande {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .commande img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }
        .infos { flex: 1; }
        .infos h3 { margin-bottom: 8px; color: #333; }
        .infos p { color: #666; margin: 4px 0; }
        .statut {
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            text-align: center;
            min-width: 140px;
        }
        .En_attente { background: #fff3cd; color: #856404; }
        .validee { background: #d4edda; color: #155724; }
        .livree { background: #cce5ff; color: #004085; }
        .annulee { background: #f8d7da; color: #721c24; }
        .prix { font-size: 1.3rem; font-weight: bold; color: #667eea; }
        .vide { 
            text-align: center; 
            padding: 60px; 
            background: white;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard_client.php">← Boutique</a>
            <a href="panier.php">Mon Panier</a>
            <a href="logout.php">Déconnexion</a>
        </div>
        
        <h1>Mes Commandes 📦</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while($cmd = $result->fetch_assoc()): ?>
    <div class="commande">
        <img src="<?php echo $cmd['image']; ?>" alt="">
        <div class="infos">
            <h3><?php echo $cmd['nom_article']; ?></h3>
            <p>Commande #<?php echo $cmd['id_commande']; ?> du <?php echo date('d/m/Y à H:i', strtotime($cmd['date_commande'])); ?></p>
            <p>Quantité : <?php echo $cmd['quantite']; ?></p>
            <p class="prix"><?php echo number_format($cmd['prix_total'], 0, ',', ' '); ?> FCFA</p>
        </div>
        <div class="statut <?php echo $cmd['statut']; ?>">
    <?php 
    $statuts = [
        'En_attente' => 'En attente ⏳',
        'validee' => 'Validée ✅',
        'livree' => 'Livrée 📦',
        'annulee' => 'Annulée ❌',
        'annulée' => 'Annulée ❌'

    ];
    // Si le statut existe dans le tableau, on l'affiche, sinon on met "Inconnu"
    echo $statuts[$cmd['statut']] ?? 'Statut inconnu'; 
    ?>
</div>
    </div>
<?php endwhile; ?>
        <?php else: ?>
            <div class="vide">
                <h2>Tu n'as pas encore de commande</h2>
                <p><a href="dashboard_client.php">Va faire du shopping !</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>