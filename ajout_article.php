<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id'])){
    header('Location: login.php');
    exit();
}

$message = "";
if(isset($_POST['ajouter'])){
    $nom = $_POST['nom_article'];
    $desc = $_POST['description'];
    $prix = $_POST['prix'];
    $stock = $_POST['stock'];
    $id_admin = $_SESSION['id'];
    
    // 1. GESTION DE L'IMAGE
    $image = "";
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $dossier = "uploads/";
        if(!is_dir($dossier)) mkdir($dossier);
        
        $nom_image = time() . '_' . basename($_FILES['image']['name']);
        $chemin = $dossier . $nom_image;
        
        if(move_uploaded_file($_FILES['image']['tmp_name'], $chemin)){
            $image = $chemin;
        }
    }

    // 2. INSERTION DANS LA BDD
    $SQL = "INSERT INTO article (nom_article, description, prix, stock, id_admin, image) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($SQL);
    $stmt->bind_param("ssdiis", $nom, $desc, $prix, $stock, $id_admin, $image);
    
    if($stmt->execute()){
        $message = "Article ajouté avec succès !";
    } else {
        $message = "Erreur : " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f0f2f5;
            padding: 30px; 
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        h1 { 
            color: #333; 
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group { margin-bottom: 25px; }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 14px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: 0.3s;
        }
        input:focus, textarea:focus {
            border-color: #667eea;
            outline: none;
        }
        input[type="file"] {
            border: 2px dashed #667eea;
            padding: 25px;
            background: #f8f9ff;
            cursor: pointer;
        }
        textarea { min-height: 120px; resize: vertical; }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }
        button:hover { 
            opacity: 0.9; 
            transform: translateY(-2px);
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        .retour {
            display: inline-block;
            margin-top: 25px;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        small {
            display: block;
            color: #888;
            margin-top: 5px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un nouvel article 📝</h1>
        
        <?php if($message != "") echo '<div class="message">'.$message.'</div>'; ?>
        
        <form method="POST" enctype="multipart/form-data">
            
            <!-- 1. IMAGE EN PREMIER -->
            <div class="form-group">
                <label>Image de l'article :</label>
                <input type="file" name="image" accept="image/*" required>
                <small>Clique pour choisir une photo dans tes dossiers : JPG, PNG, WEBP</small>
            </div>

            <!-- 2. NOM DE L'ARTICLE -->
            <div class="form-group">
                <label>Nom de l'article :</label>
                <input type="text" name="nom_article" placeholder="Ex: iPhone 15 Pro Max" required>
            </div>
            
            <!-- 3. DESCRIPTION -->
            <div class="form-group">
                <label>Description :</label>
                <textarea name="description" placeholder="Décris ton article..." required></textarea>
            </div>
            
            <!-- 4. PRIX -->
            <div class="form-group">
                <label>Prix en FCFA :</label>
                <input type="number" step="0.01" name="prix" placeholder="Ex: 500000" required>
            </div>
            
            <!-- 5. STOCK -->
            <div class="form-group">
                <label>Quantité en stock :</label>
                <input type="number" name="stock" placeholder="Ex: 10" required>
            </div>
            
            <button type="submit" name="ajouter">Publier l'article</button>
        </form>
        
        <a href="<?php echo $_SESSION['role'] == 'admin' ? 'dashboard_admin.php' : 'dashboard_client.php'; ?>" class="retour">← Retour au dashboard</a>
    </div>
</body>
</html>