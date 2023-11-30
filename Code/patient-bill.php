<?php
require_once "utils/protected.php";
require_once "utils/connection.php";

// Ensure the user is authenticated as a patient
protectPatientPage();

$selectedLabTests = [];
$selectedTestIds = [];
$errors = [];
$total = 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if lab tests were selected
    if (isset($_GET['labTests']) || isset($_POST['labTests'])) {
        // Sanitize and validate the selected test IDs to prevent SQL injection
        $selectedTestIds = isset($_GET['labTests']) ? $_GET['labTests'] : explode(',', $_POST['labTests']);
        $selectedTestIds = array_map('intval', $selectedTestIds);

        $inClause = implode(',', $selectedTestIds);

        // Fetch the selected lab tests from the database using prepared statements
        try {
            $sql = "SELECT * FROM lab_test WHERE id IN ($inClause)";
            $stmt = $mysqli->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $selectedLabTests = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $errors[] = "No matching lab tests found in the database.";
            }
        } catch (Exception $e) {
            $errors[] = "Error occurred while fetching lab tests from the database.";
        }
    }

    $homesample = isset($_GET['homesample']);
    
    if (empty($selectedLabTests)) {
        $errors[] = "You need to select a Lab-Test before applying.";
    }

    if ($homesample && empty($_GET['patientAddress'])) {
        $errors[] = "Please provide the patient address.";
    }

    if (empty($_GET['doctor'])) {
        $errors[] = "Please select a Doctor who recommended these tests.";
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode('</br>', $errors);

        header("Location: lab-tests.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate credit card details
    $cardNumber = $_POST['cardNumber'];
    $cardName = $_POST['cardName'];
    $expDate = $_POST['expDate'];
    $cvv = $_POST['cvv'];

    // Validate credit card details
    if (empty($cardNumber) || empty($cardName) || empty($expDate) || empty($cvv)) {
        $errors[] = 'All fields are required.';
    } else {
        // Validate card number
        if (!preg_match('/^\d{16}$/', $cardNumber)) {
            $errors[] = 'Please enter a 16-digit card number.';
        }

        // Validate card name (non-empty)
        if (empty($cardName)) {
            $errors[] = 'Please enter the name on the card.';
        }

        // Validate expiration date (format: MM/YY)
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expDate)) {
            $errors[] = 'Please enter the expiration date in the format MM/YY.';
        }

        // Validate CVV (3 digits)
        if (!preg_match('/^\d{3}$/', $cvv)) {
            $errors[] = 'Please enter a 3-digit CVV.';
        }
    }

    $doctorId = $_POST['doctor'];
    $selectedTestIds = isset($_GET['labTests']) ? $_GET['labTests'] : explode(',', $_POST['labTests']);
    $selectedTestIds = array_map('intval', $selectedTestIds);

    if (empty($errors)) {
        // Convert the array of selected test IDs and the doctor ID into a URL-encoded query string
        $queryParams = http_build_query([
            'labTests' => $selectedTestIds,
            'doctor' => $doctorId // require_once the doctor ID in the query parameters
        ]);

        // Create the URL with the query parameters and redirect
        $redirectUrl = "bill-paid.php?" . $queryParams;
        header("Location: " . $redirectUrl);
        exit;
    } else {
        $_SESSION['error'] = implode(' ', $errors);
        header("Location: lab-tests.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Patient Bill</title>
    <link rel="stylesheet" type="text/css" href="css/patient-bill.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="content">
            <h1>Patient Bill</h1>
            <ul class="bill-list">
                <?php foreach ($selectedLabTests as $selectedTest) : ?>
                    <?php if (!empty($selectedTest)) : ?>
                        <?php $discountedPrice = $selectedTest['price'] - ($selectedTest['price'] * $selectedTest['discount'] / 100); ?>
                        <li>
                            <a class="test-link" href="test-details.php?id=<?php echo urlencode($selectedTest['id']); ?>">
                                <?php echo $selectedTest['name']; ?> - $
                                <del><?php echo number_format($selectedTest['price'], 2); ?></del> (Discounted: $
                                <?php echo number_format($discountedPrice, 2); ?>)
                            </a>
                        </li>
                        <?php $total += $discountedPrice; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <p class="total">Total: $<?php echo number_format($total, 2); ?></p>

            <form action="patient-bill.php" method="post">
                <!-- Hidden input to send lab test IDs -->
                <input type="hidden" name="labTests" value="<?= implode(',', $selectedTestIds); ?>">
                <input type="hidden" name="doctor" value="<?= $_GET['doctor']; ?>">

                <?php if (isset($errors) && in_array('All fields are required.', $errors)) : ?>
                    <p class="error">All fields are required.</p>
                <?php endif; ?>
                <label for="cardNumber">Credit Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber" value="<?php echo isset($_POST['cardNumber']) ? $_POST['cardNumber'] : ''; ?>">
                <?php if (isset($errors) && in_array('Please enter a 16-digit card number.', $errors)) : ?>
                    <p class="error">Invalid credit card number. Please enter a 16-digit card number.</p>
                <?php endif; ?>

                <!-- Add more input fields and validation messages for other credit card fields -->
                <label for="cardName">Name on Card:</label>
                <input type="text" id="cardName" name="cardName" value="<?php echo isset($_POST['cardName']) ? $_POST['cardName'] : ''; ?>">
                <?php if (isset($errors) && in_array('Please enter the name on the card.', $errors)) : ?>
                    <p class="error">Please enter the name on the card.</p>
                <?php endif; ?>

                <label for="expDate">Expiration Date:</label>
                <input type="text" id="expDate" name="expDate" value="<?php echo isset($_POST['expDate']) ? $_POST['expDate'] : ''; ?>">
                <?php if (isset($errors) && in_array('Please enter the expiration date in the format MM/YY.', $errors)) : ?>
                    <p class="error">Invalid expiration date. Please enter the expiration date in the format MM/YY.</p>
                <?php endif; ?>

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" value="<?php echo isset($_POST['cvv']) ? $_POST['cvv'] : ''; ?>">
                <?php if (isset($errors) && in_array('Please enter a 3-digit CVV.', $errors)) : ?>
                    <p class="error">Invalid CVV. Please enter a 3-digit CVV.</p>
                <?php endif; ?>
                <!-- ... -->
                <button type="submit" class="pay-button"><i class="fas fa-credit-card"></i> Pay</button>
                <a href="lab-tests.php?labTests=<?= $inClause ?>">
                    <input type="button" class="cancel-button" value="Cancel"></input>
                </a>
            </form>
        </div>
    </div>
</body>

</html>