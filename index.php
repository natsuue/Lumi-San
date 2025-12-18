<?php
require_once 'config.php';

// Set flag: true if not logged in
$requireRegistration = !isset($_SESSION['Email']); 
$userEmail = $_SESSION['Email'] ?? null;
$userName  = $_SESSION['UserName'] ?? null;
$userPic   = $_SESSION['Picture'] ?? null; // for Google users

$errors = [
    'Login' => $_SESSION['login_error'] ?? '',
    'Register' => $_SESSION['register_error'] ?? ''
];

function ShError($errors){
    return !empty($errors) ? "<div class='alert alert-danger'>$errors</div>" : '';
}
unset($_SESSION['login_error'], $_SESSION['register_error']);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tralala-Layas! | Tralala-Layas</title>
    <link href="style.css" rel="stylesheet"> 
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
        <script>
      window.isUserLoggedIn = <?= json_encode(!$requireRegistration); ?>;
    </script>
    
  </head>
  <body style="overflow-x: hidden">
    <?php include 'header.php'; ?>

<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
                <form action="login.php" method="post">
          <?=ShError($errors['Login']); ?>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="Email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="Pass" required>
          </div>
          <button type="submit" name="Login" class="btn btn-dark w-100 mb-3">Login</button>
        </form>
                  <div class="text-center mt-3">
          <a href="login.php" name="gmail" class="btn btn-danger w-100 mb-3">Login with Google</a>
        </div>
          <p style="text-align: center;">
          Don't have an account?
          <a href="#" data-bs-toggle="modal" data-bs-target="#RegisModal" data-bs-dismiss="modal">Sign up</a>
        </p>
      </div>
      </div>
  </div>
</div>

<div class="modal fade" id="RegisModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Register</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
      <div class="modal-body">
        <form action="login.php" method="post" enctype="multipart/form-data">
          <?=ShError($errors['Register']); ?>
          <div class="mb-3">
            <label class="form-label">User Name</label>
            <input type="text" class="form-control" name="Name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="Email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="Pass" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="Confirm" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Upload Profile picture</label>
            <input class="form-control" type="file" name="idcard" accept=".jpg, .jpeg">
          </div>
          <button type="submit"  name="Register" class="btn btn-dark w-100 mb-3">Sign up</button>
        </form>
        <div class="text-center mt-3">
          <a href="login.php"  class="btn btn-danger w-100 mb-3">Login with Google</a>
        </div>
        <p style="text-align: center;">
          Already have an account?
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Login</a>
        </p>
              </div>
    </div>
  </div>
</div>

<div class="space" style="width: auto;
  background-color: rgb(255, 255, 255);
  height: 80px;"></div>
<div class="videoSplash">
  <video class="video" id="introVideo" autoplay muted playsinline>
    <source src="intro.mp4" type="video/mp4">
  </video>

    <button class="pause-btn" id="pauseBtn">
    <i class="fas fa-pause"></i>
  </button>

    <div class="video-overlay">
    <h1 class="video-title">Explore the wonder of the Philippines</h1>
    <div class="video-buttons">
      <button class="play-btn" id="playFullBtn"><i class="fas fa-pause"></i> Play Full Video</button>    
    </div>
  </div>
</div>

    <main>
<section class="container py-5">
    <div>
      <div class="text-center mx-auto" style="max-width: 800px;">
       <h1 class="section-title" style="font-size: 2rem !important;">Discover the wonders of the Philippines</h1>
    </div>
</div>

    <div class="magazine-feature-grid mt-4">
        <a href="https://www.tripadvisor.com.ph/Tourism-g294260-Boracay_Malay_Aklan_Province_Panay_Island_Visayas-Vacations.html" target="_blank" class="feature-box box-1">
            <img src="./images/boracay.jpg" alt="White beach in Boracay" class="box-image">
            <div class="box-overlay">
                <h6>Aklan, Philippines</h6>
                <h2>Boracay Island</h2>
            </div>
            </a>

        <a href="https://www.tripadvisor.com.ph/Attraction_Review-g317121-d1673683-Reviews-Peoples_Park_In_The_Sky-Tagaytay_Cavite_Province_Calabarzon_Region_Luzon.html" target="_blank" class="feature-box box-2">
            <img src="./images/tagaytay.avif" alt="Palace in the Sky" class="box-image">
            <div class="box-overlay">
                <h6>Tagaytay, Philippines</h6>
                <h2>Palace in the Sky</h2>
            </div>
          </a>

        <a href="https://www.tripadvisor.com.ph/Attraction_Review-g298573-d548076-Reviews-Intramuros-Manila_Metro_Manila_Luzon.html" target="_blank" class="feature-box box-3">
            <img src="./images/manila.jpg" alt="Intramuros" class="box-image">
            <div class="box-overlay">
                <h6>Manila, Philippines</h6>
                <h2>Intramuros</h2>
            </div>
        </a>

        <a href="https://www.tripadvisor.com.ph/Tourism-g294259-Bohol_Island_Bohol_Province_Visayas-Vacations.html" target="_blank" class="feature-box box-4">
            <img src="./images/bohol.jpg" alt="Chocolate Hills in Bohol" class="box-image">
            <div class="box-overlay">
                <h6>Bohol Province</h6>
                <h2>Chocolate Hills</h2>
            </div>
        </a>
        
        <a href="https://www.tripadvisor.com.ph/Tourism-g424958-Vigan_Ilocos_Sur_Province_Ilocos_Region_Luzon-Vacations.html" target="_blank" class="feature-box box-5">
            <img src="./images/vigan.jpg" alt="Vigan City" class="box-image">
            <div class="box-overlay">
                <h6>Vigan City, Philippines</h6>
            </div>
        </a>
        
    </div>
