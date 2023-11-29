<?php 
require('../components/connect.php');
session_start();


$admin_id = $_SESSION['admin_id'];

if(!isset($_SESSION['admin_id'])){
    header('Location: ./admin_login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users accounts</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    
<!-- header section -->
<?php require('../components/admin_header.php') ?>


<!-- account section -->
<section class="account">

    <h1 class="heading">users accounts</h1>

    <div class="box-container">

        <?php 
        $select_users = $conn->prepare("SELECT * FROM  `users`");
        $select_users->execute();
        if($select_users->rowCount() > 0){
            while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
            
        ?>

            <div class="box">
                <p>id : <span><?= $fetch_users['id']; ?></span></p>
                <p>username : <span><?= $fetch_users['name']; ?></span></p>
                <p>user email : <span><?= $fetch_users['email']; ?></span></p>
            </div>

        <?php 
        }
        }else{
            echo '<p class="empty">no accounts found!</p>';
        }        
        ?>
    </div>

</section>




<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>