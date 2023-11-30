<?php
require_once "utils/protected.php";
require_once "utils/connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/png" href="images/favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <meta name="BestLab" content="Save you Life, take tests now and  live long!">
  <title>Lab-Managment System</title>

</head>
<body class="dark-theme">
  <?php require_once 'components/Nav.php'; ?>

  <div class="container">


    <div class="image-section">
      <div class="image-overlay"></div>
      <img id="image" src="images/lab1.jpeg" alt="Image 1">
      <div class="image-content">
        <h1 class="HOME">Full Service Microbiology and Mycology Laboratory.</h1><br>

        <?php
        if (isset($_SESSION['type']) && $_SESSION['type'] == 'manager') {
       echo "<a href='dashboard.php' class='button primary-button'>Dashboard <i class='fas fa-arrow-circle-right'></i> </a>";
          } else {
            echo "<a href='lab-tests.php' class='explore-button button primary-button'>Explore <i class='fas fa-arrow-circle-right'></i> </a>";
          }
            ?>

      </div>
    </div>




    <script>
      const images = ["lab1.jpeg", "lab2.jpeg", "lab3.jpeg", "lab4.jpeg"];
      let currentIndex = 0;
      const imageElement = document.getElementById("image");

      function rotateImage() {
        currentIndex = (currentIndex + 1) % images.length;
        const nextImage = images[currentIndex];
        imageElement.src = "images/" + nextImage;
      }

      setInterval(rotateImage, 3000);
    </script>

<div class="section section-4">
  <div class="content">
    <h2>Why Choose Us?</h2>
    <ul>
      <li>Streamlined lab operations and increased productivity.</li>
      <li>Enhanced collaboration and communication among lab members.</li>
      <li>Improved data integrity and compliance with regulatory requirements.</li>
      <li>Access to real-time information and analytics for better decision-making.</li>
      <li>Customizable features to adapt to your lab's unique workflows.</li>
    </ul>
  </div>
  <div class="image">
    <img src="images/hp.gif" alt="Image Description">
  </div>
</div>



<div class="services-section" style="background-color: #E0E8F0;">
  <h1>Our Services</h1>
  <br>
  <div class="services-grid">
    <div class="service-box">
      <i class="fa fa-home"></i>
      <h3>Home Sampling</h3>
      <p>Experience the convenience of our Home Sampling service, where our expert professionals come to your doorstep to collect samples for your tests. We ensure a seamless and comfortable process, so you can focus on your well-being.</p>
    </div>
    <div class="service-box">
      <i class="fa fa-medkit"></i>
      <h3>Homecare</h3>
      <p>Discover the comfort of our Homecare services, offering comprehensive healthcare assistance tailored to your unique needs. Our dedicated team of caregivers and medical experts is committed to your health and well-being in the comfort of your home.</p>
    </div>
    <div class="service-box">
      <i class="fa fa-heartbeat"></i>
      <h3>Radiology</h3>
      <p>Trust in the precision of our Radiology services for accurate and detailed diagnoses. Our state-of-the-art technology ensures the highest level of accuracy, providing you with the most reliable results for your healthcare needs.</p>
    </div>
    <div class="service-box">
      <i class="fa fa-capsules"></i>
      <h3>Pharmacy</h3>
      <p>Access a wide range of top-quality medications and healthcare products at our Pharmacy. Our knowledgeable pharmacists are here to offer expert guidance and ensure you have access to the best medications for your health and well-being.</p>
    </div>
    <div class="service-box">
      <i class="fa fa-hospital"></i>
      <h3>Medical Center</h3>
      <p>Experience comprehensive care and specialized treatments at our Medical Center. Our team of experienced healthcare professionals is dedicated to providing you with the highest standard of care and a wide range of medical services to meet your needs.</p>
    </div>
    <div class="service-box">
      <i class="fa fa-tint"></i>
      <h3>Blood Bank</h3>
      <p>Count on us for a consistent supply of safe blood and blood products. Our Blood Bank is committed to ensuring a steady source of high-quality blood products for medical treatments and emergencies, safeguarding lives when it matters most.</p>
    </div>
    <div class="service-box">
      <i class="fa fa-ambulance"></i>
      <h3>Ambulance</h3>
      <p>Rely on our swift and efficient Emergency Response Services. Our dedicated ambulance team is available around the clock to provide immediate medical assistance and transportation during emergencies, ensuring your safety and well-being.</p>
    </div>
    <div class="service-box">
      <i class="fa fa-syringe"></i>
      <h3>Vaccination Center</h3>
      <p>Ensure your preventive care through our Vaccination Center. Our team administers vaccines with expertise, providing you and your loved ones with protection against preventable diseases and promoting overall health and well-being.</p>
    </div>
  </div>
  <br>
</div>

  <br>
</div>



<div class="header">
        <h1 style="padding-top: 100px;">Customer Reviews</h1>
    </div>
  <div class="reviews-container">
    <div class="review">
        <h3>John Doe</h3>
        <div class="rating">
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
        </div>
        <p>
            "I am impressed with the lab management system! It has made our workflow more efficient and organized. The user-friendly interface makes it easy for our team to handle tasks and track progress. Highly recommended!"
        </p>
    </div>

    <div class="review">
        <h3>Jane Smith</h3>
        <div class="rating">
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
        </div>
        <p>
            "The lab management system has been a game-changer for our research lab. It has streamlined our inventory management and reduced the chances of errors. The support team is also very responsive and helpful. Great product!"
        </p>
    </div>

    <div class="review">
        <h3>Michael Johnson</h3>
        <div class="rating">
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
        </div>
        <p>
            "I cannot imagine running our lab without this management system anymore. It has simplified our data management and analysis processes significantly. The customizable features are excellent, and the reports are clear and informative. A must-have tool!"
        </p>
    </div>
</div>








    <?php require_once 'components/footer.php'; ?>
    <button id="scrollToTopBtn" title="Scroll to Top"><i class="fas fa-arrow-up"></i>
    </button>

    <script defer src="js/script.js"></script>
</body>

</html>