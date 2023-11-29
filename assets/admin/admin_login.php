<?php 
require('../components/connect.php');
session_start();

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $select_admin = $conn->prepare("SELECT * FROM  `admin` WHERE name= ? AND
    password= ?");
    $select_admin->execute(array($name, $pass));
    if($select_admin->rowCount() > 0){
        $fetch_admin= $select_admin->fetch(PDO::FETCH_ASSOC);
        $_SESSION['admin_id'] = $fetch_admin['id'];
        header('Location:./dashboard.php');
        exit();
    }else{
        $message[] = 'incorrect username or password!';
        $_SESSION['message'] = $message;
        header('Location:./admin_login.php');
        exit();
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login page</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body style="padding-left: 0;">

<?php 
    if(isset($_SESSION['message'])){
        foreach($_SESSION['message'] as $message){
            echo '<div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
        }
        unset($_SESSION['message']);
    }
?>


<!-- form-container section -->

<section class="form-container">

    <form action="" method="post">
        <h3>login now</h3>
        <p>default username = <span>admin</span> & password = <span>111</span></p>
        <input type="text" name="name" class="box" placeholder="enter your username"
        required maxlength="20" oninput="this.value = this.value.replace(/\s/g,'');">
        <input type="password" name="pass" class="box" placeholder="enter your password"
        required maxlength="20" oninput="this.value = this.value.replace(/\s/g,'');">
        <input type="submit" name="submit" class="btn" value="login now">
    </form>
</section>




<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>