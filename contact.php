<?php
$page = 'contact'; $title = 'Contact - Lanka Recipe Book';
require_once 'includes/functions.php';
$msg = ''; $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = trim($_POST['name'] ?? ''); $e = trim($_POST['email'] ?? ''); $m = trim($_POST['message'] ?? '');
    if ($n === '' || $m === '' || !filter_var($e, FILTER_VALIDATE_EMAIL)) { $err = 'Enter your name, a valid email and a message.'; }
    else {
        $st = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?,?,?)");
        $st->bind_param('sss', $n, $e, $m); $st->execute();
        $msg = 'Thanks! Your message has been sent.';
    }
}
require 'includes/header.php';
?>
<main class="container py-5" style="max-width:640px">
  <h1 class="mb-4">Contact us</h1>
  <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
  <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
  <form method="post" class="needs-validation" novalidate>
    <div class="mb-3"><label class="form-label">Name</label><input name="name" class="form-control" required><div class="invalid-feedback">Enter your name.</div></div>
    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required><div class="invalid-feedback">Enter a valid email address.</div></div>
    <div class="mb-3"><label class="form-label">Message</label><textarea name="message" rows="5" class="form-control" required minlength="10"></textarea><div class="invalid-feedback">Write at least 10 characters.</div></div>
    <button class="btn btn-success">Send message</button>
  </form>
</main>
<?php require 'includes/footer.php'; ?>
