<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client'){ 
    header('Location: login.php'); 
    exit();
}

if(isset($_GET['id'])){
    $id_article = $_GET['id']; // C'est bien id_article dans ta table
    $id_user = $_SESSION['id'];

    // 1. VÉRIFIER LE STOCK - TABLE "article" ET COLONNE "id_article"
    $check_stock = $conn->prepare("SELECT stock FROM article WHERE id_article = ?");
    if($check_stock === false){
        die("Erreur SQL : " . $conn->error);
    }
    
    $check_stock->bind_param("i", $id_article);
    $check_stock->execute();
    $result_stock = $check_stock->get_result();
    $article = $result_stock->fetch_assoc();

    if(!$article || $article['stock'] <= 0){
        header('Location: dashboard_client.php?error=stock_insuffisant');
        exit();
    }

    // 2. VÉRIFIER SI ARTICLE DÉJÀ DANS LE PANIER
    $sql_check = "SELECT * FROM panier WHERE id_utilisateur = ? AND id_article = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $id_user, $id_article);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if($result->num_rows > 0){
        // ARTICLE DÉJÀ DANS PANIER
        $sql = "UPDATE panier SET quantite = quantite + 1 WHERE id_utilisateur = ? AND id_article = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_user, $id_article);
        $stmt->execute();
    } else {
        // NOUVEL ARTICLE
        $sql = "INSERT INTO panier (id_utilisateur, id_article, quantite) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_user, $id_article);
        $stmt->execute();
    }

    // 3. DIMINUER LE STOCK - TABLE "article" ET "id_article"
    $update_stock = $conn->prepare("UPDATE article SET stock = stock - 1 WHERE id_article = ?");
    $update_stock->bind_param("i", $id_article);
    $update_stock->execute();
}

header('Location: dashboard_client.php?added=1');
exit();
?>