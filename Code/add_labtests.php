<?php
require_once "utils/protected.php";

require_once "utils/connection.php";

protectManagerPage();

// Define variables
$testName = $description = $price = $discount = $testType = "";
$errors = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $testName = $_POST['test_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $testType = $_POST['test_type'];

    $errors = [];

    // Validation functions
    function validateRequired($value, $fieldName)
    {
        if (empty($value)) {
            return "$fieldName is required.";
        }
        return '';
    }

    function validateNumeric($value, $fieldName)
    {
        if (!is_numeric($value)) {
            return "$fieldName must be a numeric value.";
        }
        return '';
    }

    // Validate test name
    $errors['test_name'] = validateRequired($testName, 'Test Name');

    // Validate description
    $errors['description'] = validateRequired($description, 'Description');

    // Validate price
    $errors['price'] = validateRequired($price, 'Price');
    $errors['price'] .= validateNumeric($price, 'Price');
    if ($price < 0 || $price > 50000) {
        $errors['price'] .= ' Price must be a positive value not exceeding Rs-50000.';
    }

    // Validate discount
    $errors['discount'] = validateRequired($discount, 'Discount');
    $errors['discount'] .= validateNumeric($discount, 'Discount');
    if ($discount < 0 || $discount > 100) {
        $errors['discount'] .= ' Discount must be a non-negative value not exceeding 100%.';
    }

    // Validate test type
    $errors['test_type'] = validateRequired($testType, 'Test Type');

    // Check if there are no errors
    if (empty(array_filter($errors))) {
        $sql = "INSERT INTO lab_test (name, description, price, discount, test_type)
        VALUES (?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ssdds", $testName, $description, $price, $discount, $testType);

            if ($stmt->execute()) {
                // Successfully added the lab test
                header("Location: dashboard-lab-tests.php"); // Redirect to the lab test list page
                exit();
            } else {
                $errors['main'] = 'Error adding lab test: ' . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $errors['main'] = 'Error preparing the statement: ' . $mysqli->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lab Test</title>
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
            <h2 class="heading">Add Lab Test</h2>
            <br>
            <?php if (isset($errors['main']))
                echo '<span class="error">' . $errors['main'] . '</span>'; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="test_name">Test Name</label>
                    <?php if (isset($errors['test_name']))
                        echo '<span class="error">' . $errors['test_name'] . '</span>'; ?>
                    <input type="text" name="test_name" id="test_name" placeholder="Test Name" value="<?php echo $testName; ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <?php if (isset($errors['description']))
                        echo '<span class="error">' . $errors['description'] . '</span>'; ?>
                    <br>
                    <textarea name="description" id="description" placeholder="Description"><?php echo $description; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <?php if (isset($errors['price']))
                        echo '<span class="error">' . $errors['price'] . '</span>'; ?>
                    <input type="text" name="price" id="price" placeholder="Price" value="<?php echo $price; ?>">
                </div>
                <div class="form-group">
                    <label for="discount">Discount</label>
                    <?php if (isset($errors['discount']))
                        echo '<span class="error">' . $errors['discount'] . '</span>'; ?>
                    <input type="text" name="discount" id="discount" placeholder="Discount" value="<?php echo $discount; ?>">
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
                <button type="submit" class="button primary-button">Add</button>
            </form>
        </div>
    </div>
</body>

</html>