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


$user_query = "SELECT user_id FROM $users_table WHERE user_name = '$username'";
$user_result = mysqli_query($conn, $user_query);
$user_row = mysqli_fetch_assoc($user_result);
$user_id = $user_row['user_id'];

$category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
$price = mysqli_real_escape_string($conn, $_POST['price']);

$sql = "UPDATE $table SET price = '$price' WHERE category_name = '$category_name' AND user_id = '$user_id'";
if (mysqli_query($conn, $sql)) {
    echo "Price updated successfully";
} else {
    die("Error updating price: " . mysqli_error($conn));
}

// جلب فئات المصروفات والأسعار المرتبطة بالمستخدم (قبل التحديث).
$category_query_before = "SELECT category_name, price, date_created FROM $table WHERE user_id = '$user_id'";
$category_result_before = mysqli_query($conn, $category_query_before);

if (!$category_result_before) {
    die("Error fetching categories: " . mysqli_error($conn));
}

// جلب فئات المصروفات والأسعار المرتبطة بالمستخدم (بعد التحديث).
$category_query_after = "SELECT category_name, price, date_created FROM $table WHERE category_name = '$category_name' AND user_id = '$user_id'";
$category_result_after = mysqli_query($conn, $category_query_after);

if (!$category_result_after) {
    die("Error fetching categories: " . mysqli_error($conn));
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="update_cost.css" type="text/css">
<head>
    <title>Update Price</title>
    <h1 > Dear <?php echo $username; ?>.</h1><br>

</head>
<body>

    <h1>Update Price</h1>
    
    <form action="update_cost.php" method="post">
        <label for="category_name">Category Name:</label>

        <input type="text" name="category_name" id="category_name" required><br><br>
        <label for="price">Price:</label>
        <input type="text" name="price" id="price" required><br><br>
        <input type="submit" value="Update Price">
    </form>
    
  
       
    <h2>Categories and Prices (Before Update)</h2>
    <table>
        <tr>
            <th>Category Name</th>
            <th>Price</th>
            <th>Date Created</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($category_result_before)) { ?>
            <tr>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['date_created']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <h2>Categories and Prices (After Update)</h2>
    <table>
        <tr>
            <th>Category Name</th>
            <th>Price</th>
            <th>Date Created</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($category_result_after)) { ?>
            <tr>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['date_created']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>