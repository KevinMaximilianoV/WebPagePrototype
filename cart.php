<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "rebibanelserber");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$message = "";

// Process remove from cart
if(isset($_GET['remove'])) {
    $cart_id = (int)$_GET['remove'];
    
    $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_id, $user_id);
    
    if($stmt->execute()) {
        $message = "Item removed from cart";
    } else {
        $message = "Error removing item: " . $conn->error;
    }
}

// Process update quantity
if(isset($_POST['update_cart'])) {
    foreach($_POST['quantity'] as $cart_id => $quantity) {
        $cart_id = (int)$cart_id;
        $quantity = (int)$quantity;
        
        if($quantity > 0) {
            $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
            $stmt->execute();
        }
    }
    
    $message = "Cart updated successfully";
}

// Process checkout
if(isset($_POST['checkout'])) {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Calculate total amount
        $total_amount = 0;
        
        $sql = "SELECT c.id, p.id as product_id, p.name, p.price, c.quantity 
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            // Calculate total
            while($row = $result->fetch_assoc()) {
                $total_amount += $row['price'] * $row['quantity'];
            }
            
            
            // Create order
            $sql = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("id", $user_id, $total_amount);
            $stmt->execute();
            
            $order_id = $conn->insert_id;
            
            // Reset result pointer
            $result->data_seek(0);
            
            // Add order items
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            while($row = $result->fetch_assoc()) {
                $stmt->bind_param("iiid", $order_id, $row['product_id'], $row['quantity'], $row['price']);
                $stmt->execute();
            }
            $sql = "UPDATE products p 
            JOIN order_items oi ON p.id = oi.product_id 
            SET p.stock = p.stock - oi.quantity 
            WHERE oi.order_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            
            // Clear cart
            $sql = "DELETE FROM cart WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            
            // Commit transaction
            $conn->commit();
            
            $message = "Order placed successfully! Thank you for your purchase.";
        } else {
            $message = "Your cart is empty";
        }
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        $message = "Error processing order: " . $e->getMessage();
    }
}

// Get cart items
$sql = "SELECT c.id, p.id as product_id, p.name, p.price, p.image_url, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - PC Hardware Hub</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="Login.css">
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">PC Hardware Hub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="cart.php" class="btn btn-outline-light me-2 active"><i class="fas fa-shopping-cart"></i> Cart</a>
                    <a href="logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    <button id="theme-toggle" class="btn btn-outline-light me-2">
                        <i class="fas fa-moon" id="theme-icon"></i> 
                        <span id="theme-text">Dark Mode</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Shopping Cart -->
    <div class="container mt-4">
        <h2>Your Shopping Cart</h2>
        
        <?php if($message): ?>
            <div class="alert <?php echo (strpos($message, 'Error') !== false) ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if($cart_items->num_rows > 0): ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = $cart_items->fetch_assoc()): ?>
                                <?php $subtotal = $item['price'] * $item['quantity']; ?>
                                <?php $total += $subtotal; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" class="cart-item-img me-3">
                                            <span><?php echo $item['name']; ?></span>
                                        </div>
                                    </td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control" style="width: 70px;">
                                    </td>
                                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                                    <td>
                                        <a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold">$<?php echo number_format($total, 2); ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between mt-3">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <div>
                        <button type="submit" name="update_cart" class="btn btn-info me-2">
                            <i class="fas fa-sync"></i> Update Cart
                        </button>
                        <button type="submit" name="checkout" class="btn btn-success">
                            <i class="fas fa-check"></i> Checkout
                        </button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart mb-3"></i>
                <h3>Your cart is empty</h3>
                <p>Browse our products and discover amazing PC hardware!</p>
                <a href="index.php" class="btn btn-primary mt-3">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer bg-body-tertiary text-body-emphasis py-4 mt-5">
    <div class="container">
        <div class="text-center">
            <p>© 2025 Kevin Maximiliano Vazquez Aguilar - Universidad Politecnica de Santa Rosa Jauregui.</p>
        </div>
    </div>
</footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const htmlRoot = document.documentElement; // Use documentElement instead of getElementById
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const themeText = document.getElementById('theme-text');

    // Check for saved theme preference or default to dark mode
    const savedTheme = localStorage.getItem('theme') || 'dark';
    updateTheme(savedTheme);

    themeToggle.addEventListener('click', function() {
        const currentTheme = htmlRoot.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        updateTheme(newTheme);
    });

    function updateTheme(theme) {
        htmlRoot.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);

        if (theme === 'dark') {
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
            themeText.textContent = 'Dark Mode';
        } else {
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
            themeText.textContent = 'Light Mode';
        }
    }
});
</script>
</body>
</html>