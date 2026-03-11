<?php
session_start();
if (!isset($_SESSION['user_type']) || (int) $_SESSION['user_type'] !== 2) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $conn = new mysqli('localhost', 'kevin_concurrente', '72seasons', 'rebibanelserber');

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $delete_id = intval($_POST['delete_id']);
    $sql = 'DELETE FROM products WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        header('Location: modify.php');
        exit();
    } else {
        $error_message = 'Error deleting product';
    }

    $stmt->close();
    $conn->close();
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
                        <span id="theme-text">Modo Oscuro</span>
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
    <div class="container" style="padding: 2rem 0;">
        <div class="row g-4">
        <?php
        // Database connection
        $conn = new mysqli('localhost', 'kevin_concurrente', '72seasons', 'rebibanelserber');

        // Check connection
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Fetch products with stock
        $sql = 'SELECT id, name, price, image_url, stock FROM products';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                ?>
    
        <div class="col-12">
            <div class="card shadow-lg border-primary p-4">
    <div class="card-body d-flex align-items-center">
        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
             class="me-4" 
             alt="<?php echo htmlspecialchars($product['name']); ?>"
             style="max-width: 150px; height: 150px; object-fit: contain;">
        
        <div class="flex-grow-1">
            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
            <p class="card-text mb-1">Stock: <?php echo $product['stock']; ?></span> unidades</p>
            <p class="card-text fw-bold fs-4 text-success">$<?php echo number_format($product['price'], 2); ?></p>
        </div>
        
        <div class="d-flex flex-column gap-2">
<!--Edit Button-->
            <a href="edit.php?id=<?php echo $product['id']; ?>" 
               class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            
<!--Delete Button-->
            <form method="POST" action="" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');" class="m-0">
                <input type="hidden" name="delete_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>  
        </div>
    <?php
            }
        }
        $conn->close();
        ?>
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