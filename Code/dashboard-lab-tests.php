<?php
require_once "utils/protected.php";
require_once "utils/connection.php";


$sql = "";

protectManagerPage();

// Function to fetch lab test data from the database
function getLabTestsFromDatabase($mysqli)
{
    $labTests = array();

    // Fetch lab tests from the database
    try {
        $sql = "SELECT * FROM lab_test";
        $result = $mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $labTests[] = $row;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        die();
    }

    return $labTests;
}

// Call the function to retrieve lab test data
$labTests = getLabTestsFromDatabase($mysqli);

// Check if the request is for lab test deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteLabTestId'])) {
    $labTestId = $_POST['deleteLabTestId'];

    // Step 1: Delete from lab_test table
    $deleteLabTestSql = "DELETE FROM lab_test WHERE id = $labTestId";
    if ($mysqli->query($deleteLabTestSql)) {
        // Redirect to the same page after successful deletion
        header("Location: manage_lab_tests.php");
        exit;
    } else {
        // Handle deletion error (optional)
        $_SESSION['error'] = "Error occurred while deleting the lab test.";
        header("Location: dashboard-lab-tests.php");
        exit;
    }
}
$highlightedID = isset($_GET['id']) ? intval($_GET['id']) : null;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Lab-Tests</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/dashboard/common.css" />
        <link rel="icon" type="image/png" href="images/favicon.png">

    </head>
</head>

<body class="dark-theme">
    <?php require_once 'components/Nav.php'; ?>
    <?php require_once 'components/sidebar.php'; ?>

    <div class="content">
        <div class="details">
            <div class='go-back'>
                <a href="dashboard.php" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <h2 class="heading">Lab Tests</h2>
            <br>
            <a href="add_labtests.php" class='add-btn'><i class="fa fa-plus"></i>
                Add Lab-Test</a>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>New Price</th>
                            <th>Test Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($labTests as $labTest) : ?>
                            <tr class=<?php echo intval($highlightedID) === intval($labTest['id']) ? "highlight" : "" ?>>
                                <td>
                                    <?php echo $labTest['name']; ?>
                                </td>
                                <td>
                                    <?php echo $labTest['description']; ?>
                                </td>
                                <td>
                                    <?php echo $labTest['price']; ?>
                                </td>
                                <td>
                                    <?php echo $labTest['discount']; ?>%
                                </td>
                                <td>
                                    <?php echo $labTest['price'] - ($labTest['price'] * $labTest['discount'] / 100); ?>
                                </td>
                                <td>
                                    <?php echo $labTest['test_type']; ?>
                                </td>
                                <td>
                                    <a class="edit-btn" href="edit_test.php?id=<?php echo urlencode($labTest['id']); ?>"><i class="fas fa-edit"></i></a>
                                    <form action="" method="post" class="delete-labtest-form">
                                        <input type="hidden" name="deleteLabTestId" value="<?php echo $labTest['id']; ?>">
                                        <!-- <button type="submit" class="delete-btn"><i class="fas fa-trash-alt"></i> </button> -->
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listener for table row hover
            const tableRows = document.querySelectorAll('.data-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    // Remove the highlight class on hover
                    this.classList.remove('highlight');
                });
            });
        });
        // Add event listener to the "Delete" forms with class "delete-labtest-form"
        document.querySelectorAll(".delete-labtest-form").forEach(form => {
            form.addEventListener("submit", function(event) {
                event.preventDefault();

                // Show SweetAlert2 confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f47a3d',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user confirms, submit the form for lab test deletion
                        this.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>