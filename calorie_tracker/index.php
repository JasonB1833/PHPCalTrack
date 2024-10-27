<?php
require 'database.php';

// Function to calculate calories, fats, proteins, and carbs
function calculateMacros($age, $gender, $height_inches, $current_weight, $goal_weight, $activity_level) {
    // calculation logic
    $bmr = ($gender == 'Male') ? 
        (66 + (6.23 * $current_weight) + (12.7 * $height_inches) - (6.8 * $age)) :
        (655 + (4.35 * $current_weight) + (4.7 * $height_inches) - (4.7 * $age));
    
    // Adjust based on activity level
    switch ($activity_level) {
        case 'Sedentary':
            $calories = $bmr * 1.2;
            break;
        case 'Lightly Active':
            $calories = $bmr * 1.375;
            break;
        case 'Moderately Active':
            $calories = $bmr * 1.55;
            break;
        case 'Very Active':
            $calories = $bmr * 1.725;
            break;
        default:
            $calories = $bmr * 1.2; // Default to sedentary if not specified
    }

    // New calculation for calories to meet goal
    $goal_calories = $calories;

    if ($goal_weight < $current_weight) {
        // Losing weight (subtract 500 calories for a safe weight loss goal)
        $goal_calories -= 500;
    } elseif ($goal_weight > $current_weight) {
        // Gaining weight (add 500 calories for a safe weight gain goal)
        $goal_calories += 500;
    }

    // Example macros calculation 
    $protein = $goal_weight * 0.8;
    $fats = $goal_weight * 0.35;
    $carbs = ($calories - ($protein * 4 + $fats * 9)) / 4;

    return ['calories' => round($calories), 'protein' => round($protein), 'fats' => round($fats), 'carbs' => round($carbs), 'goal calories' => round($goal_calories)];
}




// Handling form submission for creating/updating personal details and calculating macros
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $height_inches = $_POST['height_inches'];
    $current_weight = $_POST['current_weight'];
    $goal_weight = $_POST['goal_weight'];
    $activity_level = $_POST['activity_level'];
    

    // Calculate the macros
    $macros = calculateMacros($age, $gender, $height_inches, $current_weight, $goal_weight, $activity_level);
   

    if ($id) {
        // Update the personal details
        $stmt = $pdo->prepare("UPDATE personal_details SET name = ?, age = ?, gender = ?, height_inches = ?, current_weight = ?, goal_weight = ?, activity_level = ?, calories = ?, protein = ?, fats = ?, carbs = ? WHERE id = ?");
        $stmt->execute([$name, $age, $gender, $height_inches, $current_weight, $goal_weight, $activity_level, $macros['calories'], $macros['protein'], $macros['fats'], $macros['carbs'], $id]);
        $message = "Personal details updated successfully!";
    } else {
        // Insert new personal details and macros
        $stmt = $pdo->prepare("INSERT INTO personal_details (name, age, gender, height_inches, current_weight, goal_weight, activity_level, calories, protein, fats, carbs) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $age, $gender, $height_inches, $current_weight, $goal_weight, $activity_level, $macros['calories'], $macros['protein'], $macros['fats'], $macros['carbs']]);
        $message = "Personal details added successfully!";
    }
}

