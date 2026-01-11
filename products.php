<?php
include __DIR__ . '/../config/dbcon.php';
$pageTitle = "Produits";
include __DIR__ . '/../includes/header.php';

// Récupération des produits avec leur catégorie
$produits = $pdo->query("
    SELECT p.*, c.nom AS categorie_nom 
    FROM produits p 
    LEFT JOIN categories c ON p.categorie_id = c.id
    ORDER BY p.nom
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="text-brown mb-4">Liste des produits</h3>
<table class="table table-striped table-bordered bg-white shadow rounded">
    <thead class="table-dark">
        <tr>
            <th>Image</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Catégorie</th>
            <th>Enregistré le</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $produit): ?>
        <tr>
            <td>
                <?php if (!empty($produit['image'])): ?>
                    <img src="<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" class="img-fluid" style="max-width:100px;">
                <?php else: ?>
                    <span>Aucune image</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($produit['nom']) ?></td>
            <td><?= htmlspecialchars($produit['description']) ?></td>
            <td>
                <?php
                // Affichage du prix sans les 00 inutiles
                $prixAffiche = (string)$produit['prix'];
                $prixAffiche = rtrim(rtrim($prixAffiche, '0'), '.');
                echo $prixAffiche . ' ' . htmlspecialchars($produit['monnaie'] ?? '€');
                ?>
            </td>
            <td><?= htmlspecialchars($produit['quantite']) ?></td>
            <td><?= htmlspecialchars($produit['categorie_nom']) ?></td>
            <td>
                <?= !empty($produit['created_at']) ? date('d/m/Y H:i', strtotime($produit['created_at'])) : '-' ?>
            </td>
            <td>
                <a href="edit_product.php?id=<?= $produit['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                <form action="delete_product.php" method="POST" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?');">
                    <input type="hidden" name="id" value="<?= $produit['id'] ?>">
                    <button class="btn btn-sm btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
