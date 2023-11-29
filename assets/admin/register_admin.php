<?php 
require('../components/connect.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('Location: ./admin_login.php');
    exit();
}


if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_admin = $conn->prepare("SELECT * FROM  `admin` WHERE name= ?");
    $select_admin->execute(array($name));
    if($select_admin->rowCount() > 0){
        $message[] = 'username already exist!';
    }else{
        if($pass != $cpass){
            $message[] = 'confirm password not matched!';
        }else{
            $insert_admin = $conn->prepare("INSERT INTO `admin` (name, password)
            VALUES(?, ?)");
            $insert_admin->execute(array($name, $cpass));
            $message[] = 'new admin registered!';
        }
    }
    $_SESSION['message'] = $message;
    header('Location:./register_admin.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register admin</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

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
    
<!-- header section -->
<?php require('../components/admin_header.php') ?>

<!-- register section -->

<section class="form-container">

    <form action="" method="post">
        <h3>register now</h3>
        <input type="text" name="name" class="box" placeholder="enter your username"
        required maxlength="20" oninput="this.value = this.value.replace(/\s/g,'');">
        <input type="password" name="pass" class="box" placeholder="enter your password"
        required maxlength="20" oninput="this.value = this.value.replace(/\s/g,'');">
        <input type="password" name="cpass" class="box" placeholder="confirm your password"
        required maxlength="20" oninput="this.value = this.value.replace(/\s/g,'');">
        <input type="submit" name="submit" class="btn" value="register now">
    </form>
</section>


<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>