<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client'){
    header('Location: login.php');
    exit();
}

$id_user = $_SESSION['id'];

// On récupère tous les articles du panier de ce client
$SQL = "SELECT p.*, a.nom_article, a.prix, a.image, a.stock 
        FROM panier p 
        JOIN article a ON p.id_article = a.id_article 
        WHERE p.id_utilisateur = ?";  // <- id_utilisateur
        
$stmt = $conn->prepare($SQL);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
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
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        h1 { margin-bottom: 30px; color: #333; }
        .retour {
            display: inline-block;
            margin-bottom: 20px;
            background: #667eea;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            color: #333;
        }
        td {
            padding: 15px;
            border-top: 1px solid #eee;
            vertical-align: middle;
        }
        .img-article {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }
        .prix { font-weight: bold; color: #667eea; }
        .total-box {
            text-align: right;
            font-size: 1.5rem;
            font-weight: bold;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .btn-commander {
            background: #28a745;
            color: white;
            padding: 16px 40px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            float: right;
            margin-top: 20px;
        }
        .btn-supprimer {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .vide {
            text-align: center;
            padding: 60px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard_client.php" class="retour">← Continuer mes achats</a>
        <h1>Mon Panier 🛒</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Nom</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = $result->fetch_assoc()): 
                        $sous_total = $item['prix'] * $item['quantite'];
                        $total += $sous_total;
                    ?>
                    <tr>
                        <td>
                            <?php if(!empty($item['image']) && file_exists($item['image'])): ?>
                                <img src="<?php echo $item['image']; ?>" class="img-article">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/70x70?text=No+Img" class="img-article">
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo $item['nom_article']; ?></strong></td>
                        <td class="prix"><?php echo number_format($item['prix'], 0, ',', ' '); ?> FCFA</td>
                        <td><?php echo $item['quantite']; ?></td>
                        <td class="prix"><?php echo number_format($sous_total, 0, ',', ' '); ?> FCFA</td>
                        <td>
                            <a href="supprimer_panier.php?id=<?php echo $item['id_article']; ?>" class="btn-supprimer">Supprimer</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="total-box">
                Total : <?php echo number_format($total, 0, ',', ' '); ?> FCFA
            </div>
            
            <a href="commander.php" class="btn-commander" style="text-decoration:none; display:inline-block;">Passer la commande</a>
            
        <?php else: ?>
            <div class="vide">
                <h2>Ton panier est vide 😢</h2>
                <p><a href="dashboard_client.php">Va ajouter des articles</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>