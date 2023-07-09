<?php

$host = "localhost"; // اسم الخادم المضيف لقاعدة البيانات
$user = "root"; // اسم المستخدم الخاص بقاعدة البيانات
$password = ""; // كلمة مرور المستخدم الخاص بقاعدة البيانات
$database = "expense trackeer"; // اسم قاعدة البيانات
// الاتصال بقاعدة البيانات
$conn = mysqli_connect($host, $user, $password, $database);

// تحقق من اتصال قاعدة البيانات
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// استلام بيانات تسجيل الدخول من النموذج
if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // فحص صحة بيانات تسجيل الدخول
    $query = "SELECT * FROM users_table WHERE user_name='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        // احفظ اسم المستخدم في متغير الجلسة وتوجيه المستخدم إلى صفحة المستخدم
        session_start();
        $_SESSION['username'] = $username;
        header('location: userpage.php');
        exit();
    } else {
        // عرض رسالة الخطأ إذا لم يتم العثور على بيانات تسجيل الدخول المطابقة
        $error = "Invalid username or password. Please try again.";
    }
    
  
    
}

// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<link rel="stylesheet" href="website.css">
<link rel="stylesheet" href="login.css" type="text/css">
<link rel="icon"href="s.icon.png">
    <title>Expense Tracker</title>
</head>

<body>
    <div class="container">
    <nav>
        <img id="logo" src="s.icon.png" border="0" alt="logo" style="vertical-align:middle;">
            <a class="logo"href="#">    <h1 class ="logo">Tracking Expenses</h1></a>
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
      </ul>
    </nav>
    <hr>
  
<br>
    <h2>Login</h2>
<br>
    <form method="post" action="login.php">
		<label for="username">Username:</label>
		<input type="text" id="username" name="username" required><br><br>
		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required><br><br>
		<input type="submit" name="login" value="Login">
	</form>
    <main>
      <div>
        <div class="tx">
         
               
    </div>
  

    <div>
        <br><br><br>
       <div id="contact-box">
       <a href="https://www.facebook.com/"><img src="samahimg/facebook.png"alt=""></a>
       <a href="https://www.instagram.com/"><img src="samahimg/instagram.png"alt=""></a>
       <a href="https://www.whatsapp.com/"><img src="samahimg/whatsapp.png"alt=""></a>
       <a href="https://www.youtube.com/"><img src="samahimg/youtube.png"alt=""></a>
       <a href="https://www.google.com/"><img src="samahimg/google.png"alt=""></a>
       <a href="https://telegram.org/"><img src="samahimg/telgram.png"alt=""></a>
       <a href="https://twitter.com/"><img src="samahimg/twet.png"alt=""></a>

  </div>
   
</div>
   
</div>

    <footer>Copyright © 2022-2023 .
	All Rights are reserved</footer>
</body>

</html>