<?php
require_once "utils/protected.php";
require_once "utils/connection.php";

protectManagerPage();

$errors = [];

$doctorId = $_GET['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorId = $_POST['id'];

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
        // Update the doctor's data in the database
        // header("Location:". $doctorId." xx-doctors.php");
        // exit();
        $sql = "UPDATE doctor SET name = ?, email = ?, hospital_name = ?, hospital_address = ?, specialization = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssi", $name, $email, $hospitalName, $hospitalAddress, $specialization, $doctorId);
        $stmt->execute();
        $stmt->close();

        // Redirect back to the manage doctors page after updating the doctor
        header("Location: dashboard-doctors.php");
        exit();
    }
}
// Check if the URL contains the doctor ID
if (isset($_GET['id'])) {
    $doctorId = $_GET['id'];

    // Fetch the doctor with the matching ID from the database
    $sql = "SELECT * FROM doctor WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $doctorId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the doctor exists
    if ($result->num_rows === 1) {
        $matchedDoctor = $result->fetch_assoc();

        // Prepopulate the form fields with the doctor's data
        $name = $matchedDoctor['name'];
        $email = $matchedDoctor['email'];
        $hospitalName = $matchedDoctor['hospital_name'];
        $hospitalAddress = $matchedDoctor['hospital_address'];
        $specialization = $matchedDoctor['specialization'];
    } else {
        // Doctor with the given ID not found
        echo "Doctor not found.";
        exit();
    }

    $stmt->close();
}

// Validate and process the submitted data


// Close the MySQLi connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctors</title>
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
                <a href="dashboard-doctors.php" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <h2 class="heading">Doctors</h2>
            <br>
            <div class="form-container">
                <form method="post" action="edit_doctor.php">
                    <!-- ... -->
                    <div class="form-group">
                        <input type="text" hidden name="id" value="<?php echo $doctorId; ?>">

                        <label for="name">Name</label>
                        <?php if (isset($errors['name']))
                            echo '<span class="error">' . $errors['name'] . '</span>'; ?>
                        <input type="text" id="name" name="name" value="<?php echo $name ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <?php if (isset($errors['email']))
                            echo '<span class="error">' . $errors['email'] . '</span>'; ?>
                        <input type="text" id="email" name="email" value="<?php echo $email ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="hospital_name">Hospital Name</label>
                        <?php if (isset($errors['hospital_name']))
                            echo '<span class="error">' . $errors['hospital_name'] . '</span>'; ?>
                        <input type="text" id="hospital_name" name="hospital_name"
                            value="<?php echo $hospitalName ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="hospital_address">Hospital Address</label>
                        <?php if (isset($errors['hospital_address']))
                            echo '<span class="error">' . $errors['hospital_address'] . '</span>'; ?>
                        <input type="text" id="hospital_address" name="hospital_address"
                            value="<?php echo $hospitalAddress ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="specialization">Specialization</label>
                        <?php if (isset($errors['specialization']))
                            echo '<span class="error">' . $errors['specialization'] . '</span>'; ?>
                        <input type="text" id="specialization" name="specialization"
                            value="<?php echo $specialization ?? ''; ?>">
                    </div>
                    <button type="submit" class="button primary-button">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>