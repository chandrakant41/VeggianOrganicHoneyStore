<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location: login.php');
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location: login.php');
}

if (isset($_POST['order-btn'])) {
   
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, $_POST['street'] . ',' . $_POST['city'] . ',' . $_POST['state'] . ',' . $_POST['country'] . ',' . $_POST['pincode']);

    $placed_on = date('d-M-Y');
    $cart_total = 0;
    $cart_product = array(); 

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_product[] = $cart_item['name'] . '(' . $cart_item['quantity'] . ')';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }
    
    $total_products = implode(',', $cart_product);

    
    $sql = "INSERT INTO `order` (`user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`)
            VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')";
    mysqli_query($conn, $sql);

  
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");


    $message[] = 'Order placed successfully';
    header('location: checkout.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>veggen - checkout page</title>
  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <?php include 'header.php'?>
    <div class="banner">
        <div class="details">
            <h1>order</h1>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Vero nostrum dolorem aut aliquam asperiores, totam possimus voluptatibus.</p>
            <a href="index.php">home</a><span>/ order</span>
        </div>
    </div> 
    <div class="line3"></div>
     <!--------------------- order page--------------------------------------->
    <div class="checkout-form">
        <h1 class="title">payment process</h1>
        <?php
            if (isset($message)) {
                foreach($message as $message){
                    echo '
                        <div class="message">
                            <span>'.$message.'</span>
                            <i class="bi bi-x-circle" onclick="this.parentElement.remove()"</i>
                        </div>
                    ';
                }
            }
        ?>
        <div class="display-order">
        <div class="box-container">
       
        <?php
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            $total = 0;
            $grand_total = 0;

            if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                $grand_total = $total += $total_price;
            ?>
        <div class="box">
            <img src="img/<?php echo $fetch_cart['image']; ?>">
            <span>
                <?php
                echo $fetch_cart['name'] . ' (' . $fetch_cart['quantity'] . ')';
                ?>
            </span>
        </div>
        <?php
            }
        }
        ?>

        </div>
            <span class="grand-total">Total Amount payable : Rs <?= $grand_total; ?></span>
           
        </div>
        
        <form method="post" onsubmit="return validateForm()">
        <div class="input-field">
            <label>your name</label>
            <input type="text" name="name" id="name" placeholder="enter your name" required>
            <span id="nameError" class="error"></span>
        </div>
        <div class="input-field">
            <label>your number</label>
            <input type="text" name="number" id="number" placeholder="enter your number" required>
            <span id="numberError" class="error"></span>
        </div>
        <div class="input-field">
            <label>your email</label>
            <input type="email" name="email" id="email" placeholder="enter your email" required>
            <span id="emailError" class="error"></span>
        </div>
        <div class="input-field">
            <label>select payment method</label>
            <select name="method" id="method" required>
                <option selected disabled>select payment method</option>
                <option value="cash on delivery">cash on delivery</option>
                <option value="paytm">paytm</option>
                <option value="phonepay">phonepay</option>
            </select>
            <span id="methodError" class="error"></span>
        </div>
            <div class="input-field">
                <label>address line 1</label>
                <input type="text" name="street" placeholder="e.g street name" required>
            </div>
            <div class="input-field">
                <label>city</label>
                <input type="text" name="city" placeholder="e.g karad" required>
            </div>
            <div class="input-field">
                <label>state</label>
                <input type="text" name="state" placeholder="e.g maharashtra" required>
            </div>
            <div class="input-field">
                <label>country</label>
                <input type="text" name="country" placeholder="e.g india">
            </div>
   
    <div class="input-field">
        <label>pincode</label>
        <input type="text" name="pincode" id="pincode" placeholder="e.g 415103">
        <span id="pincodeError" class="error"></span>
    </div>
    <input type="submit" name="order-btn" class="btn" value="order now">
</form>



    </div>    
    <?php include 'footer.php'?>
    <script type="text/javascript" src="script.js"></script>
    <script>
function validateForm() {
    var name = document.getElementById('name').value;
    var number = document.getElementById('number').value;
    var email = document.getElementById('email').value;
    var method = document.getElementById('method').value;
    var pincode = document.getElementById('pincode').value;

    var isValid = true;

    //  Name (Should not be empty)
    if (name === '') {
        document.getElementById('nameError').innerHTML = 'Please enter your name.';
        isValid = false;
    } else {
        document.getElementById('nameError').innerHTML = '';
    }

    //  Number (Should be a valid number)
    if (isNaN(number)) {
        document.getElementById('numberError').innerHTML = 'Please enter a valid number.';
        isValid = false;
    } else {
        document.getElementById('numberError').innerHTML = '';
    }

    //  Email (Basic email format check)
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (!emailPattern.test(email)) {
        document.getElementById('emailError').innerHTML = 'Please enter a valid email address.';
        isValid = false;
    } else {
        document.getElementById('emailError').innerHTML = '';
    }

    //  Payment Method (Should not be the default option)
    if (method === 'select payment method') {
        document.getElementById('methodError').innerHTML = 'Please select a payment method.';
        isValid = false;
    } else {
        document.getElementById('methodError').innerHTML = '';
    }

    //  Pincode (Should be a valid 6-digit number)
    var pincodePattern = /^\d{6}$/;
    if (!pincodePattern.test(pincode)) {
        document.getElementById('pincodeError').innerHTML = 'Please enter a valid 6-digit pincode.';
        isValid = false;
    } else {
        document.getElementById('pincodeError').innerHTML = '';
    }

    return isValid;
}
</script>
</body>
</html>
