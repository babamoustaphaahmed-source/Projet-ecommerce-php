<?php
session_start();
if (!isset($_SESSION['id'])){
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Commande Validée</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; text-align: center; padding-top: 100px; }
        .box { background: white; max-width: 500px; margin: auto; padding: 40px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        h1 { color: #28a745; }
        .btn { background: #667eea; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Commande Validée ! ✅</h1>
        <p>Merci <?php echo $_SESSION['prenom']; ?> !</p>
        <p>Ta commande a bien été enregistrée.</p>
        <p>Statut : <strong>En attente</strong></p>
        <a href="dashboard_client.php" class="btn">Retour à l'accueil</a>
    </div>
</body>
</html>
