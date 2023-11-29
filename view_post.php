<?php 
require('./assets/components/connect.php');
session_start();



if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: ./login.php');
    exit();
}

if(isset($_GET['post_id'])){
    $post_id= $_GET['post_id'];
}else{
    $post_id = '';
}


if(isset($_POST['like_posts'])){

    if($user_id != ''){
        $post_id = $_POST['post_id'];
        $post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
        $admin_id = $_POST['admin_id'];
        $admin_id = filter_var($admin_id, FILTER_SANITIZE_STRING);

        $select_likes = $conn->prepare("SELECT * FROM  `likes` WHERE user_id= ? AND
        post_id= ?");
        $select_likes->execute(array($user_id, $post_id));
        if($select_likes->rowCount() > 0){
            $delete_likes = $conn->prepare("DELETE FROM  `likes` WHERE user_id= ? AND post_id= ? ");
            $delete_likes->execute(array($user_id, $post_id));
            $message[] = 'remove from likes!';
        }else{
            $insert_likes = $conn->prepare("INSERT INTO  `likes` (user_id, admin_id, post_id)
            VALUES(?, ?, ?) ");
            $insert_likes->execute(array($user_id, $admin_id, $post_id));
            $message[] = 'added to likes!';
        }

    }else{
        $message[] = 'please login first!';
    }
    $_SESSION['message'] = $message;
    header('Location: ./view_post.php?post_id='. $post_id);
    exit();
}

if(isset($_POST['add_comment'])){
    
    $admin_id = $_POST['admin_id'];
    $admin_id = filter_var($admin_id, FILTER_SANITIZE_STRING);
    $user_name = $_POST['user_name'];
    $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
    $comment = $_POST['comment'];
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);

    $select_comments = $conn->prepare("SELECT * FROM  `comments` WHERE post_id= ? AND
    admin_id= ? AND user_id= ? AND user_name= ? AND comment= ?");
    $select_comments->execute(array($post_id, $admin_id, $user_id, $user_name, $comment));
    if($select_comments->rowCount() > 0){
        $message[] = 'comment already added!';
    }else{
        $insert_comments = $conn->prepare("INSERT INTO `comments` (post_id, admin_id, user_id, user_name, comment) 
        VALUES(?, ?, ?, ?, ?)");
        $insert_comments->execute(array($post_id, $admin_id, $user_id, $user_name, $comment));
        $message[] = 'new comment added!';
    }

    $_SESSION['message'] = $message;
    header('Location: ./view_post.php?post_id='. $post_id);
    exit();
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
    header('Location: ./view_post.php?post_id='. $post_id);
    exit();
}

if(isset($_POST['delete_comment'])){
    $comment_id = $_POST['comment_id'];
    $comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);


    $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE id= ?");
    $delete_comments->execute(array($comment_id));
    $message[] = 'comment deleted successfully!';
    $_SESSION['message'] = $message;
    header('Location: ./view_post.php?post_id='. $post_id);
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
        <a href="./view_post.php?post_id=<?= $post_id; ?>" class="inline-option-btn">cancel edit</a>
    </form>

</section>

<?php 
}
?>


<!-- read-post section -->
<section class="read-post">

    <h1 class="heading">read post</h1>

        <?php 
          
            $select_posts = $conn->prepare("SELECT * FROM  `posts` WHERE id= ? AND status= ?");
            $select_posts->execute(array($post_id, 'active'));

            if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
                $post_id = $fetch_posts['id'];

                $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id= ?");
                $select_comments->execute(array($post_id));
                $total_comments = $select_comments->rowCount();

                $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id= ?");
                $select_likes->execute(array($post_id));
                $total_likes = $select_likes->rowCount();

                $number_of_likes = $conn->prepare("SELECT * FROM  `likes` WHERE user_id= ? AND
                post_id= ?");
                $number_of_likes->execute(array($user_id, $post_id));
        ?>

              <form action="" method="post" class="box">
                <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                <input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
                <div class="admin">
                    <i class="fas fa-user"></i>
                    <div class="admin-info">
                        <a href="./author_posts.php?author=<?= $fetch_posts['name']; ?>"><?= $fetch_posts['name']; ?></a>
                        <p><?= $fetch_posts['date']; ?></p>
                    </div>
                </div>
                <?php 
                    if($fetch_posts['image'] != ''){
                ?>

                    <img src="./assets/uploaded_img/<?= $fetch_posts['image']; ?>" class="image" alt="">

                <?php 
                }
                ?>
                <div class="title"><?= $fetch_posts['title']; ?></div>
                <div class="content"><?= $fetch_posts['content']; ?></div>
                <div class="icons">
                    <div> <i class="fas fa-comment"></i><span><?= $total_comments; ?></span></div>
                    <a href="./view_post.php?post_id=<?= $post_id;?>"></a>
                    <button type="submit" name="like_posts"><i class="fas fa-heart"
                    style="<?php if($number_of_likes->rowCount() > 0){echo 'color: var(--red)';} ?>"></i>
                    <span><?= $total_likes; ?></span></button>
                </div>
                <a href="./category.php?category=<?= $fetch_posts['category']; ?>" class="category">
                <i class="fas fa-tag"></i><span><?= $fetch_posts['category']; ?></span></a>
              </form>


        <?php 
        }
        }else{
           echo '<p class="empty">no posts found!</p>';
        }      
        ?>
    
</section>

<!-- comments section -->
<section class="comments" style="padding-bottom: 0;">

    <p class="comment-title">add comments</p>

    <?php 
        if($user_id != ''){
            $select_posts= $conn->prepare("SELECT * FROM `posts` WHERE id= ?");
            $select_posts->execute(array($post_id));
            $fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC);    
    ?>
    <form action="" method="post" class="add-comment">
        <input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
        <input type="hidden" name="user_name" value="<?= $fetch_users['name']; ?>">
        <p><i class="fas fa-user"></i><a href="./update.php"><?= $fetch_users['name'];?></a></p>
        <textarea name="comment" class="comment-box" cols="30" rows="10"
        required placeholder="write your comment..."></textarea>
        <input type="submit" name="add_comment" value="add comment" class="inline-btn" >
    </form>
    
    <?php 
    }else{
    ?>

        <div class="add-comment">
            <p>login to add or edit comments</p>
            <div class="flex-brn">
                <a href="./login.php" class="inline-option-btn">login</a>
                <a href="./register.php" class="inline-option-btn">register</a>
            </div>
    <?php 
    }
    ?>

    
        <p class="comment-title">user comments</p>
        
        <div class="show-comments">

                <?php 
                    $select_comments= $conn->prepare("SELECT * FROM  `comments` WHERE post_id= ?");
                    $select_comments->execute(array($post_id));
                    if($select_comments->rowCount() > 0){
                    while($fetch_comments= $select_comments->fetch(PDO::FETCH_ASSOC)) {
                ?>

                    <div class="user-comments" <?php if($fetch_comments['user_id'] == $user_id)
                    {echo 'style= "order: -1;"';}?>>
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


