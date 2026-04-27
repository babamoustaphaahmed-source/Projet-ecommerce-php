<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: admin_commandes.php");
    exit;
}

$id_commande = $_GET['id'];

// On récupère les infos de la commande + le client
$stmt = $conn->prepare("
    SELECT c.*, u.no, u.prenom, u.email 
    FROM commande c 
    JOIN utilisateur u ON c.id_client = u.id 
    WHERE c.id_commande = ?
");
$stmt->bind_param("i", $id_commande);
$stmt->execute();
$commande = $stmt->get_result()->fetch_assoc();

if(!$commande){
    die("Commande introuvable");
}

// Pour l'instant ta table commande a id_article, donc 1 commande = 1 article
// Si plus tard tu veux plusieurs articles par commande, faudra une table commande_detail
?>

<!DOCTYPE html>
<html>
<head>
    <title>Détails Commande #<?php echo $id_commande; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f4f6f9; 
            padding: 30px;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #2c3e50; 
            margin-bottom: 25px; 
            border-bottom: 3px solid #3498db; 
            padding-bottom: 10px;
        }
        .info-box {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .info-box p { 
            margin: 8px 0; 
            font-size: 15px;
        }
        .info-box strong { color: #2c3e50; }
        .article-card {
            display: flex;
            gap: 20px;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            align-items: center;
        }
        .article-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        .article-details { flex: 1; }
        .article-details h3 { 
            color: #2c3e50; 
            margin-bottom: 10px; 
            font-size: 22px;
        }
        .detail-line {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        .detail-line:last-child { border: none; }
        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-attente { background: #f39c12; color: white; }
        .badge-validee { background: #27ae60; color: white; }
        .badge-livree { background: #3498db; color: white; }
        .total-box {
            margin-top: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            text-align: right;
        }
        .total-box h2 { font-size: 32px; }
        .btn-retour {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #95a5a6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-retour:hover { background: #7f8c8d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Détails Commande #<?php echo $commande['id_commande']; ?></h1>
        
        <div class="info-box">
            <p><strong>Client :</strong> <?php echo $commande['prenom'] . ' ' . $commande['no']; ?></p>
            <p><strong>Email :</strong> <?php echo $commande['email']; ?></p>
            <p><strong>Date :</strong> <?php echo date('d/m/Y à H:i', strtotime($commande['date_commande'])); ?></p>
            <p><strong>Statut :</strong> 
                <span class="badge badge-<?php echo strtolower(str_replace(' ', '', $commande['statut'])); ?>">
                    <?php echo $commande['statut']; ?>
                </span>
            </p>
        </div>

        <?php
        // On récupère l'article de la commande
        $stmt = $conn->prepare("SELECT * FROM article WHERE id_article = ?");
        $stmt->bind_param("i", $commande['id_article']);
        $stmt->execute();
        $article = $stmt->get_result()->fetch_assoc();
        ?>

        <div class="article-card">
            <img src="<?php echo $article['image']; ?>" alt="<?php echo $article['nom_article']; ?>">
            <div class="article-details">
                <h3><?php echo $article['nom_article']; ?></h3>
                <div class="detail-line">
                    <span>Prix unitaire :</span>
                    <strong><?php echo number_format($article['prix'], 0, ',', ' '); ?> FCFA</strong>
                </div>
                <div class="detail-line">
                    <span>Quantité :</span>
                    <strong><?php echo $commande['quantite']; ?></strong>
                </div>
                <div class="detail-line">
                    <span>Sous-total :</span>
                    <strong style="color: #27ae60;"><?php echo number_format($commande['prix_total'], 0, ',', ' '); ?> FCFA</strong>
                </div>
            </div>
        </div>

        <div class="total-box">
            <p style="opacity: 0.9; margin-bottom: 5px;">Total de la commande</p>
            <h2><?php echo number_format($commande['prix_total'], 0, ',', ' '); ?> FCFA</h2>
        </div>

        <a href="admin_commandes.php" class="btn-retour">↩️ Retour aux commandes</a>
    </div>
</body>
</html>