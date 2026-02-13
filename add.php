<?php
session_start();
if (!isset($_SESSION['user_type']) || (int) $_SESSION['user_type'] !== 2) {
    header('Location: index.php');
    exit();
}

$conn = new mysqli('localhost', 'kevin_concurrente', '72seasons', 'rebibanelserber');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productname = $_POST['productname'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['product_imageurl'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    // Validate input
    if (strlen($productname) < 1) {
        $error = "Name can't be empty";
    } elseif (strlen($price) == 0) {
        $error = "Price can't be 0";
    } else {
        // Check if product exists
        $sql = 'SELECT id FROM products WHERE name = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $productname);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Product already exist';
        } else {
            // Insert new product
            $sql = 'INSERT INTO products (name, description, price, image_url, category, stock) VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $productname, $description, $price, $image_url, $category, $stock);

            if ($stmt->execute()) {
                $success = 'Product added successfully';
            } else {
                $error = 'Error: ' . $stmt->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html data-bs-theme="dark">
    <head>
        <style>
            body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
            main {
            flex: 1; 
        }
        </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new product - PC Hardware Hub</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="LoginCSS.css">
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
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '<a href="logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>';
                        echo '<a href="adminpanel.php" class="btn btn-outline-light me-2"><i class="fa-solid fa-code"></i> Admin Panel</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Add Form -->
     <main>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-container">
                <h2 class="text-center mb-4">Add new product</h2>
                
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
            
                <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Name of the product</label>
                        <input type="text" class="form-control" id="productname" name="productname" required>
                    </div>
                    <div class="mb-3">
                        <label for="texr" class="form-label">Description of the Product</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">Price</label>
                        <input type="text" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">URL of the Image of the Product</label>
                        <input type="text" class="form-control" id="product_imageurl" name="product_imageurl" required>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">Initial Stock</label>
                        <input type="text" class="form-control" id="stock" name="stock" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
                </main>
 <!-- Footer -->
  <footer class="footer bg-body-tertiary text-body-emphasis py-4">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">© 2025 Kevin Maximiliano Vazquez Aguilar - Universidad Politecnica de Santa Rosa Jauregui.</p>
            </div>
        </div>
    </footer>
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
    </body>
</html>




