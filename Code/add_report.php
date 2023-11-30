<?php
require_once "utils/protected.php";

protectManagerPage();

// Define variables
$testName = $testStatus = "";
$errors = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process the submitted data
    $testName = $_POST['test_name'];
    $testStatus = $_POST['test_status'];

    // Validate test name
    if (empty($testName)) {
        $errors['test_name'] = 'Test Name is required.';
    }

    // Validate test status
    if (empty($testStatus)) {
        $errors['test_status'] = 'Test Status is required.';
    }

    // If there are no errors, proceed with further processing
    if (empty($errors)) {
        // Process the submitted data

        // Add the report to the $reports array
        $reports[] = [
            'test_name' => $testName,
            'test_status' => $testStatus
        ];

        // Redirect to the manage reports page
        header("Location: dashboard-reports.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Report</title>
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
                <a href="dashboard-reports.php" class="go-back-button">
                    <i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <h2 class="heading">Add Report</h2>
            <br>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="test_name">Test Name</label>
                    <?php if (isset($errors['test_name']))
                        echo '<span class="error">' . $errors['test_name'] . '</span>'; ?>
                    <input type="text" name="test_name" id="test_name" placeholder="Test Name"
                        value="<?php echo $testName; ?>">
                </div>
                <div class="form-group">
                    <label for="test_status">Test Status</label>
                    <?php if (isset($errors['test_status']))
                        echo '<span class="error">' . $errors['test_status'] . '</span>'; ?>
                    <select name="test_status" id="test_status">
                        <option value="pending" <?php if ($testStatus === 'pending')
                            echo 'selected'; ?>>Pending</option>
                        <option value="positive" <?php if ($testStatus === 'positive')
                            echo 'selected'; ?>>Positive
                        </option>
                        <option value="negative" <?php if ($testStatus === 'negative')
                            echo 'selected'; ?>>Negative
                        </option>
                    </select>
                </div>

    <br>
                <button type="submit" class="button primary-button">Add</button>
            </form>
        </div>
    </div>
</body>

</html>