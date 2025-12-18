<?php

require_once 'config.php';
if (!isset($_SESSION['Email'])) {
    header("Location: index.php?loginRequired=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Search</title>
    <link href="style.css" rel="stylesheet"> 
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />

<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
</head>
<body>

    <?php include 'header.php'; ?>
    
    <section id="hotel-hero-section" class="position-relative overflow-hidden bg-black">
        <img src="./images/footer.jpg" class="hero-bg-img" alt="Tropical coastline at sunset"/>
        <div class="hero-overlay-content container d-flex flex-column align-items-center justify-content-center">
            <div class="text-center text-white mb-4">
                <p class="text-uppercase mb-1 opacity-75">
                    Start your journey
                </p>
                <h1 class="hero-title">Find Your Perfect Stay</h1>
            </div>
            <form id="hotelSearchForm" class="p-4 rounded-4 shadow-lg bg-white hotel-hero-form">
    <div class="row g-3 align-items-end">

        <div class="col-12 col-md-5 col-lg-4">
            <label for="hotelSearchInput" class="form-label visually-hidden-sm">Location</label>
            <input
                id="hotelSearchInput"
                type="text"
                class="form-control"
                placeholder="Location (e.g., Cebu, Manila)"
                name="location"
                value="manila"
                required
            />
        </div>

        <div class="col-12 col-md-4 col-lg-4">
            <label for="dateRangeInput" class="form-label visually-hidden-sm">Dates</label>
            <input
                id="dateRangeInput"
                type="text"
                class="form-control"
                placeholder="Select check-in and check-out"
                name="dateRange"
                required
            />
        </div>

<div class="col-12 col-md-3 col-lg-4">
            <label class="form-label visually-hidden-sm">Rooms / Guests</label>
            <div class="dropdown w-100" data-bs-auto-close="outside">
                <button class="btn btn-outline-dark w-100 text-start dropdown-toggle" type="button" id="guestDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                    1 Room, 2 Guests
                </button>
                
                <div class="dropdown-menu p-3 shadow" style="min-width: 250px;">
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="mb-0">Rooms</label>
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="adjustCount('roomsInput', -1)">−</button>
                            <input type="number" id="roomsInput" name="rooms" class="form-control text-center" value="1" min="1" readonly />
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="adjustCount('roomsInput', 1)">+</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="mb-0">Adults</label>
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="adjustCount('adultsInput', -1)">−</button>
                            <input type="number" id="adultsInput" name="adults" class="form-control text-center" value="2" min="1" readonly />
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="adjustCount('adultsInput', 1)">+</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="mb-0">Children</label>
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="adjustCount('childrenInput', -1)">−</button>
                            <input type="number" id="childrenInput" name="children" class="form-control text-center" value="0" min="0" readonly />
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="adjustCount('childrenInput', 1)">+</button>
                        </div>
                    </div>

                    <button class="btn btn-dark w-100 btn-sm" type="button" onclick="updateGuestSummary()">Apply</button>
                </div>
            </div>
        </div>

        <div class="col-12">
            <button class="btn btn-primary btn-lg w-100" type="submit" style="background-color: #007bff; border-color: #007bff;">
                <i class="fas fa-search me-2"></i> Search Hotels
            </button>
        </div>
    </div>
</form>
        </div>
    </section>
    
    <section class="container py-5">
        <div id="hotelResultsStatus" class="mt-4 text-muted text-center">
          Fetching Hotels...
        </div>
        <div id="hotelResultsGrid" class="hotel-results-grid mt-4 flex-wrap"></div>
    </section>

<?php include 'footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title fw-bold mb-0" id="bookingModalLabel">Confirm Your Booking</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="bookingForm" class="row g-2 reservation-summary" action="payment.php" method="POST">
          <img id="bookingHotelImage" src="" alt="Hotel preview" class="w-100 rounded-top-4" style="max-height: 200px; object-fit: cover;">

          <div class="col-12 px-3 pt-2">
            <h4 id="confirmHotelName">Hotel Name Placeholder</h4>

            <h6>Your Reservation Summary</h6>
            <div class="row g-2">
              <div class="col-6 fw-semibold">Check-in/out:</div>
              <div class="col-6 text-end" id="confirmCheckInOutDates">Dec 10, 2025 - Dec 13, 2025</div>

              <div class="col-6 fw-semibold">Total Nights:</div>
              <div class="col-6 text-end" id="confirmNights">3</div>

              <div class="col-6 fw-semibold">Rooms:</div>
              <div class="col-6 text-end" id="confirmRooms">1</div>

              <div class="col-6 fw-semibold">Guests:</div>
              <div class="col-6 text-end" id="confirmGuests">2 total (Adults: 2, Children: 0)</div>
            </div>
          </div>


          <div class="col-12 px-3 pt-3">
            <h6>Select Payment Method</h6>
            <div class="p-3 border rounded-3 bg-light">
              <div class="mb-2 d-flex flex-wrap gap-3" id="paymentMethodRadios">
>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payPalRadio" value="paypal" required>
                                <label class="form-check-label" for="payPalRadio">PayPal</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="gcashRadio" value="gcash">
                                <label class="form-check-label" for="gcashRadio">GCash</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cardRadio" value="card">
                                <label class="form-check-label" for="cardRadio">Credit/Debit Card</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="bankRadio" value="bank">
                                <label class="form-check-label" for="bankRadio">Bank Transfer</label>
                            </div>
                        </div>

                        <div id="extraFieldsContainer">
                            <div id="mobileField" class="d-none mt-2">
                                <label class="form-label small fw-semibold">Mobile Number:</label>
                                <input type="text" name="mobile_number" class="form-control form-control-sm" placeholder="Enter your mobile number">
                            </div>

                            <div id="cardFields" class="d-none mt-2">
                                <label class="form-label small fw-semibold">Card Number:</label>
                                <input type="text" name="card_number" class="form-control form-control-sm mb-2" placeholder="Enter card number">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold">Expiry Date:</label>
                                        <input type="month" name="expiry_date" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold">CVV:</label>
                                        <input type="text" name="cvv" class="form-control form-control-sm" placeholder="CVV">
                                    </div>
                                </div>
                            </div>

                            <div id="bankFields" class="d-none mt-2">
                                <label class="form-label small fw-semibold">Bank Account Number:</label>
                                <input type="text" name="bank_account" class="form-control form-control-sm mb-2" placeholder="Enter bank account number">
                                <label class="form-label small fw-semibold">Reference Code:</label>
                                <input type="text" name="bank_reference" class="form-control form-control-sm" placeholder="Enter reference code">
                            </div>
                        </div>
                    </div>
                </div>
                                       <div class="col-12 px-3 pt-3 price-section">
            <h6>Price Computation</h6>
            <div id="priceBreakdownContainer" class="mb-2 small fs-5"></div>
            <div class="text-end border-top pt-2">
              <p class="fs-5 fw-bolder mb-0">
                Total Price: <span id="confirmTotalPrice" class="text-success">N/A</span>
              </p>
            </div>
          </div>

          <!-- Hidden Inputs -->
          <input type="hidden" id="bookingHotel" name="hotel">
          <input type="hidden" id="bookingPriceValue" name="total_price">
          <input type="hidden" id="bookingStartDate" name="start_date">
          <input type="hidden" id="bookingEndDate" name="end_date">

          <div class="col-12 px-3 pt-3">
            <button type="submit" class="btn btn-success w-100" name="Payments">
              <i class="fas fa-credit-card me-2"></i> Confirm and Proceed to Payment
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


    <script src="hotel.js" defer></script>
<script>
  // guest dropdown logic
  function adjustCount(id, delta) {
    const input = document.getElementById(id);
    let value = parseInt(input.value) || 0;
    value += delta;
    if (value < parseInt(input.min)) value = parseInt(input.min);
    input.value = value;
  }

  function updateGuestSummary() {
    const rooms = parseInt(document.getElementById("roomsInput").value);
    const adults = parseInt(document.getElementById("adultsInput").value);
    const children = parseInt(document.getElementById("childrenInput").value);
    const totalGuests = adults + children;

    const summary = `${rooms} Room${rooms > 1 ? 's' : ''}, ${totalGuests} Guest${totalGuests > 1 ? 's' : ''}`;
    document.getElementById("guestDropdownBtn").innerText = summary;
  }

  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.dropdown-menu button, .dropdown-menu input').forEach(el => {
      el.addEventListener('click', e => e.stopPropagation());
    });
  });

