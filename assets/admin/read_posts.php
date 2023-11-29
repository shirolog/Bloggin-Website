<?php 
require('../components/connect.php');
session_start();

$admin_id= $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('Location:admin_login.php');
    exit();
}

if(!isset($_GET['post_id'])){
    header('Location:./view_posts.php');
    exit();
}else{
    $get_id = $_GET['post_id'];
}



if(isset($_POST['delete'])){

    $delete_id = $_POST['post_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE id= ?");
    $select_posts->execute(array($delete_id));
    $fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC);
    if($fetch_posts['image'] != ''){
        unlink('../uploaded_img/'.$fetch_posts['image']);
    }

    $delete_comments = $conn->prepare("DELETE FROM  `comments` WHERE post_id= ?");
    $delete_comments->execute(array($delete_id));
    $delete_likes = $conn->prepare("DELETE FROM  `likes` WHERE post_id= ?");
    $delete_likes->execute(array($delete_id));
    $delete_posts = $conn->prepare("DELETE FROM  `posts` WHERE id= ?");
    $delete_posts->execute(array($delete_id));
    $message[] = 'post deleted successfully!';
    $_SESSION['message'] = $message;
    header('Location:./view_posts.php');
    exit();
}

if(isset($_POST['delete_comment'])){

    $comment_id = $_POST['comment_id'];
    $comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);

    $delete_comments = $conn->prepare("DELETE FROM  `comments` WHERE id= ?");
    $delete_comments->execute(array($comment_id));

    $message[] = 'comment deleted!';
    $_SESSION['message'] = $message;
    header('Location: ./read_posts.php?post_id=' . $get_id);
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

<!-- read-post section -->
<section class="read-post">

    <h1 class="heading">read post</h1>

        <?php 
            $select_posts = $conn->prepare("SELECT * FROM  `posts` WHERE id= ? AND admin_id= ?");
            $select_posts->execute(array($get_id, $admin_id));
            if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
                $post_id = $fetch_posts['id'];

                $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE
                post_id= ?");
                $select_comments->execute(array($post_id));
                $total_comments =$select_comments->rowCount();

                $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE
                post_id= ?");
                $select_likes->execute(array($post_id));
                $total_likes =$select_likes->rowCount();
        ?>

            <form action="" method="post" class="box">
                <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                <div class="status" style="background: <?php if($fetch_posts['status'] == 'active') 
                {echo 'limegreen';}else{echo 'coral';}?>;"><?= $fetch_posts['status']; ?></div>
                <?php 
                    if($fetch_posts['image'] != ''){
                ?>
                    <img src="../uploaded_img/<?= $fetch_posts['image']; ?>" class="image" alt="">
                <?php 
                }
                ?>
                <div class="post-title"><?= $fetch_posts['title']; ?></div>
                <div class="post-content"><?= $fetch_posts['content']; ?></div>
                <div class="icons">
                    <div><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></div>
                    <div><i class="fas fa-heart"></i><span><?= $total_likes; ?></span></div>
                </div>

                <div class="flex-btn">
                    <a href="./edit_post.php?post_id=<?= $post_id ?>" 
                    class="option-btn">edit</a>
                    <button type="submit" name="delete" 
                    onclick="return confirm('delete this post?');" class="delete-btn">delete</button>
                </div>
                <div class="post-category"><i class="fas fa-tag"></i> <span><?= $fetch_posts['category']; ?></span></div>
            </form>

        <?php 
        }
        }else{
            echo '<p class="empty">no posts added yet!</p>';
        }        
        ?>
</section>

<!-- comments section -->
<section class="comments">

    <p class="comment-title">post comments</p>

    <div class="box-container">
        <?php 
        $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id= ?");
        $select_comments->execute(array($get_id));
        if($select_comments->rowCount() > 0){
            while($fetch_comments= $select_comments->fetch(PDO::FETCH_ASSOC)){
        ?>

            <div class="box">
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
<script>
    //ヘッダーナビゲーション設定
  const $menuBtn = document.querySelector("#menu-btn");
  const $header = document.querySelector(".header");

  $menuBtn.addEventListener("click", () => {
    $header.classList.toggle("active");
    $menuBtn.classList.toggle("fa-times");
  });

  window.addEventListener("scroll", () => {
    $header.classList.remove("active");
    $menuBtn.classList.remove("fa-times");
  });
</script>
</body>
</html>