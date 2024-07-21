<?php
include 'db.php'; // Your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $skill = $_POST['skill'];

    // Check if the skill already exists
    $stmt = $conn->prepare("SELECT id FROM skills WHERE skill_name = ?");
    $stmt->bind_param("s", $skill);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Insert new skill
        $stmt = $conn->prepare("INSERT INTO skills (skill_name) VALUES (?)");
        $stmt->bind_param("s", $skill);
        $stmt->execute();
    }
}
?>
