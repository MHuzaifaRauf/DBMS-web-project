<?php
require_once "utils/protected.php";

require_once "utils/connection.php";
protectManagerPage();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate name
    $name = $_POST['name'];
    if (empty($name)) {
        $errors['name'] = 'Name is Required.';
    }

    // Validate age
    $age = intval($_POST['age']);
    // Validate age as numeric
    if (!is_numeric($age)) {
        $errors['age'] = 'Age must be a numeric value.';
    }

    // Other common validations
    if (empty($age)) {
        $errors['age'] = 'Age is required.';
    }

    if ($age < 0) {
        $errors['age'] = 'Age must be a non-negative value.';
    }

    if ($age > 120) {
        $errors['age'] = 'Age cannot exceed 120 years.';
    }
    // Validate gender
    $gender = $_POST['gender'];
    if (empty($gender)) {
        $errors['gender'] = 'Please select a gender';
    }

    // Validate email
    $email = $_POST['email'];
    if (empty($email)) {
        $errors['email'] = 'Email is Required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    // Validate phone
    $phone = $_POST['phone'];
    // Remove any non-digit characters from the phone number
    $phoneNumber = preg_replace('/\D/', '', $phone);

    // Validate phone number as numeric
    if (!is_numeric($phoneNumber)) {
        $errors['phone'] = 'Phone number must contain only numeric digits.';
    }

    // Other common validations
    if (empty($phoneNumber)) {
        $errors['phone'] = 'Phone number is required.';
    }

    if (strlen($phoneNumber) < 10 || strlen($phoneNumber) > 15) {
        $errors['phone'] = 'Phone number must be between 10 and 15 digits.';
    }
    // Validate address
    $address = $_POST['address'];
    if (empty($address)) {
        $errors['address'] = 'Address is Required.';
    }

    // If there are no errors, proceed with further processing
    if (empty($errors)) {
        // Process the submitted data
        $sql = "UPDATE patient SET name = ?, email = ?, gender = ?, age = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        
        // Assuming $_POST['patient_id'] contains the patient ID
        $patientId = $_POST['id'];

        $stmt->bind_param("sssisss", $name, $email, $gender, $age, $phoneNumber, $address, $patientId);
        $stmt->execute();
        $stmt->close();

        // Redirect to the dashboard page
        header("Location: dashboard-patients.php");
        exit();
    }
}
if (isset($_GET['id']) && ($_SERVER['REQUEST_METHOD'] === 'GET')) {
    $patientId = $_GET['id'];


    // Fetch the doctor with the matching ID from the database
    $sql = "SELECT * FROM patient WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();

     // Check if the doctor exists
     if ($result->num_rows === 1) {
        $matchedPatient = $result->fetch_assoc();

        $name = $matchedPatient['name'];
        $age = $matchedPatient['age'];
        $gender = $matchedPatient['gender'];
        $email = $matchedPatient['email'];
        $phone = $matchedPatient['phone'];
        $address = $matchedPatient['address'];
    }
}
// Validate and process the submitted data



// Check if an ID parameter is provided in the URL

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/dashboard/common.css" />
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
            <h2 class="heading">Edit Patient</h2>
            <br>

            <div class="form-container">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $patientId ?? ''; ?>">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <?php if (isset($errors['name']))
                            echo '<span class="error">' . $errors['name'] . '</span>'; ?>
                        <input type="text" id="name" name="name" value="<?php echo $name ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <?php if (isset($errors['age']))
                            echo '<span class="error">' . $errors['age'] . '</span>'; ?>
                        <input type="number" id="age" name="age" value="<?php echo $age ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Gender</label><br>
                        <?php if (isset($errors['gender']))
                            echo '<span class="error">' . $errors['gender'] . '</span>'; ?>
                        <input type="radio" id="genderMale" name="gender" value="male" <?php if (strcasecmp($gender, 'male') === 0)
                            echo 'checked'; ?>>
                        <label for="genderMale">Male</label><br>
                        <input type="radio" id="genderFemale" name="gender" value="female" <?php if (strcasecmp($gender, 'female') === 0)
                            echo 'checked'; ?>>
                        <label for="genderFemale">Female</label><br>

                    </div>

                    <br>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <?php if (isset($errors['email']))
                            echo '<span class="error">' . $errors['email'] . '</span>'; ?>
                        <input type="text" id="email" name="email" value="<?php echo $email ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <?php if (isset($errors['phone']))
                            echo '<span class="error">' . $errors['phone'] . '</span>'; ?>
                        <input type="text" id="phone" name="phone" value="<?php echo $phone ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <?php if (isset($errors['address']))
                            echo '<span class="error">' . $errors['address'] . '</span>'; ?>
                        <input type="text" id="address" name="address" value="<?php echo $address ?? ''; ?>">

                    </div>

                    <button type="submit" class="button primary-button">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>