<style>
  /* Navbar Styles */
  .navbar {


    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #001f3f;
    font-size: large;
    z-index: 999;
    position: fixed;
    top: 0;
    width: 100%;
    padding: 25px;
    height: 10vh;
  }

  .navbar-title {
    font-size: 24px;
    font-weight: bold;
    background: linear-gradient(to right, rgba(0, 0, 0, 0.4) #E8BCB9);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: glowing 1.5s ease-in-out infinite alternate;
  }

  @keyframes glowing {
    0% {
      text-shadow: 0 0 0px #eef4ed, 0 0 15px #eef4ed, 0 0 25px #eef4ed;
    }

    100% {
      text-shadow: 0 0 1px rgba(0, 0, 0, 0.4), 0 0 1px #eef4ed, 0 0 6px rgba(0, 0, 0, 0.4);
    }
  }

  .navbar-nav {
    list-style-type: none;
    display: flex;
    margin: 0;
    padding: 0;
  }

  .navbar-nav li {
    margin-left: 100px;
    font-size: larger;

  }

  .navbar-nav li a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .navbar-nav li a:hover {
    color: rgba(0, 0, 0, 0.6);

  }

  .navbar-nav li a.current {
    color: #1f66ad !important;
    font-weight: bold;
    /* Change this to the desired color for the selected nav link */
    /* Add any other styles you want for the selected link */
  }


  /* Responsive styles */
  @media (max-width: 768px) {
    .navbar {
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 14px;
      position: sticky;
      height: auto;
      top: 0;
      z-index: 20
    }

    .navbar-title {
      margin-bottom: 10px;
      text-align: center;
    }

    .navbar .navbar-nav {
      width: 100%;
      justify-content: space-around;
      align-items: center;
      flex-direction: row;
    }

    .navbar-nav li {
      margin: 5px 0;
      font-size: medium;
    }

    .navbar-nav {

      margin-top: 10px;
    }


  }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<nav class="navbar">
  <div class="navbar-title">
    <a href="index.php">Best Lab</a>
  </div>
  <ul class="navbar-nav">
    <?php if (isset($_SESSION['username'])) : // If user is logged in 
    ?>
      <?php if ($_SESSION['type'] == 'patient') : // If user type is patient 
      ?>
        <li><a href="index.php" <?php echo isCurrentPage('index') ? 'class="current"' : ''; ?>><i class="fas fa-home"></i> </a></li>
        <li><a href="lab-tests.php" <?php echo isCurrentPage('lab-tests') ? 'class="current"' : ''; ?>><i class="fas fa-flask"></i></a></li>
        <!-- Add more icons to other links as needed -->
        <li><a href="patient-history.php" <?php echo isCurrentPage('history') ? 'class="current"' : ''; ?>><i class="fas fa-history"></i> </a></li>
        <li><span style="color: white;font-weight: bold;margin-right: 10px;text-transform: capitalize;"> <?= $_SESSION['username'] ?></span><a href="logout.php"><i class="fas fa-sign-out-alt"></i> </a></li>
      <?php else : // If user type is not patient 
      ?>
        <li><a href="dashboard.php" <?php echo isCurrentPage('dashboard') ? 'class="current"' : ''; ?>><i class="fas fa-tachometer-alt"></i></a></li>
        <li><span style="color: white;font-weight: bold;margin-right: 10px;text-transform: capitalize;"> <?= $_SESSION['username'] ?></span><a href="logout.php"><i class="fas fa-sign-out-alt"></i> </a></li>
      <?php endif; ?>
    <?php else : // If user is not logged in 
    ?>
      <li><a href="index.php" <?php echo isCurrentPage('index') ? 'class="current"' : ''; ?>><i class="fas fa-home"></i> </a></li>
      <li><a href="login-patient.php" <?php echo isCurrentPage('login') ? 'class="current"' : ''; ?>><i class="fas fa-user"></i> </a></li>
      <li><a href="login-manager.php" <?php echo isCurrentPage('login') ? 'class="current"' : ''; ?>><i class="fas fa-user-shield"></i> </a></li>
      <li><a href="lab-tests.php" <?php echo isCurrentPage('lab-tests') ? 'class="current"' : ''; ?>><i class="fas fa-flask"></i></a></li>
    <?php endif; ?>
  </ul>

</nav>
<?php
// Function to check if the provided page name is the same as the current page URL
function isCurrentPage($pageName)
{
  $currentPage = basename($_SERVER['PHP_SELF']);

  // Use strict comparison to check if the substring exists in the URL
  return strpos($currentPage, $pageName) !== false;
}
?>