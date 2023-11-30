<?php
require_once "utils/protected.php";

require_once "utils/connection.php";

protectManagerPage();

$errors = [];
if (isset($_GET['id'])) {
    $reportId = $_GET['id'];

     // Fetch the Report with the matching ID from the database
     $sql = "SELECT * FROM test_report WHERE id = ?";
     $stmt = $mysqli->prepare($sql);
     $stmt->bind_param("i", $reportId);
     $stmt->execute();
     $result = $stmt->get_result();
 
     // Check if the doctor exists
     if ($result->num_rows === 1) {
         $matched = $result->fetch_assoc();
 
         // Prepopulate the form fields with the doctor's data
         $testStatus = $matched['test_status'];
         $testName = $matched['test_name'];

        
         
        
     } else {
         // Doctor with the given ID not found
         echo "Report not found.".$reportId;
         exit();
     }
 
     $stmt->close();
}

// Validate and process the submitted data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = $_POST['id'];
    // Validate test status
    $testStatus = $_POST['test_status'];
    if (empty($testStatus)) {
        $errors['test_status'] = 'Test Status is required.';
    }

    // If there are no errors, proceed with further processing
    if (empty($errors)) {
        

            $sql = "UPDATE test_report SET test_status = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $testStatus, $reportId);
            $stmt->execute();
            $stmt->close();
        // Redirect to the manage reports page after updating the report
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
    <title>Edit Report</title>
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
                <a href="dashboard-reports.php" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <h2 class="heading">Edit Report</h2>
            <br>
            <form method="POST" action="edit_report.php">
                <input type="hidden" name="id" value="<?php echo $reportId ?? ''; ?>">
                <!-- <div class="form-group">
                    <label for="test_name">Test Name</label>
                    <?php if (isset($errors['test_name']))
                        echo '<span class="error">' . $errors['test_name'] . '</span>'; ?>
                    <input type="text" name="test_name" id="test_name" value="<?php echo $testName ?? ''; ?>" required>
                </div> -->
                <div>
                    <h3><?php
                    echo $testName;
                    ?></h3>
                </div>
                <br>
                <div class="form-group">
                    <label for="test_status">Test Status</label>
                    <?php if (isset($errors['test_status']))
                        echo '<span class="error">' . $errors['test_status'] . '</span>'; ?>
                    <select name="test_status" id="test_status" required>
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
                <button type="submit" class="button primary-button">Update</button>
            </form>
        </div>
    </div>
</body>

</html>