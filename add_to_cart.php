<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add items to cart']);
    exit();
}

// Check if product_id is provided
if(!isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'No product specified']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Validate quantity
if($quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "rebibanelserber");

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Check if product exists
$sql = "SELECT id, price FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

// Check if product is already in cart
$sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    // Update existing cart item
    $cart_item = $result->fetch_assoc();
    $new_quantity = $cart_item['quantity'] + $quantity;
    
    $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product quantity updated in cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update cart: ' . $conn->error]);
    }
} else {
    // Add new cart item
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add to cart: ' . $conn->error]);
    }
}

$conn->close();
?>