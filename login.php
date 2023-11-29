<?php 
require('./assets/components/connect.php');
session_start();


if(isset($_POST['submit'])){

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']) ;
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);


    $select_users = $conn->prepare("SELECT * FROM  `users` WHERE email= ? AND password= ?");
    $select_users->execute(array($email, $pass));
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);

    if($select_users->rowCount() > 0){
        $_SESSION['user_id'] = $fetch_users['id'];
        header('Location: ./home.php');
        exit();
    }else{
        $message[] = 'incorrect email or password!';
        $_SESSION['message'] = $message;
        header('Location:./login.php');
        exit();
    }


}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
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


    

<!-- form-container section -->
<section class="form-container">

    <form action="" method="post">
        <h3>login now</h3>
        <input type="email" name="email" class="box" placeholder="enter your email" 
        maxlength="50" required>
        <input type="password" name="pass" class="box" placeholder="enter your password" 
        maxlength="50" required>
        <input type="submit" name="submit" class="btn" value="login now">
    </form>

</section>


<!-- custom js -->
<script src="./assets/js/app.js"></script>
</body>
</html>


