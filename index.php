<?php
session_start();
?>
<!DOCTYPE html>
<html id="htmlRoot" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Hardware Hub - Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="indexCSS.css">
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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" class="btn btn-outline-light me-2">
                        <i class="fas fa-moon" id="theme-icon"></i> 
                        <span id="theme-text">Dark Mode</span>
                    </button>
                    <a href="#" id="cart-button" class="btn btn-outline-light me-2"><i class="fas fa-shopping-cart"></i> Cart</a>
                    <?php
                    if(isset($_SESSION['user_id'])) {
                        echo '<a href="logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>';
                    } else {
                        echo '<a href="login.php" class="btn btn-outline-light me-2"><i class="fas fa-sign-in-alt"></i> Login</a>';
                        echo '<a href="signup.php" class="btn btn-outline-light"><i class="fas fa-user-plus"></i> Sign Up</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Carousel -->
    <div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <!-- Indicators/dots -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
        </div>

        <!-- The slideshow/carousel -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://imgs.search.brave.com/7mcVKtyDWhSdefszZaylRwFKCAAMR7ygFroDc5S-VO0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9vY2Vs/b3QuY29tLm14L3dw/LWNvbnRlbnQvdXBs/b2Fkcy8yMDIzLzEy/L0NTLTg3Ni0zLnBu/Zw" alt="High-end Graphics Cards" class="d-block w-100 carousel-img">
                <div class="carousel-caption">
                    <h3 class="theme-heading">All of your hardware, in one place</h3>
                    <p class="theme-text">Find the perfect parts for your PC build</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://i.imgur.com/SmC688J.png" alt="Gaming Peripherals" class="d-block w-100 carousel-img">
                <div class="carousel-caption">
                    <h3 class="theme-heading">Top notch equipment</h3>
                    <p class="theme-text">Quality products from trusted brands</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://i.imgur.com/mrJh4qe.png" alt="PC Building Supplies" class="d-block w-100 carousel-img">
                <div class="carousel-caption">
                    <h3 class="theme-heading">At the best price you could've asked</h3>
                    <p class="theme-text">Affordable options for every budget</p>
                </div>
            </div>
        </div>

        <!-- Left and right controls/icons -->
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Featured Products Section -->
    <!-- Featured Products Section -->
<div class="container mb-5">
    <h2 class="text-center mb-4">Featured Products</h2>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "rebibanelserber");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch products with stock
        $sql = "SELECT id, name, price, image_url, stock FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($product = $result->fetch_assoc()) {
        ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?php echo $product['image_url']; ?>" class="card-img-top p-3" alt="<?php echo $product['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text">Stock: <?php echo $product['stock']; ?> units</p>
                        <p class="card-text fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                        <button class="btn btn-primary w-100 add-to-cart" data-id="<?php echo $product['id']; ?>"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php 
            }
        }
        $conn->close();
        ?>
    </div>
</div>


    <!-- Footer -->
    <footer class="footer bg-body-tertiary text-body-emphasis py-4 mt-5">
    <div class="container">
        <div class="text-center">
            <p>© 2025 Kevin Maximiliano Vazquez Aguilar - Universidad Politecnica de Santa Rosa Jauregui.</p>
        </div>
    </div>
</footer>

    <!-- Login Check Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                
                <?php if(!isset($_SESSION['user_id'])): ?>
                    // User is not logged in
                    alert('Please login to add items to your cart');
                    window.location.href = 'login.php';
                <?php else: ?>
                    // User is logged in, add to cart via AJAX
                    fetch('add_to_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'product_id=' + productId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            alert('Product added to cart!');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while adding to cart');
                    });
                <?php endif; ?>
            });
        });
    });
    </script>

<!-- Add this script before the closing </body> tag -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartButton = document.getElementById('cart-button');
    
    cartButton.addEventListener('click', function(event) {
        event.preventDefault();
        
        <?php if(isset($_SESSION['user_id'])): ?>
            // User is logged in, redirect to cart page
            window.location.href = 'cart.php';
        <?php else: ?>
            // User is not logged in, show alert and redirect to login
            alert('Please log in to view your cart');
            window.location.href = 'login.php';
        <?php endif; ?>
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const htmlRoot = document.getElementById('htmlRoot');
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