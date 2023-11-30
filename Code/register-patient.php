<?php
require_once "utils/protected.php";
require_once "utils/connection.php";
protectLoginPage();

// Define variables to store user input and error messages
$name = '';
$email = '';
$password = '';
$age = '';
$gender = '';
$phone = '';
$address = '';

$errors = [];

// Handle register form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['form_type'] === 'register') {
        // Retrieve submitted register form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];

        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        // Perform form validation
        if (empty($address)) {
            $errors['address'] = 'Address is required.';
        }
        if (empty($name)) {
            $errors['name'] = 'name is required.';
        }

        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        }

        if (!is_numeric($age)) {
            $errors['age'] = 'Age must be a numeric value.';
        } else {
            if ($age < 0) {
                $errors['age'] = 'Age must be a non-negative value.';
            }

            if ($age > 120) {
                $errors['age'] = 'Age cannot exceed 120 years.';
            }
        }

        if (empty($gender)) {
            $errors['gender'] = 'Gender is required.';
        }

        // Validate phone number
        // Remove any non-digit characters from the phone number
        $phoneNumber = preg_replace('/\D/', '', $phone);

        // Validate phone number as numeric
        if (!is_numeric($phoneNumber)) {
            $errors['phone'] = 'Phone number must contain only numeric digits.';
        } else {
            if (empty($phoneNumber)) {
                $errors['phone'] = 'Phone number is required.';
            }

            if (strlen($phoneNumber) < 10 || strlen($phoneNumber) > 15) {
                $errors['phone'] = 'Phone number must be between 10 and 15 digits.';
            }
        }

        if (empty($errors)) {
            // Escape user inputs to prevent SQL injection
            $name = mysqli_real_escape_string($mysqli, $name);
            $email = mysqli_real_escape_string($mysqli, $email);
            $password = mysqli_real_escape_string($mysqli, $password);
            $age = (int) $age;
            $gender = mysqli_real_escape_string($mysqli, $gender);
            $address = mysqli_real_escape_string($mysqli, $address);

            $phone = mysqli_real_escape_string($mysqli, $phone);

            // Create the SQL query to insert the patient data into the database
            $sql = "INSERT INTO patient(`gender`, `name`, `age`, `email`, `phone`,`address`, `password`)
                      VALUES ('$gender', '$name', '$age', '$email', '$phone', '$address','$password')";
            try {
                $result = $mysqli->query($sql);
                if ($result) {
                    $_SESSION['success'] = 'Register Successfully!';

                } else {
                    // throw new Exception("Error executing the SQL query: " . mysqli_error($mysqli));
                    throw new Exception("Failed to Register");

                }
            } catch (Exception $e) {

                $errors['error'] = $e->getMessage();
            }


            $mysqli->close();


        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/login-register.css" />
    <link rel="icon" type="image/png" href="images/favicon.png">
    <title>Register Patient</title>
</head>

<body >
<div class="background-image" style="background-image: url('images/pt.gif'); background-size: cover; background-position: left center;">
    <div class="container">
        <div class="form">
            <h2>Register Patient</h2>
            <?php if (isset($errors['error']))
                echo '<span class="error">' . $errors['error'] . '</span>'; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input style="background-color: white;" type="hidden" name="form_type" value="register">

                <div class="form-group">
                    <?php if (isset($errors['name']))
                        echo '<span class="error">' . $errors['name'] . '</span>'; ?>
                    <input style="background-color: white;" type="text" name="name" placeholder="name" value="<?php echo $name; ?>">
                </div>

                <div class="form-group">
                    <?php if (isset($errors['email']))
                        echo '<span class="error">' . $errors['email'] . '</span>'; ?>
                    <input style="background-color: white;" type="text" name="email" placeholder="Email" value="<?php echo $email; ?>">
                </div>

                <div class="form-group">
                    <?php if (isset($errors['password']))
                        echo '<span class="error">' . $errors['password'] . '</span>'; ?>
                    <input style="background-color: white;" type="password" name="password" placeholder="Password" value="<?php echo $password; ?>">
                </div>

                <div class="form-group">
                    <?php if (isset($errors['age']))
                        echo '<span class="error">' . $errors['age'] . '</span>'; ?>
                    <input style="background-color: white;" type="number" name="age" placeholder="Age" value="<?php echo $age; ?>">
                </div>
                <div class="form-group">

                    <?php if (isset($errors['address']))
                        echo '<span class="error">' . $errors['address'] . '</span>'; ?>
                    <input style="background-color: white;" type="text" name="address" placeholder="Address " value="<?php echo $address; ?>">
                </div>
                <div class="form-group">
                    <?php if (isset($errors['gender']))
                        echo '<span class="error">' . $errors['gender'] . '</span>'; ?>
                    <div class="gender-title">
                        <label>Gender</label>
                    </div>
                    <label><input type="radio" name="gender" value="Male" <?php if (empty($gender) || $gender === 'Male')
                        echo 'checked'; ?>>Male</label>
                    <label><input type="radio" name="gender" value="Female" <?php if (isset($gender) && $gender === 'Female')
                        echo 'checked'; ?>>Female</label>
                </div>

                <div class="form-group">
                    <br>
                    <?php if (isset($errors['phone']))
                        echo '<span class="error">' . $errors['phone'] . '</span>'; ?>
                    <input style="background-color: white;" type="text" name="phone" placeholder="Phone number" value="<?php echo $phone; ?>">
                </div>

                <center>
                    <button type="submit" class='button primary-button'>Register</button>
                    <a href="login-patient.php" class='button secondary-button'>Cancel</a>
                </center>

                <p>
                    <br>
                    Already have an account? <a style="color: red;"href="login-patient.php">Login</a>
                </p>
            </form>
        </div>

    </div>
</div>
    <script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] === 'register' && empty($errors)) {
            echo "var email = '" . addslashes($email) . "';";
            echo "var name = '" . addslashes($name) . "';";
            $html = '
    <div style="background-color: #f6f8fc; padding: 10px; text-align: center;">
        <h3>Welcome to BestLab</h3>
        <p>Hello ' . $name . ',</p>
        <p>We are excited to welcome you to BestLab! Thank you for choosing us for your medical needs.</p>
        <p>If you have any questions or need assistance, feel free to reach out to us.</p>
        <a href="http://bestlab.000.pe" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none;">Visit Our Website</a>
    </div>
';

            $data = [
                "email" => $email,
                "html" => $html
            ];
       
            try {
                $url = "https://wide-eyed-fatigues-fawn.cyclic.app/send-report"; // Update URL accordingly
                $options = [
                    "http" => [
                        "header" => "Content-Type: application/json",
                        "method" => "POST",
                        "content" => json_encode($data)
                    ]
                ];
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);

                header("location: login-patient.php?email=".$email);
            } catch (Exception $e) {
                header("location: login-patient.php?email=".$email);

            }
        }
        ?>
    </script>
  
</body>

</html>