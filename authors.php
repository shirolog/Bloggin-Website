<?php 
require('./assets/components/connect.php');
session_start();


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: ./login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>post category</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./assets/components/user_header.php'); ?>
    
<!-- authors section -->
<section class="authors">

    <h1 class="heading">authors</h1>

    <div class="box-container">
        <?php 
            $select_admin = $conn->prepare("SELECT * FROM `admin`");
            $select_admin->execute();
            if($select_admin->rowCount() > 0){
                while($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)){
                $author_id= $fetch_admin['id'];
                
                $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id= ? AND status= ?");
                $select_posts->execute(array($author_id, 'active'));
                $total_posts = $select_posts->rowCount();

                $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE admin_id= ? ");
                $select_likes->execute(array($author_id));
                $total_likes = $select_likes->rowCount();

                $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE admin_id= ? ");
                $select_comments->execute(array($author_id));
                $total_comments = $select_comments->rowCount();             
        ?>

            <div class="box">
                <p>author : <span><?= $fetch_admin['name']; ?></span></p>
                <p>total posts : <span><?= $total_posts; ?></span></p>
                <p>total likes : <span><?= $total_likes; ?></span></p>
                <p>total comments : <span><?= $total_comments; ?></span></p>
                <a href="./author_posts.php?author=<?= $fetch_admin['name']; ?>" class="btn">view posts</a>
            </div>

        <?php 
        }
        }else{
            echo '<p class="empty">no authors found!</p>';
        }
        ?>
    </div>

</section>



<!-- footer section -->
<?php  require('./assets/components/footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>
</body>
</html>


