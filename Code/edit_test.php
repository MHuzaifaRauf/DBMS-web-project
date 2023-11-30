<?php
require_once "utils/protected.php";

require_once "utils/connection.php";

protectManagerPage();
$sql = "";
$errors = [];

$_SESSION["error"] = "";
if (isset($_GET['id'])) {
    $labTestId = $_GET['id'];

    // Fetch the lab test with the matching ID from the database
    try {
        $sql = "SELECT * FROM lab_test WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $labTestId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $matchedLabTest = $result->fetch_assoc();
            // Prepopulate the form fields with the lab test's data
            $testName = $matchedLabTest['name'];
            $oldTestName = $matchedLabTest['name'];

            $description = $matchedLabTest['description'];
            $price = $matchedLabTest['price'];
            $discount = $matchedLabTest['discount'];
            $testType = $matchedLabTest['test_type'];
        } else {
            // Lab test not found with the given ID
            echo "Lab Test not found.";
            exit();
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
}

// Validate and process the submitted data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate test name

    $labTestId = $_POST['id'];
    $testName = $_POST['test_name'];
    if (empty($testName)) {
        $errors['test_name'] = 'Test Name is .';
    }

    // Validate description
    $description = $_POST['description'];
    if (empty($description)) {
        $errors['description'] = 'Description is .';
    }

    // Validate price
    $price = $_POST['price'];
    if (!is_numeric($price)) {
        $errors['price'] = 'Price must be a numeric value.';
    }

    // Other common validations
    else if (empty($price)) {
        $errors['price'] = 'Price is .';
    } else if ($price < 0) {
        $errors['price'] = 'Price must be a positive value.';
    } else if ($price > 50000) {
        $errors['price'] = 'Price cannot exceed Rs-50000.';
    }

    // Validate discount
    $discount = $_POST['discount'];
    if (!is_numeric($discount)) {
        $errors['discount'] = 'Discount must be a numeric value.';
    }

    // Other common validations
    else if (empty($discount)) {
        $errors['discount'] = 'Discount is .';
    } else if ($discount < 0) {
        $errors['discount'] = 'Discount must be a non-negative value.';
    } else if ($discount > 100) {
        $errors['discount'] = 'Discount cannot exceed 100%';
    }


    // Validate test type
    $testType = $_POST['test_type'];
    if (empty($testType)) {
        $errors['test_type'] = 'Test Type is Required.';
    }

    // If there are no errors, proceed with further processing
    if (empty($errors)) {
        // Process the submitted data

        // Update the lab test in the database
        try {
            // Update the lab test in the lab_test table
            $updateSQL = "UPDATE lab_test SET name=?, description=?, price=?, discount=?, test_type=? WHERE id=?";
            $stmt = $mysqli->prepare($updateSQL);

            $stmt->bind_param("ssddsi", $testName, $description, $price, $discount, $testType, $labTestId);
            $stmt->execute();
            $stmt->close();



            // Redirect to the manage lab tests page after updating the lab test
            header("Location: dashboard-lab-tests.php");
            exit();
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            // die();
        }
    }
}


// Check if an ID parameter is provided in the URL

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lab Test</title>
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
                <a href="dashboard-lab-tests.php" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <h2 class="heading">Edit Lab Test</h2>
            <br>
            <p>
                <?php if (isset($_SESSION["error"]))
                    echo '<span class="error">' . $_SESSION["error"] . '</span>'; ?>
            </p>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $labTestId ?? ''; ?>">
                <input type="hidden" name="oldTestName" value="<?php echo $oldTestName ?? ''; ?>">

                <div class="form-group">
                    <label for="test_name">Test Name</label>
                    <?php if (isset($errors['test_name']))
                        echo '<span class="error">' . $errors['test_name'] . '</span>'; ?>
                    <input type="text" name="test_name" id="test_name" value="<?php echo $testName ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <?php if (isset($errors['description']))
                        echo '<span class="error">' . $errors['description'] . '</span>'; ?>
                    <textarea name="description" id="description"><?php echo $description ?? ''; ?></textarea>
                </div>
                <br>
                <div class="form-group">
                    <label for="price">Price</label>
                    <?php if (isset($errors['price']))
                        echo '<span class="error">' . $errors['price'] . '</span>'; ?>
                    <input type="number" name="price" id="price" value="<?php echo $price ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="discount">Discount</label>
                    <?php if (isset($errors['discount']))
                        echo '<span class="error">' . $errors['discount'] . '</span>'; ?>
                    <input type="number" step="any" name="discount" id="discount"
                        value="<?php echo $discount ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="test_type">Test Type</label>
                    <?php if (isset($errors['test_type']))
                        echo '<span class="error">' . $errors['test_type'] . '</span>'; ?>
                    <select name="test_type" id="test_type">
                        <option value="on-site" <?php if ($testType === 'on-site')
                            echo 'selected'; ?>>On-site</option>
                        <option value="both" <?php if ($testType === 'both')
                            echo 'selected'; ?>>Both</option>
                    </select>
                </div>

                <br>
                <button type="submit" class="button primary-button">Update</button>
            </form>
        </div>
    </div>
</body>

</html>