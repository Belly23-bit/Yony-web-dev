<?php include 'header.php'; include 'config.php'; ?>
<div class="container mt-4">
    <h2>Downloads</h2>
    <form method="GET"><input type="text" name="search" placeholder="Search..."><button type="submit">Search</button></form>
    <?php
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sql = "SELECT * FROM documents WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card mb-3'><div class='card-body'><a href='{$row['path']}' download>{$row['name']} ({$row['type']})</a><small>Uploaded: {$row['upload_date']}</small></div></div>";
    }
    ?>
</div>
<?php include 'footer.php'; ?>
