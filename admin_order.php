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
    exit; 
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
            $filterCondition = "WHERE payment_status = 'complete'";
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

// Set timezone to Indian Standard Time
    date_default_timezone_set('Asia/Kolkata');
// Function to generate PDF report for orders

function generateOrderReport($orderDetails)
{
    require('fpdf186/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    $pdf->SetFillColor(255, 204, 102); // Light honey color for the header background
    $pdf->Cell(0, 10, 'Veggian Organic Honey Store', 0, 1, 'C', true); // Use true to fill the cell with the background color
    $pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'R');
    $pdf->Cell(0, 10, 'Time: ' . date('H:i:s'), 0, 1, 'R');
    $pdf->Cell(0, 10, 'Order Details Report', 0, 1, 'C', true);
    $pdf->Ln(10);

    // Table headers
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(255, 204, 102); // Light gray color for the table headers
    $pdf->Cell(10, 10, 'ID', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'User Name', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'Placed On', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'Total Price', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Method', 1, 0, 'C', true);
    $pdf->Cell(70, 10, 'Total Products', 1, 1, 'C', true); // Increased width

    // Set font for table content
    $pdf->SetFont('Arial', '', 10);

    // Iterate through order details
    foreach ($orderDetails as $order) {
        $pdf->Cell(10, 10, $order['id'], 1, 0, 'L');
        $pdf->Cell(25, 10, $order['name'], 1, 0, 'L');
        $pdf->Cell(25, 10, $order['placed_on'], 1, 0, 'L');
        $pdf->Cell(25, 10, $order['total_price'], 1, 0, 'L');
        $pdf->Cell(30, 10, $order['method'], 1, 0, 'L');
        $pdf->Cell(70, 10, $order['total_products'], 1, 1, 'L');
    }

    // Output file name
    $pdfFileName = 'order_details_report_' . date('Y-m-d_H-i-s') . '.pdf';
    $pdf->Output('F', $pdfFileName);

    return $pdfFileName;
}


// Check if any report generation parameters are set
$generateReport = isset($_GET['apply']);

if ($generateReport) {
    // Generate PDF report for the filtered orders
    $orderDetails = [];
    while ($row = mysqli_fetch_assoc($select_orders)) {
        $orderDetails[] = $row;
    }
    $pdfFileName = generateOrderReport($orderDetails);

    // Force download the PDF file
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($pdfFileName) . '"');
    readfile($pdfFileName);
    exit;
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
            <button name="apply" type="submit">Apply</button>
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
