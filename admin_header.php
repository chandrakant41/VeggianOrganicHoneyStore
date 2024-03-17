<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Document</title>
</head>
<body>
    <header class="header">
        <div class="flex">
            <a href="admin_pannel.php" class="logo"><img src="img/logo-png.png"></a>
            <nav class="navbar">
            <a href="admin_pannel.php">home</a>
            <a href="admin_product.php">products</a>
            <a href="admin_order.php">orders</a>
            <a href="admin_user.php">users</a>
            <a href="admin_message.php">messages</a>
            </nav>
            <div class="icons">
                <i class="bi bi-person" id="user-btn"></i>
                <i class="bi bi-list" id="menu-btn"></i>
            </div>
            <div class="user-box">
                <p>admin name : <span><?php echo $_SESSION['admin_name']; ?></span></p>
                <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
                <form method="post">
                    <button type="submit" name="logout" class="logout-btn">log out</button>
                </form>
            </div>
        </div>
    </header>   
    <div class="banner">
        <div class="details">
            <h1>admin dashboard</h1>
            <p>Manage your hive of information with ease. From tracking sales and inventory to nurturing customer relationships, let's get buzzing !.</p>
        </div>
    </div> 
    <div class="line2"></div>
</body>
</html>