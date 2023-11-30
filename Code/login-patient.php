<?php
require_once "utils/protected.php";
require_once "utils/connection.php";

protectLoginPage();

// Retrieve the email value from the query parameter
$email = $_GET['email'] ?? '';

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve submitted form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Invalid email format.';
        header('Location: login-patient.php?email=' . urlencode($email)); // Redirect back to the login page with email as a query parameter
        exit();
    }

    // Validate password length
    if (strlen($password) < 1) {
        $_SESSION['message'] = 'Please enter password';
        header('Location: login-patient.php?email=' . urlencode($email)); // Redirect back to the login page with email as a query parameter
        exit();
    }

    // Validate the credentials against your user database
    $query = "SELECT * FROM patient WHERE email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows === 1) {
        $patient = $result->fetch_assoc();
        // Verify the password
        if ($password== $patient['password']) {
            // Valid login credentials
            $_SESSION['username'] = $patient['name']; 
            $_SESSION['id'] = $patient['id']; // Store name in the session variable
            // Store name in the session variable
            $_SESSION['email'] = $patient['email']; // Store name in the session variable
            $_SESSION['type'] = 'patient'; // Store username in the session variable
            header('Location: index.php'); // Redirect to the dashboard
            exit();
        } else {
            // Invalid password
            $_SESSION['message'] = 'Invalid password. Please try again.';
            header('Location: login-patient.php?email=' . urlencode($email)); // Redirect back to the login page with email as a query parameter
            exit();
        }
    } else {
        // Patient not found
        $_SESSION['message'] = 'Patient with this email does not exist. Please register first.';
        header('Location: login-patient.php?email=' . urlencode($email)); // Redirect back to the login page with email as a query parameter
        exit();
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/login-register.css" />

    <link rel="icon" type="image/png" href="images/favicon.png">
    <title>Login</title>
</head>

<body >
<div class="background-image" style="background-image: url('images/pt.gif'); background-size: cover; background-position: left center;">
    <div class="container" >
        <div class="form">
            <h2>Login Patient</h2>
            <form action="login-patient.php" method="POST">
                <input style="background-color: white;" type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
                <input style="background-color: white;"type="password" name="password" placeholder="Password">
                <?php if (isset($_SESSION['message'])): ?>
                    <p class="error">
                        <?php echo $_SESSION['message']; ?>
                    </p>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
                <center>
                    <button type="submit" class="button primary-button">Login</button>
                    <a href="index.php" class="button secondary-button">Cancel</a>
                </center>
                <p>
                    <br>
                   Don't Have An Account? <a style="color: red;" href="register-patient.php">Sign-Up</a>
                </p>
            </form>
        </div>
    </div>
</div>
</body>

</html>