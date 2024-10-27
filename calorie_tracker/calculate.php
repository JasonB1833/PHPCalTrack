<?php
require 'database.php';
session_start(); // Ensure the session is started if user-specific data is needed

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Log - Calorie Tracker</title>
    <link rel="stylesheet" type="text/css" href="style.css">


</head>
<body>
    <div class="container">
        <h2>Your Daily Calorie Intake</h2>
        <?php
        // Initialize total calories variable
        $total_calories = 0;

        // Handle form submission to add calories
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['food_item_id'], $_POST['quantity'])) {
            // Get selected food item ID and quantity from POST data
            $food_item_id = $_POST['food_item_id'];
            $quantity = $_POST['quantity'];

            // Fetch the calorie value for the selected food item
            $sql = "SELECT calories FROM food_items WHERE id = :food_item_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':food_item_id', $food_item_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Calculate the total calories based on the quantity
                $total_calories += $row['calories'] * $quantity;
            }
        }

        // Display the total calories
        echo "<h1>Total Calories for Today: " . $total_calories . "</h1>";
        ?>
        <hr>

        <h2>Log Your Food Intake</h2>
        <form method="POST" action="">
            <label for="food_item">Select Food:</label>
            <select name="food_item_id" id="food_item">
                <?php
                // Fetch all food items from the database for the dropdown
                $sql = "SELECT id, food_name FROM food_items";
                $result = $pdo->query($sql);

                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['food_name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No food items available</option>";
                }
                ?>
            </select>
            
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" min="1" required>

            <input type="submit" value="Log Food">

            <p> <a href="index.php">Go Back to User Details</a>
            </p>
        </form>
    </div>
</body>
</html>
