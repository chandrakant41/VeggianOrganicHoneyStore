<?php 

include 'connection.php';
session_start();
$admin_id = $_SESSION['admin_name'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit; 
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit; 
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM `order` WHERE id ='$delete_id'") or die('query failed');
    $message[] = 'Order removed';
    header('location:admin_order.php');
    exit; // 
}

if (isset($_POST['update_payment'])) {
    $order_id = $_POST['order_id'];
    $order_payment = $_POST['update_payment'];

    mysqli_query($conn, "UPDATE `order` SET payment_status = '$order_payment' WHERE id='$order_id'") or die('query failed');
}

// Handle filter selection
$filterCondition = '';

if (isset($_GET['filter'])) {
    switch ($_GET['filter']) {
        case 'last_week':
            $filterCondition = "WHERE STR_TO_DATE(placed_on, '%d-%b-%Y') >= CURDATE() - INTERVAL 1 WEEK";
            break;
        case 'last_month':
            $filterCondition = "WHERE STR_TO_DATE(placed_on, '%d-%b-%Y') >= CURDATE() - INTERVAL 1 MONTH";
            break;
        case '2023':
            $filterCondition = "WHERE STR_TO_DATE(placed_on, '%d-%b-%Y') BETWEEN '2023-01-01' AND '2023-12-31'";
            break;
        case '2024':
            $filterCondition = "WHERE STR_TO_DATE(placed_on, '%d-%b-%Y') BETWEEN '2024-01-01' AND '2024-12-31'";
            break;
        case 'older':
            $filterCondition = "WHERE STR_TO_DATE(placed_on, '%d-%b-%Y') < '2023-01-01'";
            break;
        case 'pending':
            $filterCondition = "WHERE payment_status = 'pending'";
            break;
        case 'complete':
            $filterCondition = "WHERE payment_status = 'completes'";
            break;    
        default:
            $filterCondition = '';
            break;
    }
}

$sql = "SELECT * FROM `order` $filterCondition";


// Execute the SQL query
$select_orders = mysqli_query($conn, $sql);

if (!$select_orders) {
    die(mysqli_error($conn)); 
}

?>

<!-- ------------------------------------------------------------------------------- -->
<style type="text/css">
    <?php include 'style.css'; ?>
</style>
<!-- ------------------------------------------------------------------------------- -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Panel</title>
</head>
<body>
    <?php include 'admin_header.php';?>
    <?php
        if (isset($message)) {
            foreach ($message as $message){
                echo '
                    <div class="message">
                        <span> '.$message.' </span>
                        <i class="bi bi-x-circle" Onclick ="this.parentElement.remove()"></i> 
                    </div>
                ';
            }
        }
    ?>
    <div class="filter-container">
        <form method="GET" action="">
            <select name="filter">
                <option value="">All</option>
                <option value="last_week">Last Week</option>
                <option value="last_month">Last Month</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="older">Older</option>
                <option value="pending">Pending</option>
                <option value="complete">Complete</option>
            </select>
            <button type="submit">Apply</button>
        </form>
    </div>
<div class="line2"></div>
    <section class="order-container">
        <h1 class="title">Total Orders Placed</h1>
        
        <div class="box-container">
            <?php 
            if (mysqli_num_rows($select_orders) > 0) {
                while($fetch_orders = mysqli_fetch_assoc($select_orders)){
            ?>
            <div class="box">
                <p>User Name: <span><?php echo $fetch_orders['name'];?></span></p>
                <p>User ID: <span><?php echo $fetch_orders['user_id'];?></span></p>
                <p>Placed On: <span><?php echo $fetch_orders['placed_on'];?></span></p>
                <p>Number : <span><?php echo $fetch_orders['number'];?></p>
                <p>Email : <span><?php echo $fetch_orders['email'];?></p>
                <p>Total Price : <span><?php echo $fetch_orders['total_price'];?></p>
                <p>Method : <span><?php echo $fetch_orders['method'];?></p>
                <p>Address : <span><?php echo $fetch_orders['address'];?></p>
                <p>Total Product : <span><?php echo $fetch_orders['total_products'];?></p>
                <form method="post">
                    <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                    <select name="update_payment">
                        <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
                        <option value="pendings">Pending</option>
                        <option value="completes">Complete</option>
                    </select>
                
                    <input type="submit" name="update_order" value="Update Order" class="btn">
                    <a href="admin_order.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this message');" class="delete">Delete</a>
                    
                </form>
                 
            </div>
            <?php 
                }
            
            } else {
                echo '<div class="empty"><p>No orders placed yet!</p></div>';
            }
            ?>
        </div>
        
    </section>
    <div class="line"></div>
    

    <script type="text/javascript" src="script.js"></script>
</body>
</html>
