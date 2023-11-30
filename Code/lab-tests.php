<?php
require_once "utils/protected.php";
require_once "utils/connection.php";


$sql = "SELECT * FROM lab_test"; // Fetch all lab tests from the database
$result = $mysqli->query($sql);
$labTests = $result->fetch_all(MYSQLI_ASSOC);
$sql2 = "SELECT * FROM doctor"; // Fetch all doctors from the database
$result2 = $mysqli->query($sql2);
$doctors = $result2->fetch_all(MYSQLI_ASSOC);
if (isset($_GET['labTests'])) {
  $selectedTestIds = explode(',', $_GET['labTests']);
} else {
  $selectedTestIds = []; // Default empty array
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="images/favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/lab-tests.css" />



  <title>Lab-Tests</title>
  <style>
    section {
      margin-top: 10%;
      background-color: none;
    }
  </style>
</head>

<body class="dark-theme">
  <?php require_once 'components/Nav.php'; ?>
  <div class="container">
    <div class='go-back'>
      <a href="index.php" class="go-back-button"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div>

      <form action="patient-bill.php" method="GET">

        <div class="layout">

          <div class="left-section">
            <h1>Select Tests</h1>
            <div class="wrapper">

              <?php foreach ($labTests as $test) : ?>
                <div class="card">
                  <div class="card-inner <?php if (in_array($test['id'], $selectedTestIds))
                                            echo ' selected'; ?>">
                    <div class="card-front ">
                      <p><?php echo $test['name']; ?></p>
                    </div>
                    <div class="card-back">
                      <div class="list">
                        <div class="column">
                          <input id="<?php echo $test['id']; ?>" title="select" placeholder="labTest" type="checkbox" name="labTests[]" value="<?php echo $test['id']; ?>" onchange="toggleSelected(this);" <?php if (in_array($test['id'], $selectedTestIds)) echo 'checked'; ?>>
                          <?php echo '<a class="list-item" href="test-details.php?id=' . urlencode($test['id']) . '"><span>' . $test["name"] . ' ($' . $test["price"] . ')</span></a>' ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <br> <!-- Add a <br> tag to create spacing between cards -->
              <?php endforeach; ?>


            </div>
          </div>




          <div class="right-section">
            <div>
              <label>
                <input title="Home" placeholder="Home Sample" type="checkbox" name="homesample" id="homesampleCheckbox">
                <b> Home Sampling</b>
              </label>
            </div>
            <br>
            <div id="addressContainer" class="addressContainer" style="display: none;">
              <div>

                <label for="patientAddress">Enter Your Address</label>
              </div>
              <input type="text" id="patientAddress" name="patientAddress" placeholder="123 Main Street ,Apt 4B  ,Gujranwala ,52250 ,PAK">
            </div>
            <div class="select-doctor">
              <label for="doctor">Recommended By Doctor:</label>
              <select name="doctor" id="doctor">
                <option value="">Select a Doctor</option>
                <?php foreach ($doctors as $doctor) : ?>
                  <option value="<?php echo $doctor['id']; ?>">
                    <?php echo $doctor['name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <br>
            <?php if (isset($_SESSION["error"])) : ?>
              <p class='error'>
                <?php
                    echo $_SESSION["error"];
                    $_SESSION["error"] = "";
                ?>
              </p>
              <br>
            <?php endif; ?>
           
            <?php
            if (!isset($_SESSION['type'])) {
              echo "<a href='login-patient.php' class='test-button button primary-button'>Register to Apply</a>";
            } else {
              echo '<button type="submit" class="button primary-button">Apply</button>';
            }
            ?>
          </div>
        </div>


      </form>

    </div>

  </div>
</body>
<script>
  function toggleSelected(checkbox) {
    console.log(JSON.stringify(checkbox));
    const card = checkbox.closest('.card-inner');
    if (checkbox.checked) {
      card.classList.add('selected');
    } else {
      card.classList.remove('selected');
    }
  }
  //   const urlParams = new URLSearchParams(window.location.search);
  // const selectedIdsParam = urlParams.get('labTests');

  // if (selectedIdsParam) {
  //   const selectedIds = selectedIdsParam.split(',').map(id => parseInt(id, 10));
  //   selectedIds.forEach(id => {
  //     const checkbox = document.getElementById(id);
  //     if (checkbox) {
  //       checkbox.checked = true;
  //       toggleSelected(checkbox);
  //     }
  //   });
  // }
  const homesampleCheckbox = document.getElementById('homesampleCheckbox');
  const addressContainer = document.getElementById('addressContainer');

  homesampleCheckbox.addEventListener('change', function() {
    if (homesampleCheckbox.checked) {
      addressContainer.style.display = 'block';
    } else {
      addressContainer.style.display = 'none';
    }
  });
</script>

</html>