<?php
$base = '../';
$title = 'Create an Account - Lanka Recipe Book';
require_once '../includes/functions.php';

if (is_logged_in()) {
    header('Location: ../dashboard.php');
    exit;
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $err = 'Security verification failed. Please refresh the page.';
    } else {
        $u = trim($_POST['username'] ?? '');
        $e = trim($_POST['email'] ?? '');
        $p = $_POST['password'] ?? '';
        $p2 = $_POST['confirm_password'] ?? '';

        if (strlen($u) < 3) {
            $err = 'Username must contain at least 3 characters.';
        } elseif (!filter_var($e, FILTER_VALIDATE_EMAIL)) {
            $err = 'Please provide a valid email address.';
        } elseif (strlen($p) < 6) {
            $err = 'Password must be at least 6 characters long.';
        } elseif ($p !== $p2) {
            $err = 'Passwords do not match.';
        } else {
            $st = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
            $st->bind_param('ss', $u, $e);
            $st->execute();
            $st->store_result();

            if ($st->num_rows > 0) {
                $err = 'That username or email is already registered. Please log in or use another.';
            } else {
                $hash = password_hash($p, PASSWORD_DEFAULT);
                $ins = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
                $ins->bind_param('sss', $u, $e, $hash);
                $ins->execute();
                header('Location: login.php?registered=1');
                exit;
            }
        }
    }
}

require '../includes/header.php';
?>

<main class="container py-5" style="max-width: 520px;">
  <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
    <div class="text-center mb-4">
      <div class="brand-logo-badge mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.8rem;">
        <i class="bi bi-person-plus-fill"></i>
      </div>
      <h1 class="h3 fw-bold mb-1">Create Chef Account</h1>
      <p class="text-muted small">Join our vibrant Sri Lankan cooking community and publish your own family recipes.</p>
    </div>

    <?php if ($err): ?>
      <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div class="small"><?= clean($err) ?></div>
      </div>
    <?php endif; ?>

    <form method="post" class="needs-validation" novalidate>
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

      <div class="mb-3">
        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text bg-body-tertiary"><i class="bi bi-person"></i></span>
          <input type="text" name="username" class="form-control" placeholder="Choose a username" required minlength="3" maxlength="50" value="<?= clean($_POST['username'] ?? '') ?>">
        </div>
        <div class="invalid-feedback">At least 3 characters.</div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text bg-body-tertiary"><i class="bi bi-envelope"></i></span>
          <input type="email" name="email" class="form-control" placeholder="yourname@domain.com" required value="<?= clean($_POST['email'] ?? '') ?>">
        </div>
        <div class="invalid-feedback">Enter a valid email address.</div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text bg-body-tertiary"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" id="pw" class="form-control" placeholder="Minimum 6 characters" required minlength="6">
          <button type="button" class="btn btn-outline-secondary btn-toggle-password" data-target="#pw" title="Show/Hide Password" aria-label="Toggle password visibility">
            <i class="bi bi-eye"></i>
          </button>
        </div>
        <div class="invalid-feedback">At least 6 characters.</div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text bg-body-tertiary"><i class="bi bi-shield-check"></i></span>
          <input type="password" name="confirm_password" id="pw2" class="form-control" placeholder="Re-enter your password" required minlength="6">
        </div>
        <div class="invalid-feedback">Passwords must match.</div>
      </div>

      <button type="submit" class="btn btn-emerald btn-lg w-100 shadow-sm mb-3">
        <span>Create My Free Account</span>
        <i class="bi bi-check-lg ms-1"></i>
      </button>

      <div class="text-center">
        <span class="text-muted small">Already a member?</span>
        <a href="login.php" class="small fw-bold text-emerald ms-1">Sign in here</a>
      </div>
    </form>
  </div>
</main>

<?php require '../includes/footer.php'; ?>
