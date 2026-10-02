<?php
$page = 'contact';
$title = 'Contact & Support - Lanka Recipe Book';
require_once 'includes/functions.php';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $err = 'Security verification failed. Please try again.';
    } else {
        $n = trim($_POST['name'] ?? '');
        $e = trim($_POST['email'] ?? '');
        $m = trim($_POST['message'] ?? '');

        if ($n === '' || $m === '' || !filter_var($e, FILTER_VALIDATE_EMAIL)) {
            $err = 'Please enter your name, a valid email address, and a message of at least 10 characters.';
        } elseif (strlen($m) < 10) {
            $err = 'Message must be at least 10 characters long.';
        } else {
            $st = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?,?,?)");
            $st->bind_param('sss', $n, $e, $m);
            $st->execute();
            $msg = 'Thank you! Your message has been received. Our Ceylon culinary team will get back to you soon.';
        }
    }
}

require 'includes/header.php';
?>

<main class="container py-5">
  <div class="row justify-content-center mb-5 text-center">
    <div class="col-lg-8">
      <span class="badge bg-emerald-subtle text-emerald px-3 py-1 mb-2">We Would Love To Hear From You</span>
      <h1 class="display-6 fw-bold mb-2">Get in Touch with Our Kitchen</h1>
      <p class="text-muted">Have a question about a spice blend, cooking technique, or need help with your account? Drop us a note below.</p>
    </div>
  </div>

  <div class="row g-5">
    <!-- Contact Form Column -->
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
        <h2 class="h4 fw-bold mb-4">
          <i class="bi bi-chat-left-dots text-emerald me-2"></i>Send Us a Message
        </h2>

        <?php if ($msg): ?>
          <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?= clean($msg) ?></div>
          </div>
        <?php endif; ?>

        <?php if ($err): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?= clean($err) ?></div>
          </div>
        <?php endif; ?>

        <form method="post" class="needs-validation" novalidate>
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

          <div class="mb-3">
            <label class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-body-tertiary"><i class="bi bi-person"></i></span>
              <input type="text" name="name" class="form-control" placeholder="Kumara Perera" required value="<?= clean($_POST['name'] ?? '') ?>">
            </div>
            <div class="invalid-feedback">Please provide your name.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Your Email <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-body-tertiary"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control" placeholder="kumara@example.com" required value="<?= clean($_POST['email'] ?? '') ?>">
            </div>
            <div class="invalid-feedback">Please enter a valid email address.</div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
            <textarea name="message" rows="5" class="form-control" placeholder="Share your cooking questions, recipe feedback, or suggestions..." required minlength="10"><?= clean($_POST['message'] ?? '') ?></textarea>
            <div class="invalid-feedback">Please write at least 10 characters.</div>
          </div>

          <button type="submit" class="btn btn-emerald btn-lg w-100 shadow-sm">
            <i class="bi bi-send me-2"></i> Send Message
          </button>
        </form>
      </div>
    </div>

    <!-- Contact Info & FAQ Column -->
    <div class="col-lg-6" id="faqSection">
      <!-- Headquarters Card -->
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, rgba(21, 128, 61, 0.08) 0%, rgba(245, 158, 11, 0.08) 100%);">
        <h3 class="h5 fw-bold mb-3 text-emerald"><i class="bi bi-geo-alt me-2"></i>Ceylon Culinary Hub</h3>
        <p class="text-muted small mb-3">Rooted in Colombo and Kandy, dedicated to documenting Sri Lanka's indigenous gastronomy.</p>

        <div class="d-flex flex-column gap-2 small">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-envelope text-emerald"></i>
            <span>contact@lankarecipes.lk</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-telephone text-emerald"></i>
            <span>+94 (11) 234-5678</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock text-emerald"></i>
            <span>Mon - Fri, 9:00 AM - 5:00 PM (IST)</span>
          </div>
        </div>
      </div>

      <!-- FAQ Accordion -->
      <h3 class="h5 fw-bold mb-3"><i class="bi bi-question-circle text-emerald me-2"></i>Frequently Asked Questions</h3>
      
      <div class="accordion accordion-flush rounded-4 overflow-hidden border shadow-sm" id="recipeFaq">
        
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
              What is the difference between roasted and unroasted curry powder?
            </button>
          </h2>
          <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#recipeFaq">
            <div class="accordion-body text-muted small">
              <strong>Unroasted (Amu Thuna Paha)</strong> is ground raw without toasting and is traditionally used for mild vegetable curries and dhal. <strong>Roasted (Badapu Thuna Paha)</strong> is dark toasted until fragrant and rich brown, making it essential for meat, poultry, and fish dishes.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
              Can I post my own family recipes?
            </button>
          </h2>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#recipeFaq">
            <div class="accordion-body text-muted small">
              Yes! Simply create a free account, go to your Chef Dashboard, and publish your recipe. It will immediately appear in the public recipe book for all users to enjoy.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
              Are these recipes suitable for vegetarians and vegans?
            </button>
          </h2>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#recipeFaq">
            <div class="accordion-body text-muted small">
              Traditional Sri Lankan vegetable and dhal curries rely exclusively on coconut milk and plant spices, making them naturally 100% vegan. For sambols, Maldive fish is entirely optional.
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</main>

<?php require 'includes/footer.php'; ?>
