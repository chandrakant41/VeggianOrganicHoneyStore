<?php
include 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit(); // Make sure to exit after redirecting
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit(); // Make sure to exit after redirecting
}

// Function to generate PDF report
function generateOrderReport($orderDetails)
{
    require('fpdf186/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->Cell(0, 10, 'Veggian Organic Honey', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Order Details', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetFillColor(200, 200, 200);

    $pdf->Cell(50, 10, 'Field', 1, 0, 'C', true);
    $pdf->Cell(140, 10, 'Value', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 12);

    foreach ($orderDetails as $field => $value) {
        $pdf->Cell(50, 10, $field, 1, 0, 'L');
        $pdf->Cell(140, 10, $value, 1, 1, 'L');
    }

    $pdfFileName = 'order_details_' . $orderDetails['Order ID'] . '.pdf';
    $pdf->Output($pdfFileName, 'F');

    return $pdfFileName;
}

// Handle filter selection
$filterCondition = '';

if (isset($_GET['filter'])) {
    switch ($_GET['filter']) {
        case 'last_week':
            $filterCondition = "AND STR_TO_DATE(placed_on, '%d-%M-%Y') >= CURDATE() - INTERVAL 1 WEEK";
            break;
        case 'last_month':
            $filterCondition = "AND STR_TO_DATE(placed_on, '%d-%M-%Y') >= CURDATE() - INTERVAL 1 MONTH";
            break;
        case '2023':
            $filterCondition = "AND STR_TO_DATE(placed_on, '%d-%M-%Y') BETWEEN '2023-01-01' AND '2023-12-31'";
            break;
        case '2024':
            $filterCondition = "AND STR_TO_DATE(placed_on, '%d-%M-%Y') BETWEEN '2024-01-01' AND '2024-12-31'";
            break;
        case 'older':
            $filterCondition = "AND STR_TO_DATE(placed_on, '%d-%M-%Y') < '2023-01-01'";
            break;
        default:
            $filterCondition = '';
            break;
    }
}

$sql = "SELECT * FROM `order` WHERE user_id='$user_id' $filterCondition";
$select_orders = mysqli_query($conn, $sql) or die(mysqli_error($conn));
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
            <h1>order</h1>
            <p>Welcome to our organic honey haven! Explore nature's golden nectar in its purest form. Order now and savor the sweetness of authenticity.</p>
            <a href="index.php">home</a><span>/ order</span>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-container">
        <form method="GET" action="">
            <select name="filter">
                <option value="">All</option>
                <option value="last_week">Last Week</option>
                <option value="last_month">Last Month</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="older">Older</option>
            </select>
            <button type="submit">Apply</button>
        </form>
    </div>

    <!-- Order page -->
    <div class="order-section">
        <div class="box-container">
            <?php if (mysqli_num_rows($select_orders) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {?>
                    <div class="box">
                        <p>placed on: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                        <p>name: <span><?php echo $fetch_orders['name']; ?></span></p>
                        <p>number : <span><?php echo $fetch_orders['number']; ?></p>
                        <p>email : <span><?php echo $fetch_orders['email']; ?></p>
                        <p>address : <span><?php echo $fetch_orders['address']; ?></p>
                        <p>payment method : <span><?php echo $fetch_orders['method']; ?></p>
                        <p>your order : <span><?php echo $fetch_orders['total_products']; ?></p>
                        <p>total price : <span><?php echo $fetch_orders['total_price']; ?></p>
                        <p>payment status: <span><?php echo $fetch_orders['payment_status']; ?></span></p>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                            <button type="submit" name="generate_report" class="btn">Generate Report</button>
                        </form>
                    </div>
            <?php }
            } else {
                echo '<div class="empty"><p>No orders found!</p></div>';
            }
            ?>
        </div>
    </div>
    <div class="line2"></div>
    <?php include 'footer.php'?>
</body>
</html>
