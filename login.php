<?php
session_start();
include "connexion_base.php";

$erreur = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
    
    if(!empty($email) && !empty($mot_de_passe)){
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            
            // ICI LE FIX : c'est motpasse sans _ dans ta BDD
            if(password_verify($mot_de_passe, $user['motpasse'])){
                $_SESSION['id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['no'] = $user['no'];
                
                if($user['role'] == 'admin'){
                    header("Location: dashboard_admin.php");
                } else {
                    header("Location: dashboard_client.php");
                }
                exit;
            } else {
                $erreur = "Mot de passe incorrect";
            }
        } else {
            $erreur = "Email introuvable";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion - E-commerce</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; width: 100%; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            padding: 45px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
        }
        .logo { text-align: center; margin-bottom: 10px; font-size: 48px; }
        h1 { color: #2c3e50; margin-bottom: 8px; text-align: center; font-size: 28px; }
        .subtitle { text-align: center; color: #7f8c8d; margin-bottom: 30px; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #34495e; font-size: 14px; }
        .input-wrapper { position: relative; }
        .input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 18px; opacity: 0.5; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 14px 15px 14px 45px; 
            border: 2px solid #e0e0e0; border-radius: 10px; 
            font-size: 15px; transition: 0.3s; background: #f8f9fa;
        }
        input:focus { 
            outline: none; border-color: #667eea; 
            background: white; box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        .btn-login { 
            width: 100%; padding: 15px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; border-radius: 10px; 
            font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.3s;
            margin-top: 10px;
        }
        .btn-login:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .erreur {
            background: #fee; color: #c0392b; padding: 12px;
            border-radius: 8px; margin-bottom: 20px;
            border-left: 4px solid #e74c3c; font-size: 14px;
        }
        .register-link {
            text-align: center; margin-top: 25px; padding-top: 25px;
            border-top: 1px solid #ecf0f1; color: #7f8c8d; font-size: 14px;
        }
        .register-link a { color: #667eea; text-decoration: none; font-weight: 600; }
        .register-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">🛍️</div>
        <h1>Bienvenue</h1>
        <p class="subtitle">Connecte-toi à ton compte</p>
        
        <?php if(!empty($erreur)): ?>
            <div class="erreur">⚠️ <?php echo $erreur; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Email :</label>
                <div class="input-wrapper">
                    <span class="input-icon">📧</span>
                    <input type="email" name="email" placeholder="ton@email.com" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Mot de passe :</label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" name="mot_de_passe" placeholder="••••••••" required>
                </div>
            </div>
            
            <button type="submit" class="btn-login">Se connecter</button>
        </form>
        
        <div class="register-link">
            Pas encore de compte ? <a href="inscrption.php">S'inscrire</a>
        </div>
    </div>
</body>
</html>