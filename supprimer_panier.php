<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client'){ 
    header('Location: login.php'); 
    exit();
}

if(isset($_GET['id'])){
    $id_article = $_GET['id'];
    $id_user = $_SESSION['id'];

    // DEBUG : Affiche les valeurs pour tester
    // echo "User: $id_user | Article: $id_article"; exit();

    // 1. RÉCUPÉRER LA QUANTITÉ DANS LE PANIER
    $get_qte = $conn->prepare("SELECT quantite FROM panier WHERE id_utilisateur = ? AND id_article = ?");
    if($get_qte === false){
        die("Erreur SQL SELECT : " . $conn->error);
    }
    
    $get_qte->bind_param("ii", $id_user, $id_article);
    $get_qte->execute();
    $res = $get_qte->get_result();
    
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        $qte = $row['quantite'];

        // 2. REMONTER LE STOCK
        $update_stock = $conn->prepare("UPDATE article SET stock = stock + ? WHERE id_article = ?");
        if($update_stock === false){
            die("Erreur SQL UPDATE stock : " . $conn->error);
        }
        $update_stock->bind_param("ii", $qte, $id_article);
        $update_stock->execute();

        // 3. SUPPRIMER DU PANIER
        $delete = $conn->prepare("DELETE FROM panier WHERE id_utilisateur = ? AND id_article = ?");
        if($delete === false){
            die("Erreur SQL DELETE : " . $conn->error);
        }
        $delete->bind_param("ii", $id_user, $id_article);
        $delete->execute();
    } else {
        die("Article introuvable dans le panier pour cet utilisateur");
    }
}

header('Location: panier.php?removed=1');
exit();
?>