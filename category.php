<?php 
require('./assets/components/connect.php');
session_start();



if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: ./login.php');
    exit();
}

if(isset($_GET['category'])){
    $category = $_GET['category'];
}else{
    $category = '';
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
    header('Location:./category.php?category=' .$category);
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
    


<!-- posts-grid section -->
<section class="posts-grid">

    <h1 class="heading">post category</h1>

    <div class="box-container">
        <?php 
          
            $select_posts = $conn->prepare("SELECT * FROM  `posts` WHERE category= ? AND status= ?");
            $select_posts->execute(array($category, 'active'));

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
                        <a href="./author_posts.php?author=<?= $fetch_posts['name'];?>"><?= $fetch_posts['name']; ?></a>
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
                <a href="./view_post.php?post_id=<?= $post_id;?>" class="inline-btn">read more</a>
                <a href="./category.php?category=<?= $fetch_posts['category']; ?>" class="category">
                <i class="fas fa-tag"></i><span><?= $fetch_posts['category']; ?></span></a>
                <div class="icons">
                    <a href="./view_post.php?post_id=<?= $post_id;?>">
                    <i class="fas fa-comment"></i><span><?= $total_comments; ?></span></a>
                    <button type="submit" name="like_posts"><i class="fas fa-heart" 
                    style="<?php if($number_of_likes->rowCount() > 0){echo 'color: var(--red)';} ?>"></i>
                    <span><?= $total_likes; ?></span></button>
                </div>
              </form>

        <?php 
        }
        }else{
           echo '<p class="empty">no results found!</p>';
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


