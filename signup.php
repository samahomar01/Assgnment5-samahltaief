<?php
$host = "localhost"; 
$user = "root";
$password = "";  
$database = "expense trackeer"; 
$table = "users_table"; 

$conn = mysqli_connect($host, $user, $password, $database,null);

// تحقق من اتصال قاعدة البيانات
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['user_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if (strlen($username) < 10) {
        echo "Username must be at least 10 characters";
    } else if (strlen($password) < 10) {
        echo "Password must be at least 10 characters";
    } else if ($password != $confirm_password) {
        echo "Error in confirm password";
    } else if (!preg_match("/[a-z]/", $password)) {
        echo "Password must contain small letters";
    } else if (!preg_match("/[A-Z]/", $password)) {
        echo "Password must contain capital letters";
    } else if (!preg_match("/[0-9]/", $password)) {
        echo "Password must contain numbers";
    } else if (!preg_match("/[~!@#$%^&*()_+]/", $password)) {
        echo "Password must contain symbols";
    } else if (!preg_match("/^[0-9]{10}$/", $phone_number)) {
        echo "Invalid phone number";
    } else {
        
         $stmt = mysqli_prepare($conn, "INSERT INTO $table (user_name, phone_number, password) VALUES (?, ?, ?)");
         mysqli_stmt_bind_param($stmt, "sss", $username, $phone_number, $password);
         mysqli_stmt_execute($stmt);
 
         
         if (mysqli_affected_rows($conn) == 1) {
             // فحص صحة بيانات تسجيل الدخول
             $query = "SELECT * FROM $table WHERE user_name = ? AND password = ?";
             $stmt = mysqli_prepare($conn, $query);
             mysqli_stmt_bind_param($stmt, "ss", $username, $password);
             mysqli_stmt_execute($stmt);
             $result = mysqli_stmt_get_result($stmt);
 
             // التحقق من وجود صف واحد على الأقل يتطابق مع بيانات تسجيل الدخول
             if (mysqli_num_rows($result) == 1) {
                 // احفظ اسم المستخدم في متغير الجلسة وتوجيه المستخدم إلى صفحة المستخدم
                 session_start();
                 $_SESSION['username'] = $username;
                 header('location:userpage.php');
                 exit();
             } else {
                 // عرض رسالة الخطأ إذا لم يتم العثور على بيانات تسجيل الدخول المطابقة
                 $error = "Invalid username or password. Please try again.";
             }
         } else {
             
             $error = "Error inserting data into database";
         }
     }
 }
 
 mysqli_close($conn);
 ?>
 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tracking Expense</title>
    <link rel="stylesheet" href="website.css" type="text/css">
</head>
<body>
    <div class="container">
        <nav>
            <img id="logo" src="s.icon.png" border="0" alt="logo" style="vertical-align:middle;">
            <a class="logo"href="#">    <h1 class ="logo">Tracking Expense</h1></a>
            <ul>
                <nav id="navbar">  
                    
                <ul class="navcontent">
                    <li><a href="website_page.html">Home</a> </li>
                    <li><a href="login.php">Log in</a></li>
			        <li><a href="signup.php">Sign up</a></li>
                    <li><a href="#">Reports</a> </li>
			        <li><a href="#">History</a></li>
                    <li><a href="#">ContactUs</a> </li>
			        <li><a href="#">About us</a></li>
                </ul>
                </nav>
                <div class="re">
                    <br><br>
                    <table  id="t1">
                        <tr>
                            <th colspan="1" align="center"><h1>Registration</h1></th><br>
                        </tr>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <tr>
                                <td><label for="user">Username </label></td>
                            </tr>
                            <tr>
                                <td><input type="text" id="user" name="user_name" required></td>
                            </tr>
                            <tr>
                                <td><label for="phone">Phone_number </label></td>
                            </tr>
                            <tr>
                                <td><input type="tel" id="phone" name="phone_number" required></td>
                            </tr>
                            <tr>
                                <td><label for="pass">Password </label></td>
                            </tr>
                            <tr>
                                <td><input type="password" id="pass" name="password" required></td>
                            </tr>
                            <tr>
                                <td><label for="confirm_pass">Confirm password </label></td>
                            </tr>
                            <tr>
                                <td><input type="password" id="confirm_pass" name="confirm_password" required></td>
                            </tr>
                            <tr>
                                <td><input type="submit" value="Submit"></td>
                            </tr>
                        </form>
                    </table>
                </div>
            </ul>
        </nav>
        
    </div>
    
</body>
</html>