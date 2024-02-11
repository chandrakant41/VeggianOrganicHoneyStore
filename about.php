<?php 

    include 'connection.php';
    session_start();
    $user_id = $_SESSION['user_id'];

    if (!isset($user_id)) {
       header('location:login.php');
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:login.php');
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>veggen - home page</title>
    <!---------------- bootstrap icon link  --------------------->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!---------------- defualt css link     --------------------->
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <?php include 'header.php'?>
    <div class="banner">
        <div class="details">
            <h1>About Us</h1>
            <p>Welcome to our organic honey store we, discover the pure esswnce of nature with our exquisite organic honey. Savor the sweetness of authenticity</p>
            <a href="index.php">home</a><span>/ about us</span>
        </div>
    </div> 
    
     <!---------------------------- about us -------------------------------------------->
     <div class="line3"></div>
     <div class="about-us">
        <div class="row">
            <div class="box">
                <div class="title">
                    <span>ABOUT OUR ONLINE STORE</span>
                    <h1>Hello, with 5 years of experience</h1>
                </div>
                <p>With five years od dedicated experties, our online honey store has honed the craft of delivering pure, delicious honey to your doorstep. Our journey has been filled with a deep commitment to quality, sustainability, and the joy of sharing natures golden nectar with our valued customers. Over the years we've cultivated strong relationships with beekeepers, ensuring that every jar of honey we offer is a testament to our unwavering dedication to authenticity and excellence. As we continue to grow , our commitment remains unwavering to provide you with the finest, purest honey products while supporting the vital role of bees in our ecosystem. Thank you for being part of our honey loving community, and here's to many more sweet years ahed.</p>
            </div>
            <div class="img-box">
                <img src="img/slider2.png" alt="">
            </div>
        </div>
     </div>
     <div class="line2"></div>
      <!------------------------- about us end ----------------------------------------->
      <!------------------------- features --------------------------------------------->
      <div class="line4"></div>
      <div class="features">
        <div class="title">
            <h1>Complate Customer Ideas</h1>
            <span>best Featuers</span>
        </div>
        <div class="row">
            <div class="box">
                <h1><i class="bi bi-gear-fill"></i></h1>
                <h4>24 X 7</h4>
                <p>online support 27/7</p>
            </div>
            <div class="box">
                <h1><i class="bi bi-cash-coin"></i></h1>
                <h4>Money Back Guarantee</h4>
                <p>100% sucure Payement</p>
            </div>
            <div class="box">
                <h1><i class="bi bi-gift-fill"></i></h1>
                <h4>Special Gift Card</h4>
                <p>Give The Perfect Gift</p>
            </div>
            <div class="box">
                <h1><i class="bi bi-truck"></i></h1>
                <h4>Through-out The Country Shipping</h4>
                <p>On Order Over Rs1000</p>
            </div>
        </div>
      </div>
      <div class="line"></div>
      <!------------------------- features end ----------------------------------------->
      <!------------------------- team section ----------------------------------------->
      <div class="line2"></div>
      <div class="team">
        <div class="title">
            <h1>Our Worable Team</h1>
            <span>best team</span>
        </div>
        <div class="row">
            <div class="box">
                <div class="img-box">
                    <img src="img/team1.jpg" alt="">
                </div>
                <div class="detail">
                    <span>Website Manager</span>
                    <h4>Chandrakant Virkar</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                        <i class="bi bi-whatsapp"></i>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/team2.jpg" alt="">
                </div>
                <div class="detail">
                    <span>Finanace Manager</span>
                    <h4>Akash Pol</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                        <i class="bi bi-whatsapp"></i>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/team3.jpg" alt="">
                </div>
                <div class="detail">
                    <span>Analyst</span>
                    <h4>Rohan Phike</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                        <i class="bi bi-whatsapp"></i>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="line3"></div>
    <div class="line2"></div>
    <div class="ideas">
        <div class="title">
            <h1>We And Our Clients Are Happy To CoOperate With Our Company</h1>
            <span>our features</span>
        </div>
        <div class="row">
            <div class="box">
                <i class="bi bi-stack"></i>
                <div class="detail">
                    <h2>What We Really Do</h2>
                    <p> At Veggian Organic Honey Store, we are committed to providing you with the finest organic products that promote a healthier lifestyle for you and a more sustainable future for our planet. We believe in the power of natural, chemical-free, and locally-sourced goods to nourish both your body and the environment.</p>
                </div>
            </div>
            <div class="box">
                <i class="bi bi-grid-1x2-fill"></i>
                <div class="detail">
                    <h2>History Of Beginning</h2>
                    <p>Our first store, a modest corner shop in KARAD, was the embodiment of that dream. With a limited selection of organic goods and a commitment to supporting local farmers, we slowly began to gain a loyal following. People were drawn to the idea of nourishing their bodies and the planet with pure, chemical-free products.
                    </p>
                </div>
            </div>
            <div class="box">
                <i class="bi bi-tropical-storm"></i>
                <div class="detail">
                    <h2>Our Vision</h2>
                    <p> At Veggian Organic Honey Store, we are driven by a clear and passionate vision: to promote health and sustainability through organic and eco-friendly products. Our mission is to provide you with the highest quality organic goods while contributing to a healthier planet.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="line3"></div>
    <?php include 'footer.php'?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>