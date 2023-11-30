<style>
  
.footer {

background-color: #E0E8F0;
color: #E8BCB9;
padding: 20px;
text-align: center;
}

.footer .social-links {
margin-bottom: 10px;
}

.footer .social-links a {
color: #001F3F;
text-decoration: none;
font-size: 23px;
font-weight: bold;
margin-right: 14px;
}

.footer .social-links a:hover {
  color: rgba(0, 0, 0, 0.7);

}

.footer-content {
/* padding: 20px; */
max-width: 600px;
margin: 8px auto;
}

.footer-content::after {
content: "";
display: table;
clear: both;
}

.footer-content p {
    color: rgba(0, 0, 0, 0.7);

font-size: 16px;
}

</style>
<footer class="footer">
      <div class="footer-content">
        <div class="social-links">
          <a href="#" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="#" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-linkedin"></i>
          </a>
        </div>
        <p class="copyright">&copy; <?php echo date('Y'); ?> Best Lab.
          All rights reserved.</p>
      </div>
     
    </footer>