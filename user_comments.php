<?php 
require('./assets/components/connect.php');
session_start();



if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: ./home.php');
    exit();
}

if(isset($_GET['post_id'])){
    $post_id= $_GET['post_id'];
}else{
    $post_id = '';
}




if(isset($_POST['edit_comment'])){

    $edit_comment_id = $_POST['edit_comment_id'];
    $edit_comment_id = filter_var($edit_comment_id, FILTER_SANITIZE_STRING);
    $edit_comment_box = $_POST['edit_comment_box'];
    $edit_comment_box = filter_var($edit_comment_box, FILTER_SANITIZE_STRING);

    $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE id= ? AND comment= ?");
    $select_comments->execute(array($edit_comment_id, $edit_comment_box));
    if($select_comments->rowCount() > 0){
        $message[] = 'comment already added!';
    }else{
        $update_comments= $conn->prepare("UPDATE `comments` SET comment= ? WHERE id= ?");
        $update_comments->execute(array($edit_comment_box, $edit_comment_id));
        $message[] = 'comment edited successfully!';
    }
    $_SESSION['message'] = $message;
    header('Location: ./user_comments.php');
    exit();
}

if(isset($_POST['delete_comment'])){
    $comment_id = $_POST['comment_id'];
    $comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);


    $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE id= ?");
    $delete_comments->execute(array($comment_id));
    $message[] = 'comment deleted successfully!';
    $_SESSION['message'] = $message;
    header('Location: ./user_comments.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>read post</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./assets/components/user_header.php'); ?>
    
<?php 

if(isset($_POST['open_edit_box'])){

    $comment_id = $_POST['comment_id'];
    $comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);

    $select_comments = $conn->prepare("SELECT * FROM  `comments` WHERE id= ?");
    $select_comments->execute(array($comment_id));
    $fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC);
?>
<section class="edit-comment-box" style="padding-bottom: 0;">
    <form action="" method="post">
        <p>edit your comment</p>
        <input type="hidden" name="edit_comment_id" value="<?= $fetch_comments['id']; ?>">
        <textarea name="edit_comment_box" class="comment-box" cols="30" rows="10" required
        placeholder="enter yuor comment" maxlength="1000"><?= $fetch_comments['comment']; ?></textarea>
        <input type="submit" name="edit_comment" value="edit comment" class="inline-btn">
        <a href="./user_comments.php" class="inline-option-btn">cancel edit</a>
    </form>

</section>

<?php 
}
?>


<!-- comments section -->
<section class="comments" style="padding-bottom: 0;">
        

        <p class="comment-title">user comments</p>

        <div class="show-comments">

            <?php 
                $select_comments= $conn->prepare("SELECT * FROM  `comments` WHERE user_id= ?");
                $select_comments->execute(array($user_id));
                if($select_comments->rowCount() > 0){
                   while($fetch_comments= $select_comments->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="user-comments" <?php if($fetch_comments['user_id'] == $user_id)
                {echo 'style= "order: -1;"';}?>>
                    <?php 
                        $select_posts= $conn->prepare("SELECT * FROM `posts` WHERE id= ?");
                        $select_posts->execute(array($fetch_comments['post_id']));
                        while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
                    ?>

                        <div class="post-title">
                            from : <span><?= $fetch_posts['title']; ?></span>
                            <a href="./view_post.php?post_id=<?= $fetch_posts['id']; ?>">view post</a>
                        </div>

                    <div class="user">
                        <i class="fas fa-user"></i>
                        <div class="user-info">
                            <p><?= $fetch_comments['user_name'] ?></p>
                            <div><?= $fetch_comments['date']; ?></div>
                        </div>

                    </div>
                    <div class="comment-box" <?php if($fetch_comments['user_id'] == $user_id)
                {echo 'style= "color: var(--white); background: var(--black);"';}?>><?= $fetch_comments['comment']; ?></div>

                    <?php 
                    if($fetch_comments['user_id'] == $user_id){
                ?>

                    <form action="" method="post" class="flex-btn">
                        <input type="hidden" name="comment_id" value="<?= $fetch_comments['id']; ?>">
                        <input type="submit" name="open_edit_box" value="edit comment" 
                        class="inline-option-btn">
                        <input type="submit" name="delete_comment" value="delete comment" 
                        class="inline-delete-btn" onclick="return confirm('delete this comment?');">
                    </form>

                <?php 
                }
                ?>
                </div>

            <?php 
            }
            }
            }else{
                echo '<p class="empty">no comments added yet!</p>';
            }
            ?>
            </div>
        </div>
</section>



<!-- footer section -->
<?php  require('./assets/components/footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>
</body>
</html>


