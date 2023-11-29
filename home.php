<?php 
require('./assets/components/connect.php');
session_start();


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: ./login.php');
    exit();
}

require('./like_post.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>



<!-- header section -->
<?php require('./assets/components/user_header.php'); ?>
    
<!-- home-grid section -->
<section class="home-grid">

    <div class="box-container">

    
    <div class="box">
            <?php 
                $select_users = $conn->prepare("SELECT * FROM  `users` WHERE id= ?");
                $select_users->execute(array($user_id));
                if($select_users->rowCount() > 0){
                    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);

                    $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE  user_id= ?");
                    $select_comments->execute(array($user_id));
                    $total_comments = $select_comments->rowCount();

                    $select_likes = $conn->prepare("SELECT * FROM `likes`  WHERE user_id= ?");
                    $select_likes->execute(array($user_id));
                    $total_likes = $select_likes->rowCount();
            ?>

                <p>welcome  <span><?= $fetch_users['name']; ?></span></p>
                <p>total comments : <span><?= $total_comments; ?></span></p>
                <p>total likes : <span><?= $total_likes; ?></span></p>
                <a href="update.php"
                class="btn">update profile</a>
                <div class="flex-btn">
                    <a href="user_likes.php" class="option-btn">likes</a>
                    <a href="user_comments.php" class="option-btn">comments</a>
                </div>

            <?php 
            }else{
            ?>
                <p>please login first!</p>
                <div class="flex-btn">
                    <a href="login.php" class="option-btn">login</a>
                    <a href="register.php" class="option-btn">register</a>
                </div>
            <?php 
            }
            ?>          
        </div>

        <div class="box">
            <p>categories</p>
            <div class="flex-box">

                <a href="./category.php?category=nature" class="links">nature</a>
                <a href="./category.php?category=education" class="links">education</a>
                <a href="./category.php?category=business" class="links">business</a>
                <a href="./category.php?category=travel" class="links">travel</a>
                <a href="./category.php?category=news" class="links">news</a>
                <a href="./category.php?category=gaming" class="links">gaming</a>
                <a href="./category.php?category=sports" class="links">sports</a>
                <a href="./category.php?category=design" class="links">design</a>
                <a href="./category.php?category=fashion" class="links">fashion</a>
                <a href="./category.php?category=persional" class="links">persional</a>
                <a href="./all_category.php" class="btn">view all</a>
            </div>
        </div>

        <div class="box">
            <p>authors</p>
            <div class="flex-box">

                <?php 
                $select_admin= $conn->prepare("SELECT DISTINCT name FROM `admin` LIMIT 10");
                $select_admin->execute();
                if($select_admin->rowCount() > 0){
                    while($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)){
                ?>
    
                    <a href="./author_posts.php?author=<?= $fetch_admin['name']; ?>" class="links"><?= $fetch_admin['name']?></a>
    
                <?php 
                }
                }else{
                    echo '<p class="empty">no authors found!</p>';
                }
                ?>
                <a href="./authors.php" class="btn">view all</a>
            </div>
        </div>

    </div>

</section>

<!-- posts-grid section -->
<section class="posts-grid">

    <h1 class="heading">latest posts</h1>

    <div class="box-container">
        <?php 
        $select_posts = $conn->prepare("SELECT * FROM  `posts` WHERE status= ?
        LIMIT 9");
        $select_posts->execute(array('active'));
        if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
                $post_id = $fetch_posts['id'];

                $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id= ?");
                $select_comments->execute(array($post_id));
                $total_comments = $select_comments->rowCount();

                $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id= ? ");
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
           echo '<p class="empty">no posts found!</p>';
        }        
        ?>
    </div>


        <div style="margin-top: 2rem; text-align:center;">
            <a href="./posts.php" class="inline-btn">view all</a>
        </div>



</section>



<!-- footer section -->
<?php  require('./assets/components/footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>
</body>
</html>


