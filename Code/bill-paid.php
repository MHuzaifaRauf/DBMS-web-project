<?php
// require_once necessary files
require_once "utils/protected.php";

require_once "utils/connection.php";

// Ensure the user is a patient before proceeding
protectPatientPage();

// Initialize the success variable
$success = false;

// Check if labTests are set in the GET parameters
if (isset($_GET['labTests'])) {
    // Assuming $_GET['labTests'] is an array of selected lab test IDs
    $selectedTestIds = array_map('intval', $_GET['labTests']);
    $doctorId = $_GET['doctor'];
    
    // Fetch the selected lab tests from the database
    if (!empty($selectedTestIds)) {
        try {
            $inClause = implode(',', $selectedTestIds);
            $sql = "SELECT * FROM lab_test WHERE id IN ($inClause)";
            $stmt = $mysqli->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch the lab tests as an associative array
                $selectedLabTests = $result->fetch_all(MYSQLI_ASSOC);

                // Get the patient ID from the session
                $patientId = $_SESSION['id']; // Replace with the actual patient ID

                // Initialize a flag to track success
                $success = true;

                // Iterate through selected lab tests and insert into test_report
                $timestamp = strtotime(date('Y-m-d H:i:00')); // Get the present timestamp with seconds set to "00"
                foreach ($selectedLabTests as $test) {
                 
                    $labTestId = $test['id'];
                    $testStatus = "pending";
                    
                    // Prepare the INSERT query using prepared statement
                    $sql = "INSERT INTO test_report (test_id, doctor_id, test_status, patient_id) 
                            VALUES (?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($sql);

                    // Check for errors in preparing the statement
                    if (!$stmt) {
                        $success = false;
                        echo "Prepare error: " . $mysqli->error;
                        break; // Exit the loop if prepare fails
                    }

                    // Bind parameters and execute the test_report insertion
                    $stmt->bind_param("iisi", $labTestId, $doctorId, $testStatus, $patientId);
                    if ($stmt->execute()) {
                        // Get the last inserted report_id
                        $reportId = $stmt->insert_id;

                        // Prepare the INSERT query for the bill table
                        $sql2 = "INSERT INTO bills (patient_id, report_id, date) VALUES (?, ?, FROM_UNIXTIME(?))";
                        $stmt2 = $mysqli->prepare($sql2);

                        // Check for errors in preparing the statement
                        if (!$stmt2) {
                            $success = false;
                            echo "Prepare error for bills table: " . $mysqli->error;
                            break; // Exit the loop if prepare fails
                        }

                        // Bind parameters and execute the bill insertion
                        $stmt2->bind_param("iii", $patientId, $reportId, $timestamp);
                        if (!$stmt2->execute()) {
                            $success = false;
                            echo "Execute error for bills table: " . $stmt2->error;
                            break; // Exit the loop if execute fails
                        }
                    } else {
                        $success = false;
                        echo "Execute error for test_report table: " . $stmt->error;
                        break; // Exit the loop if execute fails
                    }
                }

                
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Payment Confirmation</title>
    <link rel="stylesheet" type="text/css" href="css/bill-paid.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="container">
        <?php
        if ($success) {
            echo "<h1>Payment Confirmation</h1>
        <div class='success-message'>
            <i class='fas fa-check-circle'></i>
            <p>Payment Successful!</p>
        </div>
        <div class='return-home'>
            <p>Thank you for your payment.</p>
            <p>Would you like to return to the home screen?</p>
            <a href='index.php'>Return Home</a>
        </div>";
        } else {
            echo "<h1>Payment Failed</h1>
            <div class='error-message'>
                <i class='fas fa-check-circle'></i>
                <p>Payment Failed!</p>
            </div>
            <div class='return-home'>
                <p>Try Again Later.</p>
                <p>Would you like to return to the home screen?</p>
                <a href='index.php'>Return Home</a>
            </div>";
        }
        ?>
    </div>
</body>

</html>