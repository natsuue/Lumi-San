<?php
session_start();
require_once 'config.php';
require_once 'vendor/autoload.php'; 

// Google OAuth setup
$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");



/// REGISTER 
if (isset($_POST['Register'])) {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $pass = $_POST['Pass'];
    $confirm = $_POST['Confirm'];

    // Check if email already exists
    $sql_register = "SELECT * FROM ACCOUNTS WHERE USER_EMAIL = ?";
    $stmt = sqlsrv_query($conn, $sql_register, [$email]);
    $row = sqlsrv_fetch_array($stmt);

    if ($row) {
        $_SESSION['register_error'] = 'Email is already registered!';
        header("Location: index.php");

    } elseif ($pass !== $confirm) {
        $_SESSION['register_error'] = 'Password does not match!';
        header("Location: index.php");

    } else {
        $password = password_hash($pass, PASSWORD_DEFAULT);

        $destination = "uploads/";
        $filename = basename($_FILES['idcard']['name']);
        $targetfilepath = $destination . $filename;
        $ext = strtolower(pathinfo($targetfilepath, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg','jpeg'])) {
            if (move_uploaded_file($_FILES['idcard']['tmp_name'], $targetfilepath)) {
                $sql_user = "INSERT INTO ACCOUNTS
                (OAUTH_PROVIDER, USER_NAME, USER_EMAIL, PASSWORD, OAUTH_UID, PICTURE, ROLE, DATE_CREATED)
                VALUES (?, ?, ?, ?, ?, ?,?, GETDATE())";

                $params = ['Local', $name, $email, $password, null,$targetfilepath,'user'];
                $result = sqlsrv_query($conn, $sql_user, $params);


                // Save user info in session
                $_SESSION['Email'] = $email;
                $_SESSION['UserName'] = $name;
                $_SESSION['Picture'] = $targetfilepath;
                header("Location: index.php");
                exit();
            }
        }
    }
}


if (isset($_POST['Login'])) {
    $email = $_POST['Email'];
    $password = $_POST['Pass'];

    $sql_check = "SELECT * FROM ACCOUNTS WHERE USER_EMAIL = ?";
    $stmt = sqlsrv_query($conn, $sql_check, [$email],);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $storedHash = $row['PASSWORD'];


    if (!$row) {
        $_SESSION['login_error'] = "Invalid username or password.";
        header("Location: index.php");
        exit();
    }else{
        if (password_verify($password, $storedHash)) {
            // ✅ Password is correct
            $_SESSION['Email'] = $email;
            $_SESSION['UserName'] = $row['USER_NAME'];
            $_SESSION['UserID'] = $row['ID'];
            $_SESSION['Picture'] = $row['PICTURE'];
            header("Location: index.php");
            exit();
        }else{
            $_SESSION['login_error'] = "Invalid username or password.";
            header("Location: index.php");
        }

    }
}

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $token = $client->getAccessToken();
    $client->setAccessToken($token);

    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();
    $userId = $userInfo->id;
    $userName = $userInfo->name;
    $userEmail = $userInfo->email;
    $userPic = $userInfo->picture;


    // Check if user exists
    $sql_check = "SELECT * FROM ACCOUNTS WHERE OAUTH_UID = ? AND OAUTH_PROVIDER = 'google'";
    $stmt = sqlsrv_query($conn, $sql_check, [$userInfo->id]);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if (!$row) {
        $sql_insert = "INSERT INTO ACCOUNTS
                    (OAUTH_PROVIDER, USER_NAME, USER_EMAIL, PASSWORD, OAUTH_UID, PICTURE, ROLE, DATE_CREATED)
                    VALUES (?, ?, ?, ?, ?, ?, ?, GETDATE())";
        $params_insert = ['google', $userName, $userEmail, null, $userId, $userPic,'user'];
        sqlsrv_query($conn, $sql_insert, $params_insert);

        
    }

    // Save user info in session
    $_SESSION['Email'] = $userEmail;
    $_SESSION['UserName'] = $userName;
    $_SESSION['Picture'] = $userPic;

    header("Location: index.php");
    exit();
}

// --- CASE 3: No login attempt yet → start Google flow ---
if (!isset($_GET['code']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit();
}
?>






