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
    if (isset($_POST['submit-btn'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name= '$name' AND email='$email' AND $number='$number' AND message='$message'") or die ('query failed');
        if (mysqli_num_rows($select_message)) {
            echo 'message already send';
        }else{
            mysqli_query($conn,"INSERT INTO  `message` (`user_id`,`name`,`email`,`number`,`message`) VALUES('$user_id','$name','$email','$number','$message')") or die('query failed');
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
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <?php include 'header.php'?>
    <div class="banner">
        <div class="details">
            <h1>contact</h1>
            <p>Get in touch with us! We're buzzing with excitement to hear from you. Reach out for sweet conversation and honey inquiries.</p>
            <a href="index.php">home</a><span>/ contact</span>
        </div>
    </div> 
    <div class="line3"></div>
     <!--------------------- contact page--------------------------------------->
     <div class="services">
        <div class="row">
            <div class="box">
                <i class="bi bi-person"></i><!--settings img-->
                <div>
                    <h1>Free Shipping</h1>
                    <p>Shop stress free! Enjoy your online shopping experience even sweeter. </p>
                </div>
            </div>
            <div class="box">
                <i class="bi bi-person"></i><!--dollers img-->
                <div>
                    <h1>Money Back & Guarantee</h1>
                    <p>Shop worry free with our Money Back Guarantee. We stand by our products ,if you're not satisfied, we'll refund your purchase.</p>
                </div>
            </div>
            <div class="box">
                <i class="bi bi-person"></i><!--delivery img-->
                <div>
                    <h1>Online Suppoet 24/7</h1>
                    <p>Shop with confidence! Our 24/7 online support is here to assist you anytime. We're your round the clock shopping companions.</p>
                </div>
            </div>
        </div>
     </div>
     <div class="line4"></div>
     <div class="form-container">
        <h1 class="title">leave a message</h1>
        <form method="post">
            <div class="input-field">
                <label>your name</label>
                <input type="text" name="name">
            </div>
            <div class="input-field">
                <label>your email</label>
                <input type="text" name="email">
            </div>
            <div class="input-field">
                <label>number</label>
                <input type="number" name="number">
            </div>
            <div class="input-field">
                <label>message</label>
                <textarea name="message" cols="10" rows="2"></textarea>
            </div>
            <button type="submit" name="submit-btn">send message</button>
        </form>
     </div>
    <div class="line3"></div>
     <div class="address">
        <h1 class="title">our contact</h1>
        <div class="row">
            <div class="box">
                <i class="bi bi-map-fill"></i>
                <div>
                    <h4>address</h4>
                    <p>54 karad,
                       karad-masur road <br> karad,
                       satara, 415103 
                    </p>
                </div>
            </div>
            <div class="box">
                <i class="bi bi-telephone-fill"></i>
                <div>
                    <h4>phone number</h4>
                    <p>7666989211</p>
                </div>
            </div>
            <div class="box">
                <i class="bi bi-envelope-fill"></i>
                <div>
                    <h4>email</h4>
                    <p>chandrakant@gmail.com</p>
                </div>
            </div>
        </div>
     </div>
    
    <?php include 'footer.php'?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
