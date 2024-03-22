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

// Function to generate PDF report for a product
function generateProductReport($productDetails)
{
    require('fpdf186/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 0, 0);

    // Set header background color
    $pdf->SetFillColor(150, 150, 255); // Light blue color for the header background

    // Add header with store name, date, and time
    $pdf->Cell(0, 10, 'Veggian Organic Honey Store', 0, 1, 'C', true); // Use true to fill the cell with the background color
    $pdf->SetFont('Arial', '', 12); // Set font size for date and time
    $pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'R');
    $pdf->Cell(0, 10, 'Time: ' . date('H:i:s'), 0, 1, 'R');
    $pdf->Ln(10);

    // Add "Product Inventory" line with bold text
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Product Inventory', 0, 1, 'C'); // Bold text for the heading
    $pdf->Ln(5); // Add some space after the heading

    // Set font and cell fill color for the table headers
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetFillColor(200, 200, 200); // Light gray color for the table headers

    // Table headers
    $pdf->Cell(50, 10, 'Field', 1, 0, 'C', true);
    $pdf->Cell(140, 10, 'Product Details', 1, 1, 'C', true);

    // Set font for table content
    $pdf->SetFont('Arial', '', 12);

    // Iterate through product details
    foreach ($productDetails as $field => $value) {
        // Exclude the image field from being added to the PDF
        if ($field !== 'image') {
            $pdf->Cell(50, 10, $field, 1, 0, 'L');
            $pdf->Cell(140, 10, $value, 1, 1, 'L');
        }
    }

    // Output file name
    $pdfFileName = 'product_details_' . $productDetails['id'] . '.pdf';
    $pdf->Output('F', $pdfFileName);

    return $pdfFileName;
}
    //adding products to database
    if (isset($_POST['add_product'])) {
        $product_brand_name = mysqli_real_escape_string($conn, $_POST['brand_name']);
        $product_name = mysqli_real_escape_string($conn, $_POST['name']);
        $product_price = mysqli_real_escape_string($conn, $_POST['price']);
        $product_detail = mysqli_real_escape_string($conn, $_POST['detail']);
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $target = 'C:\xampp\htdocs\img' . basename($_FILES['image']['name']);        
        $product_net_weight = mysqli_real_escape_string($conn, $_POST['net_weight']);
        $product_stock = mysqli_real_escape_string($conn, $_POST['stock']);



        $select_product_name= mysqli_query($conn,"SELECT name FROM `products` WHERE name='$product_name'")or die('query failed');

        if (mysqli_num_rows($select_product_name)>0) {
            $message[]='product name already exist';
        }else{
            $insert_product = mysqli_query($conn, "INSERT INTO `products`(`brand_name`,`name`,`price`,`product_details`,`image`,`net_weight`,`stock`)
            VALUES('$product_brand_name','$product_name','$product_price','$product_detail','$image',' $product_net_weight','$product_stock')") or die('query failed');
            if ($insert_product) {
                if ($image_size > 2000000) {
                    $message[]='image size is to large';
                }else {
                    move_uploaded_file($image_tmp, "$target");
                    
                    $message[]='product added successfully';
                }
            }
        // Product details for generating PDF
    $productDetails = [
       
        'brand_name' => $product_brand_name,
        'name' => $product_name,
        'price' => $product_price,
        'net_weight' => $product_net_weight,
        'stock' => $product_stock
    ];

    // Generate PDF report for the added product
    $pdfFileName = generateProductReport($productDetails);

    // Force download the PDF file
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($pdfFileName) . '"');
    readfile($pdfFileName);
    exit;
        }
    }

 
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

  
if (isset($_POST['update_product'])) {
    $update_id = $_POST['update_id'];
    $update_brand_name = mysqli_real_escape_string($conn, $_POST['update_brand_name']);
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);
    $update_detail = mysqli_real_escape_string($conn, $_POST['update_detail']);
    $update_net_weight = mysqli_real_escape_string($conn, $_POST['update_net_weight']);
    $update_stock = mysqli_real_escape_string($conn, $_POST['update_stock']);

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
        mysqli_query($conn, "UPDATE `products` SET `brand_name`=' $update_brand_name',`name`='$update_name', `price`='$update_price', `product_details`='$update_detail', `image`='$update_image',`net_weight`='$update_net_weight',`stock`='$update_stock' WHERE id = '$update_id'") or die('query failed');
    } else {
        // Update product without changing the image
        mysqli_query($conn, "UPDATE `products` SET `brand_name`=' $update_brand_name', `name`='$update_name', `price`='$update_price', `product_details`='$update_detail',`net_weight`='$update_net_weight',`stock`='$update_stock' WHERE id = '$update_id'") or die('query failed');
    }

    header('location:admin_product.php');
}


function hideUpdateForm() {
    echo "<script>document.getElementById('updateForm').style.display='none'</script>";
}

?>


<style type="text/css">
    <?php
        include 'style.css'
    ?>
</style>
<!-- --------------------------------------------------------------------------------- -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>admin pannel</title>
    <style>
        /* Add custom CSS for adjusting font size */
        .date-time {
            font-size: 12px;
        }
    </style>
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
                <label>brand name</label>
                <input type="text" name="brand_name" required>
            </div>
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
            <div class="input-field">
                <label>net_weight</label>
                <input type="text" name="net_weight" required>
            </div>
            <div class="input-field">
                <label>stock</label>
                <input type="text" name="stock" required>
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
            <div class="box <?php echo ($fetch_products['stock'] == 0) ? 'out-of-stock' : ''; ?>">
                <img src="img/<?php echo $fetch_products['image']; ?>">
                <p>price : Rs<?php echo $fetch_products['price']; ?></p>
                <h4><?php echo $fetch_products['brand_name']; ?></h4>
                <h4><?php echo $fetch_products['name']; ?></h4>
                <p>Net Weight : <?php echo $fetch_products['net_weight']; ?>g</p>
                <p>stock : <?php echo $fetch_products['stock']; ?></p>
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
            <label>Brand Name</label>
            <input type="text" name="update_brand_name" value="<?php echo $fetch_edit['brand_name']; ?>" required>
        </div>
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
        <div class="input-field">
            <label>Net Weight</label>
            <input type="text" name="update_net_weight" value="<?php echo $fetch_edit['net_weight']; ?>" required>
        </div>
        <div class="input-field">
            <label>Stock</label>
            <input type="text" name="update_stock" value="<?php echo $fetch_edit['stock']; ?>" required>
        </div>
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