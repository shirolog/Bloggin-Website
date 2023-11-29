<?php 
require('./assets/components/connect.php');
session_start();




if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: ./home.php');
    exit();
}

if(isset($_POST['submit'])){

    $name = $_POST['name'] ;
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
 
    if(!empty($name)){
        $update_users = $conn->prepare("UPDATE `users` SET name = ? WHERE id= ?");
        $update_users->execute(array($name, $user_id));
    }

    if(!empty($email)){
        $select_users = $conn->prepare("SELECT email FROM `users` WHERE email = ?");
        $select_users->execute(array($email));
        if($select_users->rowCount() > 0){
            $message[] = 'email already taken!';
        }else{
            $update_users = $conn->prepare("UPDATE `users` SET email = ? WHERE id= ?");
            $update_users->execute(array($email, $user_id));
            $message[] = 'email updated!';
        }
    }

    $empty_pass= 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $select_users = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
    $select_users->execute(array($user_id));
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
    $prev_pass = $fetch_users['password'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    if($old_pass != $empty_pass){
        if($old_pass != $prev_pass){
            $message[] = 'old password not matched!';
        }elseif($new_pass != $cpass){
            $message[]= 'cofirm password not matched!';
        }else{
            if($new_pass != $empty_pass){
                $update_users = $conn->prepare("UPDATE `users` SET password = ? WHERE id= ?");
                $update_users->execute(array($cpass, $user_id));
                $message[] = 'password updated!';
            }else{
                $message[] = 'please enter new password!';
            }
        }
    }
    $_SESSION['message'] = $message;
    header('Location: ./update.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile update</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>


<!-- header section -->
<?php require('./assets/components/user_header.php'); ?>
    

<!-- form-container section -->
<section class="form-container">

    <form action="" method="post">
        <h3>update profile</h3>
        <input type="text" name="name" class="box" placeholder="<?= $fetch_users['name']; ?>" 
        maxlength="50">
        <input type="email" name="email" class="box" placeholder="<?= $fetch_users['email']; ?>" 
        maxlength="50">
        <input type="password" name="old_pass" class="box" placeholder="enter your old password" 
        maxlength="50">
        <input type="password" name="new_pass" class="box" placeholder="enter your new password" 
        maxlength="50">
        <input type="password" name="cpass" class="box" placeholder="confirm your password" 
        maxlength="50">
        <input type="submit" name="submit" class="btn" value="update now">
    </form>

</section>



<!-- footer section -->
<?php  require('./assets/components/footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>
</body>
</html>


