<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Morrins Cookies</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    
     
    <nav class="navbar" id="">
        <div class="Logo">Morrins Cookies</div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Popular</a></li>
            <li><a href="#">Menu</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="order.html">Order Here</a></li>
            <li><a href="track_order.php">Track My Order</a></li>
            <li><a href="admin_login.php">Admin Login</a></li>
            
        </ul>
    </nav>
    
<!------------------------------------About setion--------------------------------------->
    <section class="sector">
        <div class ="About">
        
        <div class="about-description">
            <h1 class="about-inner">About Us</h1>
            <p>Since 1965, **Morrins Bakers** has been a symbol of tradition, taste, and timeless craftsmanship. Carrying forward a rich baking heritage into its sixth generation in 2026, our journey is built on a passion for creating delightful experiences through exceptional flavours and quality ingredients. From exquisite cakes and delicate pastries to freshly baked breads, cookies, biscuits, and a wide selection of bakery specialities, every creation reflects our dedication to quality, freshness, and customer satisfaction. In 2026, we proudly embrace a new chapter by bringing our legacy into the digital world, making it easier for our customers to discover our collections and experience the joy of Morrins Bakers. By blending generations of tradition with modern convenience, we continue to create moments worth celebrating, one delicious bite at a time. **Morrins Bakers — A Legacy of Taste, Crafted with Love Since 1965.</p>
            
        </div>
        <div class="about-images">
            <img src="logo.png" alt="">
        </div>

        </div>


    </section>
<!-------------------------------------Popular meals---------------------------------->      

<section class="sector1">

    <div class="meals">
        <h1>Popular meals</h1>

            <div class="cards">
                <div class="card">
                  <img src="chochlate_cake.png" alt="Chochlate cake 1">
                  <h3>chochklate cake</h3>
                   <span class="price">320/kg</span>

                </div>

                <div class="card">
                  <img src="butterschocsh-removebg-preview.png" alt="Chochlate cake 1">
                  <h3>Butter schocsh</h3>
                <span class="price">340/kg</span>

                </div>
                <div class="card">
                  <img src="angel-food-cake-removebg-preview.png" alt="Chochlate cake 1">
                  <h3>angel food cake</h3>
                <span class="price">380/kg</span>

                </div>
                <div class="card">
                  <img src="banana-cake-removebg-preview.png" alt="Chochlate cake 1">
                  <h3>Banana Cake</h3>
                  <span class="price">440/kg</span>

                </div>
                <div class="card">
                  <img src="bundt-cake-removebg-preview.png" alt="Chochlate cake 1">
                  <h3>bundt-cake</h3>
                  <span class="price">240/kg</span>

                 </div>
                <div class="card">
                  <img src="Charlotte-Cake-removebg-preview.png" alt="Chochlate cake 1">
                  <h3>Charlotte-Cake</h3>
                  <span class="price">460/kg</span>

                </div>
            
                <div class="card">
                  <img src="Coffee-cake-removebg-preview.png" alt="Chochlate cake 1">
                  <h3>Coffee-cake</h3>
                  <span class="price">360/kg</span>

                </div>
                <div class="card">
                  <img src="fruit-cake-removebg-preview.png" alt="Chochlate cake 1">
                  <h3>Fruit cacke</h3>
                  <span class="price">520/kg</span>

                </div>
        </div>    
            
    </div>


</section>


<!----------------------------------------------Contact us page------------------------------------------------------------------->
    
<section class="sector2" id="contact" >
    <div class="contact-wrapper">
        <div class="contact">
            <h1>Contact US</h1>
                <p>Hey Beloved Our Customers, Get connect with us to order your customs cakes order </p>
                <p ><strong>Address:Shop number P-20, S B Road, Near Fc college Phase 1 , besides Canbera Bank, Pune.</p>
                <p><strong></strong>Name:Gaurav Garkar</p>
                <p><strong></strong>Email us:gauravgarkar@gmail.com</p>
                <p><strong>Shop time:Mon-satws</p>


        </div> 
            <form class="contact-form" action="save_contact.php" method="post" id="contact_from">

            Enter you name Here<input type="text" name="name" placeholder="Enter your name" required> <br><br>
            Enter Your Email ID here    <input type="email" name="email" placeholder="Enter your email" required><br><br>
            Contact number    <input type="tel" name="phone" placeholder="Add phone number " required><br><br>
            Address    <textarea name="message" rows="5" placeholder="Your Message" required></textarea><br><br>
            <button type="submit">Submit</button>
            </form>
            <p id="formMessage" aria-live="polite">
    <?php if (isset($_GET['success']) && $_GET['success'] === '1'): ?>
    
    <?php endif; ?>
</p>
        </div>
 
    </div>    
</section>
<!-----claude used code for order now tab ill fix that towmmorw -->

<!----------------------------------------------Order Here---------------------------------------------->

<section class="sector3" id="order">
    <div class="order">
        <h1>Order Here</h1>

        <div class="order-box">
            <p>Want a fresh cake or a custom design? Order in three easy steps.</p>

            <ol class="order-steps">
                <li>Choose your cake from our Popular meals.</li>
                <li>Message us on WhatsApp with the cake name, weight and delivery date.</li>
                <li>We confirm the price and prepare it fresh for you.</li>
            </ol>

            <a class="order-btn"
               href="https://wa.me/91XXXXXXXXXX?text=Hello%20Morrins%20Bakers%2C%20I%20would%20like%20to%20place%20an%20order."
               target="_blank">Order on WhatsApp</a>
        </div>
    </div>
</section>

<script src="scripts.js?v=2"></script>
</body>
</html>
