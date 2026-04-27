<?php
session_start();
include "connexion_base.php";
$message = "";

if (isset($_POST['inscrire'])){
    $no = $_POST['no'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motpasse_clair = $_POST['motpasse'];
    
    $Check = $conn->prepare("SELECT id FROM utilisateur WHERE email = ?");
    $Check->bind_param('s', $email);
    $Check->execute();
    if($Check->get_result()->num_rows > 0){
        $message = "Cet email est déjà utilisé";
    } else {
        $motpasse_hash = password_hash($motpasse_clair, PASSWORD_DEFAULT);
        $SQL = "INSERT INTO utilisateur (no, prenom, email, motpasse) VALUES (?, ?, ?, ?)";
        $Stmt = $conn->prepare($SQL);
        $Stmt->bind_param('ssss', $no, $prenom, $email, $motpasse_hash);
        
        if ($Stmt->execute()){
            $message = "Compte créé ! <a href='login.php'>Se connecter</a>";
        } else {
            $message = "Erreur : " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Site Vente</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            background: #e3f2fd;
            color: #1976d2;
        }
        .message a { color: #1976d2; font-weight: bold; }
        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Créer un compte</h1>
        <p class="subtitle">Rejoins le site de vente</p>
        
        <?php if($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="no" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="password" name="motpasse" placeholder="Mot de passe" required>
            <button type="submit" name="inscrire">S'inscrire</button>
        </form>
        
        <p class="link">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>