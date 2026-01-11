<?php
include __DIR__ . '/../config/dbcon.php';
$pageTitle = "Catégories";
include __DIR__ . '/../includes/header.php';

// Récupération des catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="text-brown mb-4">Liste des catégories</h3>

<a href="<?= $root ?>/pages/add_category.php" class="btn btn-info mb-3 text-white">Ajouter une catégorie</a>

<table class="table table-bordered bg-white rounded shadow">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Produits associés</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['nom']) ?></td>
            <td>
                <?php
                // Récupérer les produits associés à cette catégorie
                $stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie_id=? ORDER BY nom");
                $stmt->execute([$c['id']]);
                $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($produits):
                ?>
                    <ul class="mb-0">
                        <?php foreach ($produits as $p): ?>
                            <li>
                                <?= htmlspecialchars($p['nom']) ?> 
                                (<?= $p['prix'] ?> <?= htmlspecialchars($p['monnaie'] ?? '') ?>,
                                Qté: <?= $p['quantite'] ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <span class="text-muted">Aucun produit</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="<?= $root ?>/pages/edit_category.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm text-white">Modifier</a>

                <form action="<?= $root ?>/pages/delete_category.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
