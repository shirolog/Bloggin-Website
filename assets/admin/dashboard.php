<?php 
require('../components/connect.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('Location: ./admin_login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    
<!-- header section -->
<?php require('../components/admin_header.php') ?>

<!-- dashboard section -->
<section class="dashboard">

    <h1 class="heading">dashboard</h1>

    <div class="box-container">

        <div class="box">
            <h3>welcome</h3>
            <p><?= $fetch_admin['name']; ?></p>
            <a href="./update_profile.php" class="btn">update profile</a>
        </div>

        <div class="box">
            <?php 
            $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id= ?");
            $select_posts->execute(array($admin_id));
            $number_of_posts = $select_posts->rowCount();
            ?>
            <h3><?= $number_of_posts; ?></h3>
            <p>posts added</p>
            <a href="./add_posts.php" class="btn">add new post</a>
        </div>

        <div class="box">
            <?php 
            $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id= ? AND status=?");
            $select_posts->execute(array($admin_id, 'active'));
            $number_of_active_posts = $select_posts->rowCount();
            ?>
            <h3><?= $number_of_active_posts; ?></h3>
            <p>active posts</p>
            <a href="./view_posts.php" class="btn">view posts</a>
        </div>

        <div class="box">
            <?php 
            $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id= ? AND status=?");
            $select_posts->execute(array($admin_id, 'deactive'));
            $number_of_deactive_posts = $select_posts->rowCount();
            ?>
            <h3><?= $number_of_deactive_posts; ?></h3>
            <p>deactive posts</p>
            <a href="./view_posts.php" class="btn">view posts</a>
        </div>

        
        <div class="box">
            <?php 
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount();
            ?>
            <h3><?= $number_of_users; ?></h3>
            <p>total users</p>
            <a href="./users_accounts.php" class="btn">view users</a>
        </div>
        
        <div class="box">
            <?php 
            $select_admins = $conn->prepare("SELECT * FROM `admin`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount();
            ?>
            <h3><?= $number_of_admins; ?></h3>
            <p>total admins</p>
            <a href="./admin_accounts.php" class="btn">view admins</a>
        </div>
        
        <div class="box">
            <?php 
            $select_comments = $conn->prepare("SELECT * FROM  `comments` WHERE admin_id= ?");
            $select_comments->execute(array($admin_id));
            $number_of_comments = $select_comments->rowCount();
            ?>
            <h3><?= $number_of_comments; ?></h3>
            <p>total comments</p>
            <a href="./comments.php" class="btn">view comments</a>
        </div>
        
        <div class="box">
            <?php 
            $select_likes = $conn->prepare("SELECT * FROM  `likes` WHERE admin_id= ?");
            $select_likes->execute(array($admin_id));
            $number_of_likes = $select_likes->rowCount();
            ?>
            <h3><?= $number_of_likes; ?></h3>
            <p>total likes</p>
            <a href="./view_posts.php" class="btn">view posts</a>
        </div>

    </div>

</section>



<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>