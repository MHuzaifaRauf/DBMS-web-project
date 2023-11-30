<?php
require_once "utils/protected.php";
require_once "utils/connection.php";

protectManagerPage();

// Function to fetch patient data from the database
function getPatientsFromDatabase($mysqli)
{
    $patients = array();

    // Fetch patients from the database
    try {
        $sql = "SELECT * FROM patient";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }
        }
    } catch (Exception $e) {
        // Handle the exception as per your requirement
    }

    return $patients;
}

// Call the function to retrieve patient data
$patients = getPatientsFromDatabase($mysqli);

// Check if the request is for patient deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletePatientId'])) {
    $patientId = $_POST['deletePatientId'];

    // Step 1: Delete from patient table
    $deletePatientSql = "DELETE FROM patient WHERE id = $patientId";
    if ($mysqli->query($deletePatientSql)) {
        // Redirect to the same page after successful deletion
        header("Location: dashboard-patients.php");
        exit;
    } else {
        // Handle deletion error (optional)
        $_SESSION['error'] = "Error occurred while deleting the patient.";
        header("Location: dashboard-patients.php");
        exit;
    }
}
$highlightedPatientId = isset($_GET['id']) ? intval($_GET['id']) : null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
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
            <h2 class="heading">Patients</h2>
            <br>

            <!-- <a href="add_patient.php" class='add-btn'><i class="fa fa-plus"></i> Add Patient</a> -->

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient) : ?>
                            <tr class=<?php echo intval($highlightedPatientId) === intval($patient['id']) ? "highlight" : "" ?>>
                                <td>
                                    <?php echo $patient['name'] ?>
                                </td>
                                <td>
                                    <?php echo $patient['age']; ?>
                                </td>
                                <td>
                                    <?php echo $patient['gender']; ?>
                                </td>
                                <td>
                                    <?php echo $patient['email']; ?>
                                </td>
                                <td>
                                    <?php echo $patient['phone']; ?>
                                </td>
                                <td>
                                    <?php echo $patient['address']; ?>
                                </td>
                                <td>
                                    <a class="edit-btn" href="edit_patient.php?id=<?php
                                                                                    echo urlencode($patient['id']); ?>">
                                                                                    <i class="fas fa-edit">

                                        </i></a>
                                    <!-- Create a form for patient deletion -->
                                    <form action="" method="post" class="delete-patient-form">
                                        <input type="hidden" name="deletePatientId" value="<?php echo $patient['id']; ?>">
                                        <button type="submit" class="delete-btn"><i class="fas fa-trash-alt"></i> </button>
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
        // document.addEventListener('DOMContentLoaded', function () {
        //     // Add event listener for table row hover
        //     const tableRows = document.querySelectorAll('.data-table tbody tr');
        //     tableRows.forEach(row => {
        //         row.addEventListener('mouseenter', function () {
        //             // Remove the highlight class from sibling tr tags
        //             tableRows.forEach(siblingRow => {
        //                 if (siblingRow !== this) {
        //                     siblingRow.classList.remove('highlight');
        //                 }
        //             });
        //         });
        //     });
        // });
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
        // Add event listener to the "Delete" forms with class "delete-patient-form"
        document.querySelectorAll(".delete-patient-form").forEach(form => {
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
                        // If user confirms, submit the form for patient deletion
                        this.submit();
                    }
                });
            });
        });
    </script>

</body>

</html>