// Fetch personal details
$stmt = $pdo->query("SELECT * FROM personal_details");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handling delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM personal_details WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calorie Calculator</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">

    <h1>Personal Details and Macro Calculations</h1>

    <!-- Display success or error messages -->
    <?php if (isset($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <!-- Form for adding/updating personal details -->
    <form method="POST" action="index.php">
    <input type="hidden" name="id" value="<?= $_GET['edit_id'] ?? '' ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?= $_GET['name'] ?? '' ?>" required><br>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" value="<?= $_GET['age'] ?? 0 ?>" required><br>

    <label for="gender">Gender:</label>
    <select id="gender" name="gender" required>
        <option value="Male" <?= (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
    </select><br>

    <label for="height_inches">Height (in inches):</label>
    <input type="number" id="height_inches" name="height_inches" value="<?= $_GET['height_inches'] ?? 0 ?>" required><br>

    <label for="current_weight">Current Weight (lbs):</label>
    <input type="number" id="current_weight" name="current_weight" value="<?= $_GET['current_weight'] ?? 0 ?>" required><br>

    <label for="goal_weight">Goal Weight (lbs):</label>
    <input type="number" id="goal_weight" name="goal_weight" value="<?= $_GET['goal_weight'] ?? 0 ?>" required><br>

    <label for="activity_level">Activity Level:</label>
    <select id="activity_level" name="activity_level" required>
        <option value="Sedentary" <?= (isset($_GET['activity_level']) && $_GET['activity_level'] == 'Sedentary') ? 'selected' : '' ?>>Sedentary</option>
        <option value="Lightly Active" <?= (isset($_GET['activity_level']) && $_GET['activity_level'] == 'Lightly Active') ? 'selected' : '' ?>>Lightly Active</option>
        <option value="Moderately Active" <?= (isset($_GET['activity_level']) && $_GET['activity_level'] == 'Moderately Active') ? 'selected' : '' ?>>Moderately Active</option>
        <option value="Very Active" <?= (isset($_GET['activity_level']) && $_GET['activity_level'] == 'Very Active') ? 'selected' : '' ?>>Very Active</option>
    </select><br>

    <input type="submit" value="Submit">
</form>


    <!-- Table displaying current personal details along with calculated macros -->
    <h2>Existing Records</h2>
            <br>
            <p>
                The following records will showcase your goals as well as a "John doe" example. The "Maintanence" is the calories needed for you to maintain weight should you need it. The "goal" calories is the amount required to safely reach your goal, be sure to weigh-in regularly and edit your details to help on the goal as this number is constantly changing!
            </p>
            <br>
            <p>feel free to add as many goals for as many people as you'd like! Click <a href="calculate.php">Here</a> to calculate your calories for today.
            </p>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Height (inches)</th>
            <th>Current Weight (lbs)</th>
            <th>Goal Weight (lbs)</th>
            <th>Activity Level</th>
            <th>Calories (Maintanence)</th>
            <th>Calories (goal)</th>
            <th>Protein (g)</th>
            <th>Fats (g)</th>
            <th>Carbs (g)</th>
            <th>Actions</th>
        </tr>
        
<?php foreach ($users as $user): ?>
    <?php
    // Calculate the goal calories for the user
    $user_macros = calculateMacros($user['age'], $user['gender'], $user['height_inches'], $user['current_weight'], $user['goal_weight'], $user['activity_level']);
    ?>
    <tr>
        <td><?= $user['name'] ?></td>
        <td><?= $user['age'] ?></td>
        <td><?= $user['gender'] ?></td>
        <td><?= $user['height_inches'] ?></td>
        <td><?= $user['current_weight'] ?></td>
        <td><?= $user['goal_weight'] ?></td>
        <td><?= $user['activity_level'] ?></td>
        <td><?= $user['calories'] ?></td>
        <td><?= $user_macros['goal calories'] ?></td> <!-- Display the calculated goal calories -->
        <td><?= $user['protein'] ?></td>
        <td><?= $user['fats'] ?></td>
        <td><?= $user['carbs'] ?></td>
        <td>
            <a href="index.php?edit_id=<?= $user['id'] ?>&name=<?= $user['name'] ?>&age=<?= $user['age'] ?>&gender=<?= $user['gender'] ?>&height_inches=<?= $user['height_inches'] ?>&current_weight=<?= $user['current_weight'] ?>&goal_weight=<?= $user['goal_weight'] ?>&activity_level=<?= $user['activity_level'] ?>">Edit</a> | 
            <a href="index.php?delete_id=<?= $user['id'] ?>">Delete</a>
        </td>
    </tr>
<?php endforeach; ?>

    </table>

    



</body>
</html>
