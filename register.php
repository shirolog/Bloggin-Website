<?php 
require('./assets/components/connect.php');
session_start();



if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']) ;
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']) ;
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_users = $conn->prepare("SELECT * FROM  `users` WHERE email= ?");
    $select_users->execute(array($email));
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);

    if($select_users->rowCount() > 0){
        $message[] = 'email already taken!';
        $_SESSION['message'] = $message;
        header('Location: ./register.php');
        exit();

    }else{
        if($pass != $cpass){
            $message[] = 'confirm password not matched!';
            $_SESSION['message'] = $message;
            header('Location: ./register.php');
            exit();
        }else{
            $insert_users = $conn->prepare("INSERT INTO `users` (name, email, password) VALUES(?, ?, ?)");
            $insert_users->execute(array($name, $email, $cpass));

            $select_users = $conn->prepare("SELECT * FROM  `users` WHERE email= ? AND
            password= ?");
            $select_users->execute(array($email, $cpass));
            $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
            if($select_users->rowCount() > 0){
                $_SESSION['user_id'] = $fetch_users['id'];
                header('Location: ./home.php');
                exit();
            }
        }
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    
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
        <h3>register now</h3>
        <input type="text" name="name" class="box" placeholder="enter your name" 
        maxlength="50" required>
        <input type="email" name="email" class="box" placeholder="enter your email" 
        maxlength="50" required>
        <input type="password" name="pass" class="box" placeholder="enter your password" 
        maxlength="50" required>
        <input type="password" name="cpass" class="box" placeholder="confirm your password" 
        maxlength="50" required>
        <input type="submit" name="submit" class="btn" value="register now">
    </form>

</section>


<!-- custom js -->
<script src="./assets/js/app.js"></script>
</body>
</html>


