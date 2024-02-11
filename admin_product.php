<?php 

    include 'connection.php';
    session_start();
    $admin_id = $_SESSION['admin_name'];

    if (!isset($admin_id)) {
       header('location:login.php');
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:login.php');
    }
    //adding products to database
    if (isset($_POST['add_product'])) {
        $product_name = mysqli_real_escape_string($conn, $_POST['name']);
        $product_price = mysqli_real_escape_string($conn, $_POST['price']);
        $product_detail = mysqli_real_escape_string($conn, $_POST['detail']);
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $target = 'C:\xampp\htdocs\img' . basename($_FILES['image']['name']);        
       
        $select_product_name= mysqli_query($conn,"SELECT name FROM `products` WHERE name='$product_name'")or die('query failed');

        if (mysqli_num_rows($select_product_name)>0) {
            $message[]='product name already exist';
        }else{
            $insert_product = mysqli_query($conn, "INSERT INTO `products`(`name`,`price`,`product_details`,`image`)
            VALUES('$product_name','$product_price','$product_detail','$image')") or die('query failed');
            if ($insert_product) {
                if ($image_size > 2000000) {
                    $message[]='image size is to large';
                }else {
                    move_uploaded_file($image_tmp, "$target");
                    
                    $message[]='product added successfully';
                }
            }
        }
    }
   // delete products from database
   if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id ='$delete_id'") or die('query failed');

    $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
    unlink('C:\xampp\htdocs\img'.$fetch_delete_image['image']);

    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
    mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');

    header('location:admin_product.php');
   }

   // update product
if (isset($_POST['update_product'])) {
    $update_id = $_POST['update_id'];
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);
    $update_detail = mysqli_real_escape_string($conn, $_POST['update_detail']);

    // Check if a new image is uploaded
    if ($_FILES['update_image']['name'] != '') {
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp = $_FILES['update_image']['tmp_name'];
        $update_target = 'C:\xampp\htdocs\img' . basename($_FILES['update_image']['name']);
        move_uploaded_file($update_image_tmp, "$update_target");

        // Remove existing image
        $select_existing_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id ='$update_id'") or die('query failed');
        $fetch_existing_image = mysqli_fetch_assoc($select_existing_image);
        unlink('C:\xampp\htdocs\img' . $fetch_existing_image['image']);

        // Update product with new image
        mysqli_query($conn, "UPDATE `products` SET `name`='$update_name', `price`='$update_price', `product_details`='$update_detail', `image`='$update_image' WHERE id = '$update_id'") or die('query failed');
    } else {
        // Update product without changing the image
        mysqli_query($conn, "UPDATE `products` SET `name`='$update_name', `price`='$update_price', `product_details`='$update_detail' WHERE id = '$update_id'") or die('query failed');
    }

    header('location:admin_product.php');
}

// hide update form
function hideUpdateForm() {
    echo "<script>document.getElementById('updateForm').style.display='none'</script>";
}

?>


<style type="text/css">
    <?php
        include 'style.css'
    ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--box icon link-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>admin pannel</title>
</head>
<body>
    <?php include 'admin_header.php';?>
    <?php
			if (isset($message)) {
				foreach ($message as $message){
					echo '
						<div class="message">
							<span>'.$message.'</span>
							<i class="bi bi-x-circle" Onclick ="this.parentElement.remove()"></i> 
						</div>
					';
				}
			}
		?>
    <div class="line2"></div>
    <section class="add-products form-container">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="input-field">
                <label>product name</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-field">
                <label>product price</label>
                <input type="text" name="price" required>
            </div>
            <div class="input-field">
                <label>product detail</label>
                <textarea name="detail" required></textarea>
            </div>
            <div class="input-field">
                <label>product image</label>
                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" required>
            </div>
            <input type="submit" name="add_product" value="add product" class="btn">
        </form>
    </section>
    <div class="line3"></div>
    <div class="line4"></div>
    <section class="show_products">
        <div class="box-container">
            <?php 
                $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die ('query failed');
                if (mysqli_num_rows($select_products)>0) {
                    while ($fetch_products = mysqli_fetch_assoc($select_products)) {
             
            ?>
            <div class="box">
                <img src="img/<?php echo $fetch_products['image']; ?>">
                <p>price : Rs<?php echo $fetch_products['price']; ?></p>
                <h4><?php echo $fetch_products['name']; ?></h4>
                <details><?php echo $fetch_products['product_details']; ?></details>
                <a href="admin_product.php?edit=<?php echo $fetch_products['id']; ?>" class="edit">edit</a>
                <a href="admin_product.php?delete=<?php echo $fetch_products['id']; ?>" class="delete" onclick="return confirm('want to delete this product');">delete</a>
            </div>
            <?php
                    }
                }else{
                    echo '
                        <div class="empty">
                            <p>no product added yet !</p>
                        </div>
                    ';    
                }
            ?>
        </div>
    </section>
    <div class="line"></div>
    <section class="update-container" id="updateForm">
    <?php
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit'];
            $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$edit_id'") or die('query failed');
            if (mysqli_num_rows($edit_query) > 0) {
                $fetch_edit = mysqli_fetch_assoc($edit_query);
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_id" value="<?php echo $fetch_edit['id']; ?>">
        <div class="input-field">
            <label>Product Name</label>
            <input type="text" name="update_name" value="<?php echo $fetch_edit['name']; ?>" required>
        </div>
        <div class="input-field">
            <label>Product Price</label>
            <input type="number" name="update_price" min="0" value="<?php echo $fetch_edit['price']; ?>" required>
        </div>
        <div class="input-field">
            <label>Product Details</label>
            <textarea name="update_detail" required><?php echo $fetch_edit['product_details']; ?></textarea>
        </div>
        <div class="input-field">
            <label>Product Image</label>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png, image/webp">
        </div>
        <img src="img/<?php echo $fetch_edit['image']; ?>" class="existing-image">
        <input type="submit" name="update_product" value="Update" class="btn">
        <input type="button" value="Cancel" class="btn" onclick="hideUpdateForm()">
    </form>
    <?php
            }
            echo "<script>document.getElementById('updateForm').style.display='block'</script>";
        }
    ?>
</section>

    <script type="text/javascript" src="script.js" ></script>
</body>
</html>