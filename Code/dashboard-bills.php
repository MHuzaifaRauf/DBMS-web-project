<?php
require_once "utils/protected.php";
require_once "utils/connection.php";

protectManagerPage();

// Check if a report deletion is requested and perform the delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM bills WHERE id = $delete_id";
    if ($mysqli->query($sql) === TRUE) {
        header("Location: dashboard-bills.php");
        exit();
    } else {
        echo "Error deleting record: ";
    }
}

// Fetch test_reports from the database with patient information
$sql = "SELECT
p.name AS patient_name,
p.id AS patient_id,

b.date AS report_date,
sum(lt.price * (1-lt.discount/100)) as total_price,
sum(lt.discount) as total_discount,
GROUP_CONCAT(concat(lt.name,'-',lt.id) ORDER BY lt.name ASC SEPARATOR ', ') AS tests
FROM
bills b
INNER JOIN
patient p ON b.patient_id = p.id
INNER JOIN
test_report tr ON b.report_id = tr.id
INNER JOIN
lab_test lt ON tr.test_id = lt.id
group by b.date,tr.patient_id
ORDER BY b.date
";

$result = $mysqli->query($sql);
$res = array();


$billsArray = $result->fetch_all(MYSQLI_ASSOC);



$mysqli->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bills</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" type="image/png" href="images/favicon.png">
    <link rel="stylesheet" href="css/dashboard/common.css" />
</head>

<body class="dark-theme">

</body>
<?php require_once 'components/Nav.php'; ?>
<?php require_once 'components/sidebar.php'; ?>

<div class="content">
    <div class="details">
        <div class='go-back'>
            <a href="dashboard.php" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <h2 class="heading">Bills</h2>
        <br>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Total Price</th>
                        <th>Total Discount</th>

                        <th>Date</th>
                        <th>Tests </th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($billsArray)) : ?>
                            <tr style="text-align:center">
                                <td colspan="3">No records Available.</td>
                            </tr>
                        <?php else : ?>
                    <?php foreach ($billsArray as $bill) : ?>
                        <tr>
                            <td>
                                <a style="background: none" href=<?php echo "dashboard-patients.php?id=" . $bill["patient_id"] ?>>

                                    <?php echo $bill['patient_name']; ?>
                                </a>
                            </td>
                            <td>
                                $
                                <?php echo $bill['total_price']; ?>
                            </td>
                            <td>

                                <?php echo $bill['total_discount']; ?>%
                            </td>
                            <td>
                                <?php
                                // Assuming $bill['date'] is in the format 'Y-m-d H:i:s'
                                $dateString = $bill['report_date'];

                                // Convert the date string to a DateTime object
                                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);

                                if ($dateTime !== false) {
                                    // Format the DateTime object as the desired date format with English month name
                                    $formattedDate = $dateTime->format('d F Y');
                                    echo $formattedDate;
                                } else {
                                    echo "Invalid date format";
                                }
                                ?>



                            </td>
                            <td>
                                <?php
                                // Get the test names and create links
                                $testNames = explode(', ', $bill['tests']); // Split the string by ', ' to get individual test components
                                $testCount = count($testNames);

                                foreach ($testNames as $index => $test) {
                                    list($testName, $testId) = explode('-', $test); // Split each component into name and ID
                                    $link = "<a style='background: none; display: inline; padding: 0; margin: 0;' href='dashboard-lab-tests.php?id=" . trim($testId) . "'>" . trim($testName) . "</a>";

                                    echo $link;

                                    // Add a comma if it's not the last test
                                    if ($index < $testCount - 1) {
                                        echo ', ';
                                    }
                                }
                                ?>
                            </td>


                            <!-- <td>
                            <a href="edit_bill.php?id=<?php echo urlencode($bill['id']); ?>">Edit</a>
                            <a href="delete_bill.php?id=<?php echo urlencode($bill['id']); ?>">Delete</a>
                        </td> -->
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

</html>