</section>

<div class="text-center mb-5">
    <h2 class="fw-bold display-5">Your Gateway to the Philippines</h2>
    <p class="lead text-muted">
        Tralala-Layas is your full-service concierge for bespoke journeys, providing seamless booking for all your travel needs.
    </p>
</div>
<div class="container">
  <div class="row g-4 justify-content-center">
    
    <!-- Contact Us -->
    <div class="col-md-6 col-lg-4">
      <div class="service-card text-center p-4 h-100 shadow-sm rounded-4">
        <i class="fas fa-envelope fa-3x text-dark mb-3"></i>
        <h3 class="fw-bold mb-2">Contact Us</h3>
        <p class="text-secondary">
          Have questions or need assistance? Reach out to our team for personalized support and guidance.
        </p>
        <a href="https://www.facebook.com/nathaniel.suarez1" 
           class="btn btn-outline-dark mt-3 guarded-link" 
           target="_blank" rel="noopener noreferrer">
          Get in Touch <i class="fas fa-arrow-right ms-2"></i>
        </a>
      </div>
    </div>

    <!-- Hotels & Stays (unchanged) -->
    <div class="col-md-6 col-lg-4">
      <div class="service-card text-center p-4 h-100 shadow-sm rounded-4">
        <i class="fas fa-bed fa-3x text-dark mb-3"></i>
        <h3 class="fw-bold mb-2">Hotels & Stays</h3>
        <p class="text-secondary">
          From luxury resorts in Palawan to boutique stays in Cebu, discover curated accommodations that fit your style and budget.
        </p>
        <a href="hotel.php" class="btn btn-outline-dark mt-3 guarded-link">
          Find a Hotel <i class="fas fa-arrow-right ms-2"></i>
        </a>
      </div>
    </div>

    <!-- Don’t Like Us -->
    <div class="col-md-6 col-lg-4">
      <div class="service-card text-center p-4 h-100 shadow-sm rounded-4">
        <i class="fas fa-thumbs-down fa-3x text-dark mb-3"></i>
        <h3 class="fw-bold mb-2">Don’t Like Us</h3>
        <p class="text-secondary">
          Not satisfied with our service? Here, feel free to use other service.
        </p>
        <a href="https://www.booking.com" 
           class="btn btn-outline-dark mt-3 guarded-link" 
           target="_blank" rel="noopener noreferrer">
          Problem? <i class="fas fa-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </div>
</div>
</section>


<section class="widgets-section" style="background: var(--white);">
    <div class="container">
        <h2 class="section-title" style="color: var(--dark); margin-bottom: 30px">
            Travel Widgets
        </h2>
        <div class="row g-4 justify-content-center"> 
            
                        <div class="col-md-6 col-lg-4"> 
                <div id="weatherWidgetContainer" class="service-card p-4 h-100 shadow-sm rounded-4">
                    <h3> Weather Forecast</h3>
                    <div id="weatherWidget" class="widget-loading text-center">
                        Loading weather...
                    </div>
                </div>
            </div>

                        <div class="col-md-6 col-lg-4">
                <div id="quoteWidgetContainer" class="service-card p-4 h-100 shadow-sm rounded-4 d-flex flex-column">
                    <h3> Travel Inspiration</h3>
                    <div id="quoteWidget" class="widget-loading flex-grow-1 d-flex flex-column justify-content-center">
                        Loading quote...
                    </div>
                    <div class="mt-auto pt-3 text-end">
                        <button id="newQuoteBtn" class="btn btn-sm btn-dark">New Quote</button>
                    </div>
                </div>
            </div>

                        <div class="col-md-12 col-lg-4">
<div id="holidaysWidgetContainer" class="service-card p-4 h-100 shadow-sm rounded-4">
  <h3>Upcoming Holidays</h3>
  <div id="holidaysWidget" class="widget-loading text-center">
    Loading holidays...
  </div>
</div>
            </div>

        </div>
    </div>
</section>

      <section class="cta-section bg-black">
        <img src="./images/footer.jpg" class="cta-bg" alt="Tropical coastline at sunset"/>
        <div class="cta-overlay">
          <h2 class="cta-title">You are welcome here in the Philippines!</h2>
          <a href="#" class="btn btn-light btn-lg guarded-link" id="bookNowBtn">Book now</a>
        </div>
      </section>
      
    </main>
    <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  <?php if (!empty($errors['Login'])): ?>
    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'), {
      backdrop: 'static',
      keyboard: false
    });
    loginModal.show();
  <?php endif; ?>
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  <?php if (!empty($errors['Register'])): ?>
    var RegisModal = new bootstrap.Modal(document.getElementById('RegisModal'), {
      backdrop: 'static',
      keyboard: false
    });
    RegisModal.show();
  <?php endif; ?>
});
</script>

  <script src="./script.js" defer></script>
  <script src="./api_widgets.js" defer></script>
    
  </body>
</html>

