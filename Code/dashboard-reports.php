<?php
require_once "utils/protected.php";
require_once "utils/connection.php";

protectManagerPage();

// Check if a report deletion is requested and perform the delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM test_report WHERE id = $delete_id";
    if ($mysqli->query($sql) === TRUE) {
        header("Location: dashboard-reports.php");
        exit();
    } else {
        echo "Error deleting record: ";
    }
}

// Fetch test_reports from the database with patient information
$sql = "SELECT tr.*,d.name as 'doctor_name',d.email as 'doctor_email',p.name as
 'patient_name',p.email as 'patient_email',lt.name as 'test_name',lt.id as 'test_id'
FROM test_report tr
INNER JOIN bills b ON tr.id = b.report_id 

INNER JOIN patient p ON tr.patient_id = p.id 
INNER JOIN doctor d ON tr.doctor_id = d.id
INNER JOIN lab_test lt ON tr.test_id = lt.id  ORDER BY b.date";

$result = $mysqli->query($sql);

// Initialize $reports array to empty array
$reports = $result->fetch_all(MYSQLI_ASSOC);

// Close the MySQLi connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reports</title>
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
                <a href="dashboard.php" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <h2 class="heading">Reports</h2>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th> Lab Test</th>
                            <th> Status</th>
                            <th>Patient</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reports)) : ?>
                            <tr style="text-align:center">
                                <td colspan="3">No records Available.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($reports as $report) : ?>
                                <tr>
                                    <td>
                                        <a style="background: none;padding:0;margin:0" href=<?php echo "dashboard-lab-tests.php?id=" . $report["test_id"] ?>>

                                            <?php echo $report['test_name']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo $report['test_status']; ?>
                                    </td>
                                    <td>
                                        <a style="background: none;padding:0;margin:0" href=<?php echo "dashboard-patients.php?id=" . $report["patient_id"] ?>>

                                            <?php echo $report['patient_name']; ?>
                                        </a>
                                      
                                    </td>
                                    <td class="action-body">
                                        <a href="edit_report.php?id=<?php 
                                        echo urlencode($report['id']); ?>">Update Status</a>
<!-- 
                                        <a href="#" onclick="forwardPatient('<?php
                                                                                echo $report['id']; ?>', 
                                            '<?php echo $report['patient_email']; ?>', 
                                            '<?php echo $report['test_status']; ?>', 
                                            '<?php echo $report['test_name']; ?>')">Forward
                                            Patient</a>
                                        <a href="#" onclick="forwardDoctor('<?php echo
                                                                            $report['id']; ?>', 
                                        '<?php echo $report['test_status']; ?>', 
                                            '<?php echo $report['test_name']; ?>',
                                            '<?php echo $report['patient_name']; ?>')">Forward Doctor</a>
 -->

                                        <a href="#" onclick="
                                        forwardReports('<?= $report['id']; ?>',
                                        '<?= $report['patient_email']; ?>', 
                                        '<?= $report['test_status']; ?>', 
                                        '<?= $report['test_name']; ?>',
                                        '<?= $report['patient_name']; ?>',
                                        '<?= $report['doctor_name']; ?>',
                                        '<?= $report['doctor_email']; ?>',
                                        '<?= $report['patient_id']; ?>',
                                        '<?= $report['doctor_id']; ?>',
                                        '<?= $_SESSION['id'] ?>')">
                                            Forward Reports </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/forward-report.js"></script>
</body>

</html>