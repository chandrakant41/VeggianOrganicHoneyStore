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
    
   
    if (isset($_POST['add_to_wishlist'])) {
        $user_id = $_POST['user_id'];
        $product_id = $_POST['product_id'];
        $product_brand_name = $_POST['brand_name'];
        $product_name = $_POST['product_name'];
        $product_net_weight = $_POST['net_weight'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['image'];
        

        $wishlist_number = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        $cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($wishlist_number)>0) {
            $message[] = 'product already exist in wishlist';
        
        }else {
            mysqli_query($conn, "INSERT INTO `wishlist`(`user_id`,`pid`,`brand_name`,`name`,`net_weight`,`price`,`image`) VALUES('$user_id','$product_id','$product_brand_name','$product_name','$product_net_weight','$product_price','$product_image')");
            $message[] = 'product successfuly added in your wishlist';
        }
    }

    
    if (isset($_POST['add_to_cart'])) {
        // Extract product details from POST data
        $product_id = $_POST['product_id'];
        $product_brand_name = $_POST['product_brand_name'];
        $product_name = $_POST['product_name'];
        $product_net_weight = $_POST['product_net_weight'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = 1;
        
        // Check if the product already exists in the cart
        $cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name'") or die('query failed');
        if (mysqli_num_rows($cart_number) > 0) {
            $message[] = 'Product already exists in cart.';
        } else {
            // Insert the product into the cart
            mysqli_query($conn, "INSERT INTO `cart`(`user_id`, `pid`, `brand_name`, `name`, `net_weight`, `price`, `quantity`, `image`) VALUES ('$user_id','$product_id','$product_brand_name','$product_name','$product_net_weight','$product_price','$product_quantity','$product_image')");
    
            // Remove the product from the wishlist
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$product_id' AND user_id = '$user_id'") or die('query failed');
    
            $message[] = 'Product successfully added to your cart and removed from wishlist.';
        }
    }
   
    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        
        mysqli_query($conn, "DELETE FROM `wishlist` WHERE id = '$delete_id'") or die('query failed');
    
        header('location:wishlist.php');
       }
    
    if (isset($_GET['delete_all'])) {
        
        mysqli_query($conn, "DELETE FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');
    
        header('location:wishlist.php');
       } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>veggen - home page</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="main.css">
</head>
<body>
    <?php include 'header.php'?>
    <div class="banner">
        <div class="details">
            <h1>my wishlist</h1>
            <p>Discover your sweetest desires, Explore our organic honey wishlist - a hive of pure, golden dreams. Buzz in and savor!.</p>
            <a href="index.php">home</a><span>/ wishlist</span>
        </div>
    </div> 
    <div class="line3"></div>
    <div class="line4"></div>
     <!--------------------- wishlist --------------------------------------->
    <section class="shop">
       <h1 class="title">products added in wishlist</h1>

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
        
        <div class="box-container">
            <?php
                $grand_total=0;
                $select_wishlist = mysqli_query($conn, "SELECT * FROM  `wishlist`") or die('query failed');
                if (mysqli_num_rows($select_wishlist)>0) {
                    while($fetch_wishlist =  mysqli_fetch_assoc($select_wishlist)){
                
            ?>
            <form method="post" class="box">
                 <img src="img/<?php echo $fetch_wishlist['image']; ?>">       
                 <div class="brand_name"><h4><?php echo $fetch_wishlist['brand_name']; ?></h4></div>
                 <div class="name"><?php echo $fetch_wishlist['name']; ?></div>
                 <div class="net_weight"><?php echo $fetch_wishlist['net_weight']; ?>g</div>
                 <div class="price">Rs<?php echo $fetch_wishlist['price']; ?></div>
                 <input type="hidden" name="product_id" value="<?php echo $fetch_wishlist['pid']; ?>">
                 <input type="hidden" name="product_brand_name" value="<?php echo $fetch_wishlist['brand_name'];?>">
                 <input type="hidden" name="product_name" value="<?php echo $fetch_wishlist['name']; ?>">
                 <input type="hidden" name="product_net_weight" value="<?php echo $fetch_wishlist['net_weight'];?>">
                 <input type="hidden" name="product_price" value="<?php echo $fetch_wishlist['price']; ?>">
                 <input type="hidden" name="product_image" value="<?php echo $fetch_wishlist['image']; ?>">
                 <div class="icon">
                    <a href="view_page.php?pid=<?php echo $fetch_wishlist['id']; ?>" class="bi bi-eye-fill"></a>
                    <a href="wishlist.php?delete=<?php echo $fetch_wishlist['id']; ?>" class="bi bi-x" onclick="return confirm('do you want to delete this product from your wishlist')"></a>
                    <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
                 </div>
            </form>
            
            <?php
                    $grand_total+=$fetch_wishlist['price'];
                    }
                }else {
                    echo '<p class="empty">no products added yet!</p>';
                }
            ?>
        </div>
        <div class="wishlist_total">
                <p>total amount payable : <span>Rs<?php echo $grand_total; ?>/- </span></p>
                <a href="shop.php" class="btn">continue shoping</a>
                <a href="wishlist.php?delete_all" class="btn  <?php echo ($grand_total)?'':'disabled' ?>" onclick="return confirm('do you want to delete all items from your wishlist') "> Delete All</a>
        </div>
    </section>
    <?php include 'footer.php'?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
