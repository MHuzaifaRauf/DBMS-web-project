<?php
require_once "utils/protected.php";
require_once "utils/connection.php";


protectManagerPage();

//define variables
$name = $email = $hospitalName = $hospitalAddress = $specialization = "";
$errors = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process the submitted data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $hospitalName = $_POST['hospital_name'];
    $hospitalAddress = $_POST['hospital_address'];
    $specialization = $_POST['specialization'];


    // Redirect back to the manage doctors page after adding the doctor
    if (empty($name)) {
        $errors['name'] = 'Name is Required.';
    }

    // Validate hospital name
    if (empty($hospitalName)) {
        $errors['hospital_name'] = 'Hospital name is Required.';
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is Required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    // Validate specialization
    if (empty($specialization)) {
        $errors['specialization'] = 'Specialization is Required.';
    }

    // Validate hospital address
    if (empty($hospitalAddress)) {
        $errors['hospital_address'] = 'Hospital address is Required.';
    }

    // If there are no errors, proceed with further processing
    if (empty($errors)) {
        // Process the submitted data
        // Insert the new doctor into the "doctor" table in the database

        $sql = "INSERT INTO doctor (name, email, hospital_name, hospital_address, specialization)
                VALUES ('$name', '$email', '$hospitalName', '$hospitalAddress', '$specialization')";

        if ($mysqli->query($sql) === TRUE) {
            // Redirect to the dashboard page after adding the doctor
            header("Location: dashboard-doctors.php");
            exit();
        } else {
            echo "Error adding doctor: ";
        }
    }
}

// Close the MySQLi connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <link rel="stylesheet" href="css/style.css" />

    <link rel="stylesheet" href="css/dashboard/doctors.css" />
    <link rel="icon" type="image/png" href="images/favicon.png">

</head>

<body class="dark-theme">
    <?php require_once 'components/Nav.php'; ?>
    <?php require_once 'components/sidebar.php'; ?>

    <div class="content">
        <div class="details">
            <div class='go-back'>
                <a href="dashboard-doctors.php" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <h2 class="heading">Add Doctor</h2>
            <br>

            <form method="POST" action="add_doctor.php">
                <div class="form-group">
                    <label for="name">Name</label>
                    <?php if (isset($errors['name']))
                        echo '<span class="error">' . $errors['name'] . '</span>'; ?>
                    <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <?php if (isset($errors['email']))
                        echo '<span class="error">' . $errors['email'] . '</span>'; ?>
                    <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label for="hospital_name">Hospital Name</label>
                    <?php if (isset($errors['hospital_name']))
                        echo '<span class="error">' . $errors['hospital_name'] . '</span>'; ?>
                    <input type="text" name="hospital_name" id="hospital_name" placeholder="Hospital Name"
                        value="<?php echo $hospitalName; ?>">
                </div>
                <div class="form-group">
                    <label for="hospital_address">Hospital Address</label>
                    <?php if (isset($errors['hospital_address']))
                        echo '<span class="error">' . $errors['hospital_address'] . '</span>'; ?>
                    <input type="text" name="hospital_address" id="hospital_address" placeholder="Hospital Address"
                        value="<?php echo $hospitalAddress; ?>">
                </div>
                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <?php if (isset($errors['specialization']))
                        echo '<span class="error">' . $errors['specialization'] . '</span>'; ?>
                    <input type="text" name="specialization" id="specialization" placeholder="Specialization"
                        value="<?php echo $specialization; ?>">
                </div>
                <button type="submit" class="button primary-button">Add</button>
            </form>

        </div>
    </div>
</body>

</html>