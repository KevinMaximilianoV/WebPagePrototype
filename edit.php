<?php

session_start();
if (!isset($_SESSION['user_type']) || (int) $_SESSION['user_type'] !== 2) {
    header('Location: index.php');
    exit();
}
$conn = new mysqli('localhost', 'kevin_concurrente', '72seasons', 'rebibanelserber');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Obtener datos del producto
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = 'SELECT * FROM products WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        header('Location: modify.php');
        exit();
    }
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image_url = $_POST['image_url'];

    $sql = 'UPDATE products SET name=?, price=?, stock=?, image_url=? WHERE id=?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdisi', $name, $price, $stock, $image_url, $id);

    if ($stmt->execute()) {
        header('Location: modify.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html data-bs-theme="dark">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Productos - TuPC</title>
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
                        <a class="nav-link active" href="index.php">Inicio</a>
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
                        echo '<a href="logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Cerrar Sesion</a>';
                        echo '<a href="adminpanel.php" class="btn btn-outline-light me-2"><i class="fa-solid fa-code"></i> Panel de Administracion</a>';
                    }

                    ?>
                </div>
            </div>
        </div>
    </nav>
<div class="container mt-5">
    <h2>Modificar Producto</h2>
    <form method="POST" class="mt-4">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        
        <div class="mb-3">
            <label class="form-label">Nombre del Producto</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">URL de la Imagen</label>
            <input type="url" name="image_url" class="form-control" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        <a href="modify.php" class="btn btn-secondary">Cancelar</a>
    </form>
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