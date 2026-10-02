<?php
require_once __DIR__ . '/functions.php';
$base = $base ?? '';
$page = $page ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $title ?? 'Lanka Recipe Book' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Bitter:wght@500;700&family=Nunito+Sans:wght@400;600&display=swap" rel="stylesheet">
<link href="<?= $base ?>css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark site-nav sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?= $base ?>index.php">Lanka Recipe Book</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link <?= $page=='home'?'active':'' ?>" href="<?= $base ?>index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= $page=='recipes'?'active':'' ?>" href="<?= $base ?>recipes.php">Recipes</a></li>
        <li class="nav-item"><a class="nav-link <?= $page=='contact'?'active':'' ?>" href="<?= $base ?>contact.php">Contact</a></li>
        <?php if (is_logged_in()): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>auth/logout.php">Log out (<?= clean($_SESSION['username']) ?>)</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>auth/login.php">Log in</a></li>
          <li class="nav-item"><a class="btn btn-saffron ms-lg-2" href="<?= $base ?>auth/register.php">Sign up</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
