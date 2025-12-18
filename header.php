  <header class="fixed-top custom-header">
  <nav class="navbar navbar-expand-lg justify-content-center">
    <div class="container-fluid justify-content-between align-items-center">

      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center" href="index.php">
  <span class="ms-2 text-dark fw-bold">Lumi ~ San</span>
</a>

      <!-- Toggle for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Centered Nav Links -->
<div class="collapse navbar-collapse justify-content-center" id="navbarNav">
  <ul class="navbar-nav gap-3">
    <!-- Opens in a new tab -->
    <li class="nav-item">
      <a class="nav-link nav-circle" 
         href="https://www.facebook.com/nathaniel.suarez1" 
         target="_blank" 
         rel="noopener noreferrer">
         Contact us
      </a>
    </li>

    <!-- Internal link (same tab) -->
    <li class="nav-item">
      <a class="nav-link nav-circle" href="hotel.php">Hotels</a>
    </li>
    <!-- Ride link opens in a new tab -->
    <li class="nav-item">
      <a class="nav-link nav-circle" 
         href="https://www.booking.com" 
         target="_blank" 
         rel="noopener noreferrer">
         Don't Like Us?
      </a>
    </li>
  </ul>
</div>


      <!-- Right-side Login/Logout -->
      <div class="d-flex align-items-center">
        <?php if ($requireRegistration): ?>
          <button class="btn login-circle" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="fas fa-user"></i>
          </button>
        <?php else: ?>
          <a href="logout.php" class="btn login-circle me-2">
            <i class="fas fa-sign-out-alt"></i>
          </a>
          <?php if ($userPic): ?>
  <img src="<?php echo htmlspecialchars($userPic); ?>" 
       alt="Profile" 
       class="rounded-circle" 
       width="40" height="40"
       role="button"
       data-bs-toggle="offcanvas" 
       data-bs-target="#tripDrawer" 
       aria-controls="tripDrawer">
<?php endif; ?>

        <?php endif; ?>
      </div>
    </div>
  </nav>
</header>