<?php 
require('../components/connect.php');
session_start();


$admin_id = $_SESSION['admin_id'];

if(!isset($_SESSION['admin_id'])){
    header('Location: ./admin_login.php');
    exit();
}

if(isset($_POST['delete_comment'])){

    $comment_id = $_POST['comment_id'];
    $comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);

    $delete_comments = $conn->prepare("DELETE FROM  `comments` WHERE id= ?");
    $delete_comments->execute(array($comment_id));

    $message[] = 'comment deleted!';
    $_SESSION['message'] = $message;
    header('Location: ./comments.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user comments</title>

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


<!-- comments section -->
<section class="comments">

    <h1 class="heading">all comments</h1>

    <p class="comment-title">post comments</p>

    <div class="box-container">
        <?php 
        $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE admin_id= ?");
        $select_comments->execute(array($admin_id));
        if($select_comments->rowCount() > 0){
            while($fetch_comments= $select_comments->fetch(PDO::FETCH_ASSOC)){
        ?>

            <div class="box">
                <?php  
                    $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE id= ? ");
                    $select_posts->execute(array($fetch_comments['post_id']));
                    while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
                ?>

                    <div class="post-title"><span>from : </span><?= $fetch_posts['title']; ?>
                    <a href="./read_posts.php?post_id=<?= $fetch_posts['id']; ?>">read post</a></div>

                <?php 
                }
                ?>
                <div class="user">
                    <i class="fas fa-user"></i>
                    <div class="user-info">
                        <span><?= $fetch_comments['user_name']; ?></span>
                        <div><?= $fetch_comments['date']; ?></div>
                    </div>
                </div>

                <div class="text"><?= $fetch_comments['comment']; ?></div>
                <form action="" method="post" class="icons">
                    <input type="hidden" name="comment_id" value="<?= $fetch_comments['id']; ?>">
                    <button type="submit" name="delete_comment" 
                    onclick="return confirm('delete this comment?');" 
                    class="inline-delete-btn">delete comment</button>
                </form>
            </div>

        <?php 
        }
        }else{
            echo '<p class="empty">no comments added yet!</p>';
        }
        ?>
    </div>

</section>



<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>