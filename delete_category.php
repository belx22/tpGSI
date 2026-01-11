<?php
include __DIR__ . '/../config/dbcon.php';

// Vérifier si l'ID est fourni
if (!isset($_POST['id'])) {
    header('Location: categories.php');
    exit;
}

$id = $_POST['id'];

// Récupérer tous les produits associés à cette catégorie
$stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie_id=?");
$stmt->execute([$id]);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Supprimer chaque produit (et son image si elle existe)
foreach ($produits as $produit) {
    if ($produit['image'] && file_exists(__DIR__ . '/../' . ltrim($produit['image'], '/'))) {
        unlink(__DIR__ . '/../' . ltrim($produit['image'], '/'));
    }
    $stmtDel = $pdo->prepare("DELETE FROM produits WHERE id=?");
    $stmtDel->execute([$produit['id']]);
}

// Supprimer la catégorie
$stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
$stmt->execute([$id]);

// Redirection vers la liste des catégories
header('Location: categories.php');
exit;
