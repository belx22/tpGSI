<?php
include __DIR__ . '/config/dbcon.php';
$pageTitle = "Santa Lucia";
include __DIR__ . '/includes/header.php';
?>


<div class="d-flex flex-column align-items-center justify-content-center text-center" 
     style="min-height: 80vh; 
            background-image: url('https://images.unsplash.com/photo-1606788075765-86f084adbe80?auto=format&fit=crop&w=1920&q=80'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat;">
    
    <div class="p-5 rounded shadow" style="background-color: rgba(255,255,255,0.8);">
        <h1 class="text-brown mb-4">Bienvenue à Santa Lucia </h1>
        <p class="mb-4">Découvrez nos délicieux produits classés par catégorie.</p>

       <div class="d-flex flex-wrap justify-content-center gap-3">
    <a class="btn btn-success btn-lg" href="pages/products.php">Produits</a>
    <a class="btn btn-warning btn-lg text-white" href="pages/categories.php">Catégories</a>
    <a class="btn btn-info btn-lg text-white" href="pages/add_product.php">Ajouter un produit</a>
</div>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
