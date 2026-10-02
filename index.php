<?php
$page = 'home'; $title = 'Lanka Recipe Book';
require 'includes/header.php';
$featured = $conn->query("SELECT * FROM recipes ORDER BY created_at DESC LIMIT 3");
?>
<!-- Image slider (Bootstrap carousel, auto + manual controls) -->
<div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active slide s1"><div class="container"><h1>Rice, coconut and a little fire.</h1><p>Home recipes from every corner of Sri Lanka.</p><a class="btn btn-saffron btn-lg" href="recipes.php">Browse recipes</a></div></div>
    <div class="carousel-item slide s2"><div class="container"><h1>Sunday kiribath, any day.</h1><p>Learn the classics, step by step.</p><a class="btn btn-saffron btn-lg" href="#featured">See what's new</a></div></div>
    <div class="carousel-item slide s3"><div class="container"><h1>Got a family recipe?</h1><p>Create a free account and add yours.</p><a class="btn btn-saffron btn-lg" href="auth/register.php">Join now</a></div></div>
  </div>
  <button class="carousel-control-prev" data-bs-target="#heroSlider" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
  <button class="carousel-control-next" data-bs-target="#heroSlider" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
</div>

<section id="featured" class="container py-5 fade-in">
  <h2 class="mb-4">Newest recipes</h2>
  <div class="row g-4"><?php while ($r = $featured->fetch_assoc()) recipe_card($r); ?></div>
</section>

<section id="about" class="container py-4 fade-in">
  <h2>Why this book?</h2>
  <p class="lead col-lg-8 px-0">Every recipe here is written in plain steps, with ingredients you can find in a Sri Lankan kitchen. Search by name, filter by type, and tap a recipe to read it in full.</p>
</section>
<?php require 'includes/footer.php'; ?>
