<?php
session_start();

$transaction_status = false; // Default: No successful transaction has occurred
$hotel = $_POST['hotel'] ?? 'N/A';
$total_price = $_POST['total_price'] ?? 'N/A';
$start_date = $_POST['start_date'] ?? 'N/A';
$end_date = $_POST['end_date'] ?? 'N/A';
$transactionId = 'N/A';
$userEmail = $_SESSION['Email'] ?? 'guest'; // Keep userEmail for success message

$serverName = "LAPTOP-G9D4RQQU"; 
$connectionOptions = [ 
  "Database" => "FINALSDB", 
  "Uid" => "", 
  "PWD" => "" 
]; 
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    // If connection fails, redirect back immediately
    error_log("Database Connection Failed: " . print_r(sqlsrv_errors(), true));
    header("Location: hotel.php?error=db_connect_failed");
    exit;
}

// --- Transaction Processing ---
if (isset($_POST['Payments']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Data collected from the form submission on hotel.php
    $hotel       = $_POST['hotel'] ?? '';
    $total_price = $_POST['total_price'] ?? '';
    $start_date  = $_POST['start_date'] ?? '';
    $end_date    = $_POST['end_date'] ?? '';
    // Payment specific data (not used in DB insert below, but available if needed)
    $payment_method = $_POST['payment_method'] ?? 'N/A';
    
    // 1. Database Insertion
    $sql = "INSERT INTO Transactions (UserEmail, Hotel, TotalPrice, StartDate, EndDate) 
            VALUES (?, ?, ?, ?, ?)";
    $params = [$userEmail, $hotel, $total_price, $start_date, $end_date];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Transaction failed, set status to show error message
        $transaction_status = 'error';
        error_log("SQL Error: " . print_r(sqlsrv_errors(), true));
    } else {
        // Transaction SUCCESSFUL
        $transaction_status = 'success';
        
        // 2. Get Transaction ID for confirmation
        $idStmt = sqlsrv_query($conn, "SELECT MAX(TransactionID) AS LastTransactionID FROM Transactions");
        $row = sqlsrv_fetch_array($idStmt, SQLSRV_FETCH_ASSOC);
        $transactionId = $row['LastTransactionID'] ?? 'N/A';

    }
    
} else {
    header("Location: hotel.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payment Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: url('images/footer.jpg') no-repeat center center fixed; 
  background-size: cover;
  color: #111;
}

</style>

</head>
<body>
    
    <div class="container py-5">

    <?php if ($transaction_status === 'success'): ?>

        <div class="card status-card text-center shadow-lg border-success">
            <div class="card-header bg-success text-white">
                <i class="fas fa-check-circle fa-2x"></i>
                <h4 class="mt-2 mb-0">Booking Confirmed!</h4>
            </div>
            <div class="card-body p-4">
                <p class="lead">Your payment was processed successfully. Thank you for booking!</p>
                
                <div class="alert alert-info fw-bold">
                    Transaction ID: 
                    <span class="text-success fs-5"><?= htmlspecialchars($transactionId) ?></span>
                </div>

                <h3>Booking Details</h3>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><strong>Hotel:</strong> <?= htmlspecialchars($hotel) ?></li>
                    <li class="list-group-item"><strong>Total Price:</strong> ₱<?= htmlspecialchars($total_price) ?></li>
                    <li class="list-group-item"><strong>Check-in:</strong> <?= htmlspecialchars($start_date) ?></li>
                    <li class="list-group-item"><strong>Check-out:</strong> <?= htmlspecialchars($end_date) ?></li>
                    <li class="list-group-item"><strong>Payment Method:</strong> <?= htmlspecialchars($payment_method) ?></li>
                </ul>

                <p class="text-muted small">A confirmation email has been sent to <?= htmlspecialchars($userEmail) ?>.</p>
                <a href="index.php" class="btn btn-primary mt-3"><i class="fas fa-home me-2"></i> Return to Home</a>
            </div>
        </div>

    <?php elseif ($transaction_status === 'error'): ?>

        <div class="card status-card text-center shadow-lg border-danger">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-times-circle fa-2x"></i>
                <h4 class="mt-2 mb-0">Payment Failed</h4>
            </div>
            <div class="card-body p-4">
                <p class="lead text-danger">There was an issue processing your transaction. Please try again or contact support.</p>
                <a href="hotel.php" class="btn btn-warning mt-3"><i class="fas fa-redo me-2"></i> Try Again</a>
            </div>
        </div>

    <?php endif; ?>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

