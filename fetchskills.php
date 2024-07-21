<?php
include 'db.php'; // Your database connection file

// Fetch skills from the database
$result = $conn->query("SELECT skill_name FROM skills");
$skills = [];
while ($row = $result->fetch_assoc()) {
    $skills[] = $row['skill_name'];
}

// Return skills as JSON
echo json_encode($skills);
?>
