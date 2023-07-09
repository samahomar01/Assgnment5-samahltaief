<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "expense trackeer";
$table = "expense_categories";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    
    $query = "DELETE FROM $table WHERE category_id = '$category_id' AND user_id = '$user_id'";
    
    if (mysqli_query($conn, $query)) {
        echo "Category deleted successfully";
        $conn->close();// Close connection
        header("location:add_cat.php"); // redirects to all records page
        exit;
    } else {
        echo "Error deleting category: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
