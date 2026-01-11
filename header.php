<?php
if (!isset($pageTitle)) $pageTitle = "Santa Lucia";
$root = '/Santa Lucia'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $root ?>/uploads/style.css">
</head>
<body class="bg-light">


<nav class="navbar navbar-expand-lg bg-light shadow">
  <div class="container justify-content-center">
    <a class="navbar-brand fw-bold fs-1 text-dark text-center" > Santa Lucia</a>
  </div>
</nav>

<div class="container mt-4">
