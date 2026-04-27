<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: liste_article.php");
    exit;
}

$id = $_GET['id'];

// Si l'admin a cliqué sur "Confirmer"
if(isset($_POST['confirmer'])){
    // On récupère l'image pour la supprimer
    $stmt = $conn->prepare("SELECT image FROM article WHERE id_article = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0){
        $article = $result->fetch_assoc();
        $image_path = $article['image'];
        
        if(!empty($image_path) && file_exists($image_path)){
            unlink($image_path);
        }
    }
    
    // On supprime de la BDD
    $stmt = $conn->prepare("DELETE FROM article WHERE id_article = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header("Location: liste_article.php");
    exit;
}

// On récupère les infos pour afficher la confirmation
$stmt = $conn->prepare("SELECT * FROM article WHERE id_article = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();

if(!$article){
    header("Location: liste_article.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supprimer Article</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f4f6f9; 
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container { 
            max-width: 550px; 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            text-align: center;
        }
        .warning-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        h1 { 
            color: #e74c3c; 
            margin-bottom: 15px; 
            font-size: 26px;
        }
        .article-info {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .article-info img {
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .article-info p {
            margin: 8px 0;
            color: #856404;
            font-size: 15px;
        }
        .article-info strong {
            color: #2c3e50;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
        }
        .btn { 
            padding: 14px 35px; 
            border: none; 
            border-radius: 6px; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-danger { 
            background: #e74c3c; 
            color: white; 
        }
        .btn-danger:hover { 
            background: #c0392b; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
        }
        .btn-secondary { 
            background: #95a5a6; 
            color: white; 
        }
        .btn-secondary:hover { 
            background: #7f8c8d; 
        }
        .danger-text {
            color: #e74c3c;
            font-weight: 600;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="warning-icon">⚠️</div>
        <h1>Confirmer la suppression</h1>
        <p class="danger-text">Cette action est irréversible !</p>
        
        <div class="article-info">
            <img src="<?php echo $article['image']; ?>" width="120">
            <p><strong>Nom :</strong> <?php echo $article['nom_article']; ?></p>
            <p><strong>Prix :</strong> <?php echo $article['prix']; ?> FCFA</p>
            <p><strong>Stock :</strong> <?php echo $article['stock']; ?></p>
        </div>
        
        <form method="POST">
            <div class="btn-group">
                <button type="submit" name="confirmer" class="btn btn-danger">🗑️ Oui, supprimer</button>
                <a href="liste_article.php" class="btn btn-secondary">↩️ Non, annuler</a>
            </div>
        </form>
    </div>
</body>
</html>