<?php include 'header.php'; include 'config.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name']; $email = $_POST['email']; $message = $_POST['message']; $date = date('Y-m-d');
    $sql = "INSERT INTO contacts (name, email, message, date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $message, $date);
    $stmt->execute();
    echo "<p class='alert alert-success'>Message sent!</p>";
}
?>
<div class="container mt-4">
    <h2>Contact Us</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required class="form-control mb-2">
        <input type="email" name="email" placeholder="Email" required class="form-control mb-2">
        <textarea name="message" placeholder="Message" required class="form-control mb-2"></textarea>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>
<?php include 'footer.php'; ?>
