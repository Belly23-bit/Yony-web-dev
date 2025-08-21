<?php include 'header.php'; include 'config.php'; ?>
<div class="container mt-4">
    <h2>Departments</h2>
    <?php
    $sql = "SELECT * FROM departments";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card mb-3'><div class='card-body'><h5>{$row['name']}</h5><p>{$row['description']}</p></div></div>";
    }
    ?>
</div>
<?php include 'footer.php'; ?>
