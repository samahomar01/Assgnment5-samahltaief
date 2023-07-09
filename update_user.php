<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "expense trackeer";
$table = "expense_categories";
$users_table = "users_table";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

if (!isset($_SESSION['username'])) {
    die("User ID not found in session");
}

$old_username = $_SESSION['username'];

if(isset($_POST['new_username'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $update_query = "UPDATE $users_table SET user_name = '$new_username' WHERE user_name = '$old_username'";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['username'] = $new_username;
        echo "Username updated successfully";
    } else {
        die("Error updating username: " . mysqli_error($conn));
    }
}

$username = $_SESSION['username'];

// جلب معرّف المستخدم من جدول المستخدمين
$user_query = "SELECT user_id FROM $users_table WHERE user_name = '$username'";
$user_result = mysqli_query($conn, $user_query);
$user_row = mysqli_fetch_assoc($user_result);
$user_id = $user_row['user_id'];

// جلب فئات المصروفات والأسعار المرتبطة بالمستخدم.
$category_query = "SELECT category_name, price, date_created FROM $table WHERE user_id = '$user_id'";
$category_result = mysqli_query($conn, $category_query);

if (!$category_result) {
    die("Error fetching categories: " . mysqli_error($conn));
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="update_cost.css" type="text/css">
<head>
    <title>Update Username</title>
    <h1 > Dear <?php echo $username; ?>.</h1><br>

</head>
<body>

    <h1>Update Username</h1>
    
    <form action="update_user.php" method="post">
        <label for="new_username">New Username:</label>
        <input type="text" name="new_username" id="new_username" required><br><br>
        <input type="submit" value="Update Username">
    </form>
   
</body>
</html>