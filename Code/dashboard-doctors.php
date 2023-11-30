<?php
require_once "utils/protected.php";
require_once "utils/connection.php";


protectManagerPage();

// Check if a doctor deletion is requested
if (isset($_POST['deleteDoctorId'])) {
    $delete_id = $_POST['deleteDoctorId'];

    // Perform the delete operation in the database
    $sql = "DELETE FROM doctor WHERE id = $delete_id";
    if ($mysqli->query($sql) === TRUE) {
        // Redirect to the same page after successful deletion
        header("Location: dashboard-doctors.php");
        exit();
    } else {
        echo "Error deleting record: ";
    }
}

// Fetch data from the "doctor" table
$sql = "SELECT * FROM doctor";
$result = $mysqli->query($sql);

// Initialize $doctors array to store fetched data
$doctors = $result->fetch_all(MYSQLI_ASSOC);

// Close the MySQLi connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
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
            <h2 class="heading">Doctors</h2>
            <br>
            <a href="add_doctor.php" class='add-btn'><i class="fa fa-plus"></i>
                Add Doctor</a>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Hospital name</th>
                            <th>Hospital Address</th>
                            <th>Specialization</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($doctors)): ?>
                            <tr>
                                <td colspan="6">No doctors found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($doctors as $doctor): ?>
                                <tr>
                                    <td>
                                        <?php echo $doctor['name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $doctor['email']; ?>
                                    </td>
                                    <td>
                                        <?php echo $doctor['hospital_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $doctor['hospital_address']; ?>
                                    </td>
                                    <td>
                                        <?php echo $doctor['specialization']; ?>
                                    </td>
                                    <td>
                                        <a class="edit-btn" 
                                        href="edit_doctor.php?id=<?php echo urlencode($doctor['id']); ?>"><i class="fas fa-edit"></i></a>
                                        <form action="" method="post" class="delete-doctor-form">
                                            <input type="hidden" name="deleteDoctorId"
                                             value="<?php echo $doctor['id']; ?>">
                                            <button type="submit" class="delete-btn">
                                                <i class="fas fa-trash-alt"></i> </button>
                                        </form>
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
    <script>
        // Add event listener to the "Delete" forms with class "delete-doctor-form"
        document.querySelectorAll(".delete-doctor-form").forEach(form => {
            form.addEventListener("submit", function (event) {
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
                        // If user confirms, submit the form for doctor deletion
                        this.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>