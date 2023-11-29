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
    if(!empty($name)){
        $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name=?");
        $select_admin->execute(array($name));
        if($select_admin->rowCount() > 0){
            $message[] = 'username already taken!';
        }else{
            $update_admin = $conn->prepare("UPDATE `admin` SET name= ? WHERE id= ? ");
            $update_admin->execute(array($name, $admin_id));
            $message[] = 'username updated!';
        }
    }


    
    $empty_pass = 'da39a3ee 5e6b4b0d 3255bfef 95601890 afd80709';
    $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id= ? ");
    $select_admin->execute(array($admin_id));
    $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
    $prev_pass = $fetch_admin['password'];
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
            $message[] = 'confirm password not matched!';
        }else{
            if($new_pass != $empty_pass){
                $update_admin = $conn->prepare("UPDATE `admin` SET password= ? WHERE id= ? ");
                $update_admin->execute(array($cpass, $admin_id));
                $message[] = 'password updated!';
            }else{
                $message[] = 'please enter new password!';
            }
        }
    }
    $_SESSION['message'] = $message;
    header('Location:./update_profile.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update profile</title>

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


<!-- profile update section -->

<section class="form-container">

    <form action="" method="post">
        <h3>update now</h3>
        <input type="text" name="name" class="box" placeholder="<?= $fetch_admin['name']; ?>"
         maxlength="20" oninput="this.value = this.value.replace(/\s/g,'');">
        <input type="password" name="old_pass" class="box" placeholder="enter your old password"
         maxlength="20" oninput="this.value = this.value.replace(/\s/g,'');">
        <input type="password" name="new_pass" class="box" placeholder="enter your new password"
         maxlength="20" oninput="this.value = this.value.replace(/\s/g,'');">
        <input type="password" name="cpass" class="box" placeholder="confirm your new password"
         maxlength="20" oninput="this.value = this.value.replace(/\s/g,'');">
        <input type="submit" name="submit" class="btn" value="update now">
    </form>
</section>


<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>