<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nom = $_POST['nom_article'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    
    $stmt = $conn->prepare("UPDATE article SET nom_article=?, prix=?, description=?, stock=? WHERE id_article=?");
    $stmt->bind_param("sdsii", $nom, $prix, $description, $stock, $id);
    $stmt->execute();
    
    header("Location: liste_article.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM article WHERE id_article = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Article</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f4f6f9; 
            padding: 30px;
        }
        .container { 
            max-width: 700px; 
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
        .form-group { margin-bottom: 20px; }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600; 
            color: #34495e; 
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%; 
            padding: 12px; 
            border: 2px solid #e0e0e0; 
            border-radius: 6px; 
            font-size: 15px;
            transition: 0.3s;
        }
        input:focus, textarea:focus { 
            outline: none; 
            border-color: #3498db; 
        }
        textarea { 
            min-height: 100px; 
            resize: vertical; 
            font-family: Arial;
        }
        .image-preview { 
            margin: 15px 0; 
            padding: 15px; 
            background: #f8f9fa; 
            border-radius: 6px; 
        }
        .image-preview img { 
            border-radius: 8px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .btn { 
            padding: 12px 30px; 
            border: none; 
            border-radius: 6px; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: 0.3s;
        }
        .btn-primary { 
            background: #3498db; 
            color: white; 
        }
        .btn-primary:hover { 
            background: #2980b9; 
            transform: translateY(-2px);
        }
        .btn-secondary { 
            background: #95a5a6; 
            color: white; 
            text-decoration: none; 
            display: inline-block; 
            margin-left: 10px;
        }
        .btn-secondary:hover { 
            background: #7f8c8d; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Modifier : <?php echo $article['nom_article']; ?></h1>
        <form method="POST">
            <div class="form-group">
                <label>Nom de l'article :</label>
                <input type="text" name="nom_article" value="<?php echo $article['nom_article']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Prix (FCFA) :</label>
                <input type="number" name="prix" value="<?php echo $article['prix']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Stock disponible :</label>
                <input type="number" name="stock" value="<?php echo $article['stock']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Description :</label>
                <textarea name="description" required><?php echo $article['description']; ?></textarea>
            </div>
            
            <div class="image-preview">
                <label>Image actuelle :</label><br>
                <img src="<?php echo $article['image']; ?>" width="150">
            </div>
            
            <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
            <a href="liste_article.php" class="btn btn-secondary">↩️ Annuler</a>
        </form>
    </div>
</body>
</html>