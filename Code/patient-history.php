<?php
require_once "utils/protected.php";
require_once "utils/connection.php";

protectPatientPage();
// Fetch test_reports from the database
$pateintId = $_SESSION['id'];
$sql = "SELECT tr.*,d.name as 'doctor_name', t.name as 'test_name', bl.date as 'test_date' FROM bills bl inner join test_report tr on bl.report_id = tr.id 
        LEFT JOIN lab_test t ON t.id = tr.test_id
        LEFT JOIN doctor d ON tr.doctor_id = d.id
        WHERE tr.patient_id = $pateintId";
$result = $mysqli->query($sql);

// Initialize $reports array to empty array
$reports = $result->fetch_all(MYSQLI_ASSOC);

// Close the MySQLi connection
$mysqli->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Hospital Lab Management System - Patient History</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="css/patient-history.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>

<body class='dark-theme'>
    <?php require_once 'components/Nav.php'; ?>
    <center>
        <div class="container">
            <div class="content">
                <div class='go-back'>
                    <a href="javascript:history.back()" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
                <br>
                <h1>Test History</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Test Name</th>
                            <th>Status</th>
                            <th>Doctor</th>

                        </tr>
                    </thead>
                    <?php if (empty($reports)) : ?>
                            <tr style="text-align:center">
                                <td colspan="3">No records Available.</td>
                            </tr>
                        <?php else : ?>
                            
                            <?php
                            echo '<tbody>';
                        foreach ($reports as $test) {
                            echo '<tr>';
                            $testDate = DateTime::createFromFormat('Y-m-d H:i:s', $test['test_date']);
                            if ($testDate !== false) {
                                // Format the DateTime object as the desired date format with English month name
                                $formattedTestDate = $testDate->format('d F Y');
                                echo '<td>' . $formattedTestDate . '</td>';
                            } else {
                                echo '<td>Invalid date format</td>';
                            }
                            echo '<td><a class="test-link" href="test-details.php?id=' . urlencode($test['id']) . '">' . $test['test_name'] . '</a></td>';
                            echo '<td>' . $test['test_status'] . '</td>';
                            echo '<td>' . (isset($test['doctor_name']) ? $test['doctor_name'] : "N/A") . '</td>';


                            echo '</tr>';
                        }
                        echo '</tbody>';

                        ?>
                    <!-- </tbody> -->
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </center>
</body>

</html>