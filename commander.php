<?php
session_start();
include "connexion_base.php";
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client'){ header('Location: login.php'); exit(); }
$id_user = $_SESSION['id'];

// ON LIT LE PANIER avec id_utilisateur
$SQL_panier = "SELECT p.*, a.prix, a.stock 
               FROM panier p 
               JOIN article a ON p.id_article = a.id_article 
               WHERE p.id_utilisateur = ?";
$stmt = $conn->prepare($SQL_panier);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result_panier = $stmt->get_result();
if($result_panier->num_rows == 0){ header('Location: panier.php?vide=1'); exit(); }

while($item = $result_panier->fetch_assoc()){
    if($item['quantite'] > $item['stock']){ die("Stock insuffisant"); }
    $prix_total_ligne = $item['prix'] * $item['quantite'];
    
    // ON INSÈRE DANS COMMANDE avec id_client
    $SQL_insert = "INSERT INTO commande (id_client, id_article, quantite, prix_total, statut) 
                   VALUES (?, ?, ?, ?, 'En_attente')";
    $stmt_insert = $conn->prepare($SQL_insert);
    $stmt_insert->bind_param("iiid", $id_user, $item['id_article'], $item['quantite'], $prix_total_ligne);
    $stmt_insert->execute();
    
    $SQL_stock = "UPDATE article SET stock = stock - ? WHERE id_article = ?";
    $stmt_stock = $conn->prepare($SQL_stock);
    $stmt_stock->bind_param("ii", $item['quantite'], $item['id_article']);
    $stmt_stock->execute();
}

// ON VIDE LE PANIER avec id_utilisateur
$SQL_vide = "DELETE FROM panier WHERE id_utilisateur = ?";
$stmt_vide = $conn->prepare($SQL_vide);
$stmt_vide->bind_param("i", $id_user);
$stmt_vide->execute();

header('Location: commande_success.php');
exit();
?>