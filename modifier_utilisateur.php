<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $no = $_POST['no'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    $stmt = $conn->prepare("UPDATE utilisateur SET no=?, prenom=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("ssssi", $no, $prenom, $email, $role, $id);
    $stmt->execute();
    
    header("Location: liste_utilisateur.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Utilisateur</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f4f6f9; 
            padding: 30px;
        }
        .container { 
            max-width: 600px; 
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
        input[type="text"], input[type="email"], select {
            width: 100%; 
            padding: 12px; 
            border: 2px solid #e0e0e0; 
            border-radius: 6px; 
            font-size: 15px;
            transition: 0.3s;
        }
        input:focus, select:focus { 
            outline: none; 
            border-color: #3498db; 
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
        .btn-secondary:hover { background: #7f8c8d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Modifier : <?php echo $user['prenom']; ?></h1>
        <form method="POST">
            <div class="form-group">
                <label>Nom :</label>
                <input type="text" name="no" value="<?php echo $user['no']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Prénom :</label>
                <input type="text" name="prenom" value="<?php echo $user['prenom']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email :</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Rôle :</label>
                <select name="role" required>
                    <option value="client" <?php echo $user['role'] == 'client' ? 'selected' : ''; ?>>👤 Client</option>
                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>👑 Admin</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
            <a href="liste_utilisateur.php" class="btn btn-secondary">↩️ Annuler</a>
        </form>
    </div>
</body>
</html>