document.addEventListener("DOMContentLoaded", function() {
  const bookingStartDateInput = document.getElementById('bookingStartDate');
  const bookingEndDateInput   = document.getElementById('bookingEndDate');

  const picker = new Litepicker({
  element: document.getElementById('dateRangeInput'),
  singleMode: false,
  numberOfMonths: 2,
  numberOfColumns: 2,
  format: 'MMM DD, YYYY',
  tooltipText: {
    one: 'night',
    other: 'nights'
  },
  setup: (picker) => {
    picker.on('selected', (date1, date2) => {
      document.getElementById('bookingStartDate').value = date1.format('YYYY-MM-DD');
      document.getElementById('bookingEndDate').value = date2.format('YYYY-MM-DD');
    });
  }
});

});

</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const paymentRadios = document.querySelectorAll("input[name='payment_method']");
    const mobileField = document.getElementById("mobileField");
    const cardFields = document.getElementById("cardFields");
    const bankFields = document.getElementById("bankFields");
      
    paymentRadios.forEach(radio => {
      radio.addEventListener("change", function() {
        mobileField.classList.add("d-none");
        cardFields.classList.add("d-none");
        bankFields.classList.add("d-none");

       
        if (this.value === "paypal" || this.value === "gcash") {
          mobileField.classList.remove("d-none");
        } else if (this.value === "card") {
          cardFields.classList.remove("d-none");
        } else if (this.value === "bank") {
          bankFields.classList.remove("d-none");
        }
      });
    });
});
    </script>

</body>

</html>

