<?php
require_once "utils/protected.php";

protectManagerPage();

// Define variables
$name = $age = $gender = $email = $phone = $address = "";
$errors = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process the submitted data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Validate name
    if (empty($name)) {
        $errors['name'] = 'Name is required.';
    }

    // Validate age
    if (empty($age)) {
        $errors['age'] = 'Age is required.';
    }

    // Validate gender
    if (empty($gender)) {
        $errors['gender'] = 'Gender is required.';
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    // Validate phone
    if (empty($phone)) {
        $errors['phone'] = 'Phone is required.';
    }

    // Validate address
    if (empty($address)) {
        $errors['address'] = 'Address is required.';
    }

    // If there are no errors, proceed with further processing
    if (empty($errors)) {
        // Process the submitted data

        // Redirect to the dashboard page
        header("Location: dashboard-patients.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/dashboard/patients.css" />
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>

<body class="dark-theme">
    <?php require_once 'components/Nav.php'; ?>
    <?php require_once 'components/sidebar.php'; ?>

    <div class="content">
        <div class="details">
            <div class='go-back'>
                <a href="dashboard-patients.php" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <h2 class="heading">Patients</h2>
            <br>
            <form method="POST" action="add_patient.php">
                <div class="form-group">
                    <label for="name">Name</label>
                    <?php if (isset($errors['name'])) echo '<span class="error">' . $errors['name'] . '</span>'; ?>
                    <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>">
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <?php if (isset($errors['age'])) echo '<span class="error">' . $errors['age'] . '</span>'; ?>
                    <input type="number" name="age" id="age" placeholder="Age" value="<?php echo $age; ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label><br>
                    <input type="radio" id="genderMale" name="gender" value="male" <?php if (strcasecmp($gender, 'male') === 0) echo 'checked'; ?>>
                    <label for="genderMale">Male</label>
                    <input type="radio" id="genderFemale" name="gender" value="female" <?php if (strcasecmp($gender, 'female') === 0) echo 'checked'; ?>>
                    <label for="genderFemale">Female</label>
                    
                    <?php if (isset($errors['gender'])) echo '<p class="error">' . $errors['gender'] . '</p>'; ?>
                </div>
                <br>
                <div class="form-group">
                    <label for="email">Email</label>
                    <?php if (isset($errors['email'])) echo '<span class="error">' . $errors['email'] . '</span>'; ?>
                    <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <?php if (isset($errors['phone'])) echo '<span class="error">' . $errors['phone'] . '</span>'; ?>
                    <input type="text" name="phone" id="phone" placeholder="Phone" value="<?php echo $phone; ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <?php if (isset($errors['address'])) echo '<span class="error">' . $errors['address'] . '</span>'; ?>
                    <input type="text" name="address" id="address" placeholder="Address" value="<?php echo $address; ?>">
                </div>
                <button type="submit" class="button primary-button">Add</button>
            </form>
        </div>
    </div>
</body>

</html>
