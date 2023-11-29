<?php 
require('../components/connect.php');
session_start();

$admin_id= $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('Location:admin_login.php');
    exit();
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



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view posts</title>

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

<!-- show-posts section -->
<section class="show-posts">

    <h1 class="heading">your posts</h1>

    <form action="search_page.php" method="post" class="search-form">
        <input type="text" name="search_box" placeholder="search post..." required
        maxlength="100">
        <button type="submit" class="fas fa-search" name="search-btn"></button>
    </form>

    <div class="box-container">
        <?php 
        $select_posts = $conn->prepare("SELECT * FROM  `posts` WHERE admin_id= ?");
        $select_posts->execute(array($admin_id));
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
                    <a href="./read_posts.php?post_id=<?= $post_id; ?>" class="btn">view post</a>
            </form>

        <?php 
        }
        }else{
            echo '<p class="empty">no posts added yet!</p>';
        }        
        ?>
        
    </div>

</section>



<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>