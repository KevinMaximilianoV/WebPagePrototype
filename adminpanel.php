<?php
session_start();

if (!isset($_SESSION['user_type']) || (int) $_SESSION['user_type'] !== 2) {
    header('Location: index.php');
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'kevin_concurrente', '72seasons', 'rebibanelserber');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$error = '';
$success = '';

$conn->close();
?>
<!DOCTYPE html>
<html data-bs-theme="dark">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TuPC</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="indexCSS.css">
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
    <body>
          <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">TuPC (Admin Panel)</a>
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
                    
                    <?php
                    if (isset($_SESSION['user_id'])) {
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
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    
        <div class="row g-4 justify-content-center w-100">
        
            <div class="col-md-5 col-lg-4">
                <div class="card h-100 shadow-lg border-primary text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-plus-circle fa-4x mb-3 text-primary"></i>
                        <h3 class="card-title">Add Products</h3>
                        <p class="card-text text-muted">Add new products into the database</p>
                        <a href="add.php" class="btn btn-primary btn-lg w-100">Enter</a>
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-lg-4">
                <div class="card h-100 shadow-lg border-warning text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-edit fa-4x mb-3 text-warning"></i>
                        <h3 class="card-title">Modify Products</h3>
                        <p class="card-text text-muted">Modify Prices, Stocks, Descriptions, Category and the Image of the product</p>
                        <a href="modify.php" class="btn btn-warning btn-lg w-100 text-dark">Enter</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
     <!-- Footer -->
    <footer class="footer bg-body-tertiary text-body-emphasis py-4 mt-5">
    <div class="container">
        <div class="text-center">
            <p>© 2026 Megapa - Universidad Politecnica de Santa Rosa Jauregui.</p>
        </div>
    </div>
</footer>
    </body>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const htmlRoot = document.documentElement;
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
            themeIcon.className = 'fas fa-moon';
            themeText.textContent = 'Dark Mode';
            } else {
            themeIcon.className = 'fas fa-sun';
            themeText.textContent = 'Light Mode';
            }
        }
    });
    </script>

</html>