<?php
require_once "utils/protected.php";
require_once "utils/connection.php";

protectLoginPage();

$email = $_GET['email'] ?? '';

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve submitted form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Invalid email format.';
        header('Location: login-manager.php?email=' . urlencode($email));
        exit();
    }

    // Validate password length
    if (strlen($password) < 1) {
        $_SESSION['message'] = 'Please enter a password';
        header('Location: login-manager.php?email=' . urlencode($email));
        exit();
    }

    // Validate the credentials against your user database
    $query = "SELECT * FROM manager WHERE email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows === 1) {
        $manager = $result->fetch_assoc();
        // Verify the password
        if ($password == $manager['password']) {
            // Valid login credentials
            $_SESSION['username'] = $manager['name'];
            $_SESSION['email'] = $manager['email'];
            $_SESSION['id'] = $manager['id'];

            $_SESSION['type'] = 'manager';
            header('Location: index.php');
            exit();
        } else {
            // Invalid password
            $_SESSION['message'] = 'Invalid password. Please try again.';
            header('Location: login-manager.php?email=' . urlencode($email));
            exit();
        }
    } else {
        // Manager not found
        $_SESSION['message'] = 'This Email is not registered';
        header('Location: login-manager.php?email=' . urlencode($email));
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
    <title>Login Manager</title>
</head>
<body>
    <div class="background-image" style="background-image: url('images/admin.jpg'); background-size: cover; background-position: left center;">
        <div class="container">
            <div class="form">
                <h2>Login Manager</h2>
                <form action="login-manager.php" method="POST">
                    <input style="background-color: white;" type="email" value="<?php echo $email ?>" name='email' placeholder="Email..." required>
                    <input style="background-color: white;" type="password" name='password' placeholder="Password..." required>
                    <?php if (isset($_SESSION['message'])): ?>
                        <p class='error'>
                            <?php echo $_SESSION['message']; ?>
                        </p>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>
                    <center>
                        <button type="submit" class='button primary-button'>Login</button>
                        <a href="index.php" class='button secondary-button'>
                            Cancel
                        </a>
                    </center>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
