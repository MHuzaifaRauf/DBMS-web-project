<?php
require_once "utils/protected.php";
require_once "utils/connection.php";

$selectedLabTest = null;

if (isset($_GET['id'])) {
    $selectedTestId = $_GET['id'];

    try {
        // Fetch the selected test from the database using prepared statement
        $sql = "SELECT * FROM lab_test WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $selectedTestId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $selectedLabTest = $result->fetch_assoc();
        } else {
            echo "<p>Invalid test selection.</p>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<p>No test selected.</p>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/test-detail.css" />
    <link rel="icon" type="image/png" href="images/favicon.png">
    <title>Lab-Tests</title>
    <style>
        /* CSS styles */
    </style>
</head>

<body class="dark-theme">
    <?php require_once 'components/Nav.php'; ?>
    <div class="container">
        <div class='go-back'>
            <a href=<?php echo $_SERVER['HTTP_REFERER']; ?> class='go-back-button'><i class='fas fa-arrow-left'></i> Back</a>
        </div><br />
        <?php
        if ($selectedLabTest) {
            $name = $selectedLabTest['name'];
            $description = $selectedLabTest['description'];
            $discount = $selectedLabTest['discount'];
            $price = $selectedLabTest['price'];
            $cutPrice = $price;
            $newPrice = $price - ($price * $discount/100);
           
            $testType = $selectedLabTest['test_type'];


            echo "<div class='test-card'>";
            echo "<h2>$name</h2>";
            echo "<p class='desc'>$description</p>";


            echo "<p class='price'>Price: <del>$$price</del> =>  $$newPrice </p>";
            echo "<p class='discount'>Discount: $discount%</p>";
            echo "<p class='test-type'>Test Type: $testType</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>

</html>