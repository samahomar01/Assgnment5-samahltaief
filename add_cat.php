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

$username = $_SESSION['username'];

// Fetch user ID from users_table
$user_query = "SELECT user_id FROM $users_table WHERE user_name = '$username'";
$user_result = mysqli_query($conn, $user_query);
$user_row = mysqli_fetch_assoc($user_result);
$user_id = $user_row['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    // Remove currency symbol
    $price = str_replace('$', '', $price);
    $date = mysqli_real_escape_string($conn, $_POST['date_created']);
    
    $sql = "INSERT INTO $table (category_name, price, date_created, user_id) VALUES ('$category_name', '$price', '$date', '$user_id')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Category added successfully";
    } else {
        echo "Error adding category: " . mysqli_error($conn);
    }
}

// Fetch all categories and prices for this user
$category_query = "SELECT category_id, category_name, price, date_created FROM $table WHERE user_id = '$user_id'";
$category_result = mysqli_query($conn, $category_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>
       
        Add Category</title>
    <link rel="stylesheet" type="text/css" href="add_cat.css">
</head>
<body>
    <h1>Add Category</h1>
    <h1 > Dear <?php echo $username; ?>.</h1><br>
    <form action="add_cat.php" method="post">
        <label for="category_name">Category Name:</label>
        <input type="text" name="category_name" id="category_name" required><br><br>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" required><br><br>
        <label for="date_created">Date Created:</label>
        <input type="date" name="date_created" id="date_created" required><br><br>
        <input type="submit" value="Add Category">
    </form>

    <h2>Your Categories</h2>
    <table>
        <tr>
        
            <th>Category Name</th>
            <th>Price</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($category_result)) { ?>
            <tr>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo '$' . $row['price']; ?></td>
                <td><?php echo $row['date_created']; ?></td>
                <td>
                    <form action="delete_cat.php" method="post">
                        <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>