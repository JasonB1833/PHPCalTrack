<?php
require 'database.php';

// Assuming daily intake ID is 1 for simplicity
$daily_intake_id = 1;

// SQL query to get total calories for the day
$sql = "SELECT SUM(fi.calories * difi.quantity) AS total_calories
        FROM daily_intake_food_items difi
        JOIN food_items fi ON difi.food_item_id = fi.id
        WHERE difi.daily_intake_id = :daily_intake_id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':daily_intake_id', $daily_intake_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$total_calories = $result['total_calories'] ?? 0;

// Display the total calories
echo "<h1>Total Calories for Today: " . $total_calories . "</h1>";
?>
