<?php
include 'connection.php';


if (isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];
    $product_brand_name = $_POST['product_brand_name'];
    $product_name = $_POST['product_name'];
    $product_net_weight = $_POST['product_net_weight'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

   
    $wishlist_number = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE pid = '$product_id' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($wishlist_number) > 0) {
        
        $update_query = "UPDATE `wishlist` SET `brand_name`='$product_brand_name', `net_weight`='$product_net_weight', `price`='$product_price', `image`='$product_image' WHERE `pid`='$product_id' AND `user_id`='$user_id'";
        
        if (mysqli_query($conn, $update_query)) {
            $message[] = 'Product updated in your wishlist';
        } else {
            $message[] = 'Error updating product: ' . mysqli_error($conn);
        }
    } else {
        
        $insert_query = "INSERT INTO `wishlist`(`user_id`, `pid`, `brand_name`, `name`, `net_weight`, `price`, `image`) 
                         VALUES ('$user_id', '$product_id', '$product_brand_name', '$product_name', '$product_net_weight', '$product_price', '$product_image')";

        if (mysqli_query($conn, $insert_query)) {
            $message[] = 'Product successfully added to your wishlist';
        } else {
            $message[] = 'Error adding product: ' . mysqli_error($conn);
        }
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
    
    $cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE pid = '$product_id' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($cart_number) > 0) {
       
        $update_query = "UPDATE `cart` SET `brand_name`='$product_brand_name', `net_weight`='$product_net_weight', `price`='$product_price',`quantity`='$product_quantity', `image`='$product_image' WHERE `pid`='$product_id' AND `user_id`='$user_id'";
        
        if (mysqli_query($conn, $update_query)) {
            $message[] = 'Product updated in your cart';
        } else {
            $message[] = 'Error updating product: ' . mysqli_error($conn);
        }
    } else {
        
        $insert_query = "INSERT INTO `cart`(`user_id`, `pid`, `brand_name`, `name`, `net_weight`, `price`,`quantity`, `image`) 
                         VALUES ('$user_id', '$product_id', '$product_brand_name', '$product_name', '$product_net_weight', '$product_price','$product_quantity', '$product_image')";

        if (mysqli_query($conn, $insert_query)) {
            $message[] = 'Product successfully added to your cart';
        } else {
            $message[] = 'Error adding product: ' . mysqli_error($conn);
        }
    }
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
    <section class="popular-brands">
        <h2>POPULAR BRANDS</h2>
        <div class="controls">
            <i class="bi bi-chevron-left left"></i>
            <i class="bi bi-chevron-right right"></i>
        </div>
        <?php
            if (isset($message)) {
                foreach($message as $message){
                    echo '
                        <div class="message">
                            <span>'.$message.'</span>
                            <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                        </div>
                    ';
                }
            }
        ?>
        <div class="popular-brands-content">
            <?php
                $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                if (mysqli_num_rows($select_products) > 0) {
                    while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                ?>
                        <!-- Update the product box markup to include the out-of-stock message and apply the 'out-of-stock' class -->
                <form method="post" class="card <?php echo ($fetch_products['stock'] == 0) ? 'out-of-stock' : ''; ?>">
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

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Slick carousel script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.popular-brands-content').slick({
                lazyLoad: 'ondemand',
                slidesToShow: 4,
                slidesToScroll: 1,
                nextArrow: $('.right'),
                prevArrow: $('.left'),
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    </script>
</body>
</html>