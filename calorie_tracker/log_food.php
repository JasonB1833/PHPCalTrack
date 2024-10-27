<?php
require 'database.php';

// Assuming user_id is 1 for now (adjust as needed)
$user_id = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $food_item_id = $_POST['food_item_id'];
    $quantity = $_POST['quantity'];

    if (empty($food_item_id) || empty($quantity) || !is_numeric($quantity)) {
        echo "Please provide valid food item and quantity.";
        exit;
    }

    // Check if there's an existing daily intake for the user today
    $query = "SELECT id FROM daily_intake WHERE user_id = :user_id AND date = CURDATE()";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $daily_intake_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    } else {
        // Insert a new daily intake entry for today
        $insertDailyIntake = "INSERT INTO daily_intake (user_id, date) VALUES (:user_id, CURDATE())";
        $insertStmt = $pdo->prepare($insertDailyIntake);
        $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insertStmt->execute();

        $daily_intake_id = $pdo->lastInsertId();
    }

    // Insert logged food item into the database
    $query = "INSERT INTO daily_intake_food_items (food_item_id, quantity, daily_intake_id) VALUES (:food_item_id, :quantity, :daily_intake_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':food_item_id', $food_item_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':daily_intake_id', $daily_intake_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Food item logged successfully. <a href='index.php'>Return to Home</a>";
    } else {
        echo "Error logging food item: " . $pdo->errorInfo()[2];
    }
}
?>
