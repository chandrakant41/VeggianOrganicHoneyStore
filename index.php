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
    // adding product in wishlist
    if (isset($_POST['add_to_wishlist'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        

        $wishlist_number = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        $cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($wishlist_number)>0) {
            $message[] = 'product already exist in wishlist';
        }elseif (mysqli_num_rows($cart_number)>0) {
            $message[] = 'product already exist in cart';
        }else {
            mysqli_query($conn, "INSERT INTO `wishlist`(`user_id`,`pid`,`name`,`price`,`image`) VALUES('$user_id','$product_id','$product_name','$product_price','$product_image')");
            $message[] = 'product successfuly added in your wishlist';
        }
    }

     // adding product in cart
     if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];
        
        $cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($cart_number)>0) {
            $message[] = 'product already exist in cart';
        }else {
            mysqli_query($conn, "INSERT INTO `cart`(`user_id`,`pid`,`name`,`price`,`quantity`,`image`) VALUES('$user_id','$product_id','$product_name','$product_price','$product_quantity','$product_image')");
            $message[] = 'product successfuly added in your cart';
        }
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
    <!---------------- bootstrap css link   --------------------->

    <!---------------- slick slider link    --------------------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">
    <!---------------- defualt css link     --------------------->
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <?php include 'header.php'?>
     <!-------------------------home img container-------------------------------------------------->
     <div class="container-fluid">
        <div class="hero-slider">
            <div class="slider-item">
                <img src="img/slider1.png"> <!--hero slid img 1-->
                <div class="slider-caption">
                    <span>Test The Quality</span>
                    <h1>Organic Premium <br>Honey</h1>
                    <p>Enjoy sweet, aromatic honey made by hardworking people of <br> ecologically clean raw materials in the most pure environment!</p>
                    <a href="shop.php" class="btn">shop now</a>
                </div>
            </div>
            <div class="slider-item">
                <img src="img/hero-slider2.png"> <!--hero slid img 2-->
                <div class="slider-caption">
                    <span>Test The Quality</span>
                    <h1>Organic Premium <br>Honey</h1>
                    <p>Enjoy sweet, aromatic honey made by hardworking people of <br> ecologically clean raw materials in the most pure environment!</p>
                    <a href="shop.php" class="btn">shop now</a>
                </div>
            </div>
        </div>
        <div class="controls">
            <i class="bi bi-chevron-left prev"></i>
            <i class="bi bi-chevron-right next"></i>
        </div>
     </div>
     <div class="line"></div>
     <div class="services">
        <div class="row">
            <div class="box">
                <h1><i class="bi bi-gear-fill"></i></h1><!--settings img-->
                <div>
                    <h1>Free Shipping</h1>
                    <p>Shop More,Pay Less - Free Shipping Today, Every Day,Your Way.Unbeatable Deails Await. </p>
                </div>
            </div>
            <div class="box">
                <h1><i class="bi bi-cash-coin"></i></h1><!--dollers img-->
                <div>
                    <h1>Money Back & Guarantee</h1>
                    <p>Shop Risk Free With Our 100% Money Back Guarantee - Your Satisfaction, Our Commitment, Your Peace Of Mind.</p>
                </div>
            </div>
            <div class="box">
                <h1><i class="bi bi-truck"></i></h1><!--delivery img-->
                <div>
                    <h1>Online Suppoet 24/7</h1>
                    <p>Unwavering Assistance Anytime, Anywhere. 24/7 Online Support, Your Solutions, Around the Clock. We're Here Always.</p>
                </div>
            </div>
        </div>
     </div>
     <div class="line2"></div>
     <div class="story">
        <div class="row">
            <div class="box">
                <span>OUR STORY</span>
                <h1>Production of natural honey since 1900</h1>
                <p>Our story is one of time-honored tradition, sustainable practices, and a deep commitment to providing the finest quality honey to our valued customers. Since 1900, our family-owned vegetarian organic honey store has been a labor of love, rooted in a passion for nature's sweetest gift - honey.
                It all began with our great-grandparents, who had a profound appreciation for the natural world and the bounty it had to offer. They started with a handful of beehives and a vision to create a harmonious balance between humans and nature. Over the years, this vision evolved into a legacy of organic beekeeping, sustainable farming, and a dedication to the vegetarian lifestyle.
                Today, as we stand on the brink of a new era, we continue to provide you with the purest, most flavorful vegetarian organic honey, a testament to the values and traditions that have been at the heart of our family for over a century. We invite you to savor the sweet nectar of our labor, knowing that it is a product of enduring passion, respect for the environment, and a timeless dedication to quality. Thank you for being a part of our story, and for choosing our honey to sweeten your life.</p>
                <a href="shop.php" class="btn">shop now</a>
            </div>
            <div class="box">
                <img src="img/hero-slider2.png">
            </div>
        </div>
     </div>
     <div class="line3"></div>
     <!-- testimonial -->
     <div class="line4"></div>
     <div class="testimonial-fluid">
        <h1 class="title">What Our Customer Say's</h1>
        <div class="testimonial-slider">
            <div class="testimonial-item">
                <img src="img/team2.jpg" alt="">
                <div class="testimonial-caption">
                    <span>Test The Quality</span>
                    <h1>Organic Premium Honey</h1>
                    <p>The history and tradition behind your honey is amazing. It's like a taste of the past, and I can tell that there's a lot of care that goes into every jar.</p>
                </div>
            </div>
            <div class="testimonial-item">
                <img src="img/team2.jpg" alt="">
                <div class="testimonial-caption">
                    <span>Test The Quality</span>
                    <h1>Organic Premium Honey</h1>
                    <p>I've been buying honey from your store for years, and I can't imagine going anywhere else. The quality is consistently top-notch, and I appreciate your commitment to sustainable and vegetarian-friendly practices.</p>
                </div>
            </div>
            <div class="testimonial-item">
                <img src="img/team3.jpg" alt="">
                <div class="testimonial-caption">
                    <span>Test The Quality</span>
                    <h1>Organic Premium Honey</h1>
                    <p>Your honey has become a staple in our household. It's not just a sweetener; it's a symbol of our commitment to a healthy and sustainable lifestyle.</p>
                </div>
            </div>
        </div>
        <div class="controls">
            <i class="bi bi-chevron-left prev1"></i>
            <i class="bi bi-chevron-right next1"></i>
        </div>
     </div>
     <div class="line"></div>
     <!---------- discover section -------------->
    <div class="line2"></div>
    <div class="discover">
        <div class="detail">
            <h1 class="title">Organic Honey Be Healthy</h1>
            <span>Buy Now And Save 30% Off!</span>
            <p>Organic honey is a type of honey that is produced using organic beekeeping and farming practices. It is prized for its purity, quality, and the environmentally responsible methods used in its production. Here are some key aspects of organic honey:
            Organic Beekeeping: Organic honey is produced by beekeepers who adhere to strict organic beekeeping practices. This means that the bees are allowed to forage in pesticide-free and non-GMO environments. The beehives are also managed without the use of synthetic chemicals or antibiotics. Beekeepers prioritize the health and well-being of their bee colonies.
            Natural Foraging Areas: The bees that produce organic honey forage in areas where the plants and flowers are grown without the use of chemical pesticides, herbicides, or fertilizers. This ensures that the nectar and pollen collected by the bees are free from harmful residues.</p>
            <a href="shop.php" class="btn">discover now</a>
        </div>
        <div class="img-box">
            <img src="img/hero-slider2.png">
        </div>
    </div>
    <div class="line3"></div>
    <?php include 'homeshop.php'; ?>
    <div class="line2"></div>
    <div class="newslatter">
        <h1 class="title">Join Our To Newslatter</h1>
        <p>Get 15% off your next order. Be the first to learn about promotions special events, new arrivals and more.</p>
        <input type="text" name="" placeholder="tour Email Address...">
        <button>subscribe now</button>
    </div>
    <div class="line3"></div>
    
    <?php include 'footer.php'; ?>
    <!-------------------------slick slider link--------------------------------------------------->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <script type="text/javascript">
        <?php include 'script2.js' ?>
    </script>
    <!--<script type="text/javascript">
        $('.hero-slider').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true
        });
    </script>-->
</body>
</html>