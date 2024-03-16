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
    $product_brand_name = $_POST['product_brand_name'];
    $product_name = $_POST['product_name'];
    $product_net_weight = $_POST['product_net_weight'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    $wishlist_number = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name'") or die('query failed');
   
    if (mysqli_num_rows($wishlist_number) > 0) {
        $message[] = 'product already exists in wishlist';
    } else {
        mysqli_query($conn, "INSERT INTO `wishlist`(`user_id`,`pid`,`brand_name`,`name`,`net_weight`,`price`,`image`) VALUES('$user_id','$product_id','$product_brand_name','$product_name','$product_net_weight','$product_price','$product_image')");
        $message[] = 'product successfully added to your wishlist';
    }
}

// adding product in cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_brand_name = $_POST['product_brand_name'];
    $product_name = $_POST['product_name'];
    $product_net_weight = $_POST['product_net_weight'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
    
    $cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name'") or die('query failed');
    if (mysqli_num_rows($cart_number) > 0) {
        $message[] = 'product already exists in cart';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(`user_id`,`pid`,`brand_name`,`name`,`net_weight`,`price`,`quantity`,`image`) VALUES('$user_id','$product_id','$product_brand_name','$product_name','$product_net_weight','$product_price','$product_quantity','$product_image')");
        $message[] = 'product successfully added to your cart';
    }
}


$filter = isset($_GET['filter']) ? $_GET['filter'] : 'none';
$order = '';

switch ($filter) {
    case 'lowest':
        $order = 'ORDER BY CAST(price AS DECIMAL) ASC';
        break;
    case 'highest':
        $order = 'ORDER BY CAST(price AS DECIMAL) DESC';
        break;
    default:
        $order = ''; 
}


if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%$search%' $order") or die('query failed');
} else {
    $select_products = mysqli_query($conn, "SELECT * FROM `products` $order") or die('query failed');
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
            <h1>Our Shop</h1>
            <p>Welcome to our organic honey store, Where nature's sweetness meets purity. Discover the finest selection of honey, harvested with care.</p>
            <a href="index.php">home</a><span>/ about us</span>
        </div>
    </div>
    <div class="line"></div>
   
    <!--------------------------- Shop Section ----------------------------------->
    <section class="shop">
        <h1 class="title">Shop Best Sellers</h1>

        <?php
        if (isset($message)) {
            foreach ($message as $message) {
                echo '
                    <div class="message">
                        <span>' . $message . '</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>
                ';
            }
        }
        ?>
        <div class="search-container">
            <form method="get">
                <input type="text" name="search" placeholder="Search products here...">
                
                <select name="filter">
                    <option value="none">-- Filter --</option>
                    <option value="lowest">Lowest Price</option>
                    <option value="highest">Highest Price</option>
                </select>
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="box-container">
            <?php
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
                    <!-- Update the product box markup to include the out-of-stock message and apply the 'out-of-stock' class -->
            <form method="post" class="box <?php echo ($fetch_products['stock'] == 0) ? 'out-of-stock' : ''; ?>">
                <img src="img/<?php echo $fetch_products['image']; ?>">       
                <div class="price">Rs<?php echo $fetch_products['price']; ?></div>
                <div class="brand_name"><h4><?php echo $fetch_products['brand_name'];?></h4></div>
                <div class="name"><?php echo $fetch_products['name']; ?></div>
                <div class="net_weight"><h4><?php echo $fetch_products['net_weight'];?>g</h4></div>
                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                <input type="hidden" name="product_brand_name" value="<?php echo $fetch_products['brand_name'];?>">
                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_net_weight" value="<?php echo $fetch_products['net_weight'];?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_quantity" value="1" min="1">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <?php if ($fetch_products['stock'] == 0): ?>
                    <div class="out-of-stock-message">Out of Stock</div> <!-- Display out-of-stock message -->
                <?php endif; ?>
                <div class="icon">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="bi bi-eye-fill"></a>
                <?php if ($fetch_products['stock'] > 0): ?>
                    <button type="submit" name="add_to_wishlist" class="bi bi-heart"></button>
                    <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
                <?php else: ?>
                    <button type="submit" name="add_to_wishlist" class="bi bi-heart" disabled></button>
                    <button type="submit" name="add_to_cart" class="bi bi-cart" disabled></button>
                <?php endif; ?>
                </div>
            </form>
            <?php
                }
            } else {
                echo '<p class="empty">No products found!</p>';
            }
            ?>
        </div>
    </section>
    <?php include 'footer.php'?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>