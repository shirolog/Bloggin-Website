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


if(isset($_POST['save'])){

    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $status =  $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);

    $update_posts = $conn->prepare("UPDATE `posts` SET title= ?, content= ?, category= ?, status= ?
    WHERE id= ? ");
    $update_posts->execute(array($title, $content, $category, $status, $get_id));
    $message[] = 'post updated!';

    $old_image = $_POST['old_image'];
    $old_image = filter_var($old_image, FILTER_SANITIZE_STRING);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder= '../uploaded_img/'.$image;

    $select_posts = $conn->prepare("SELECT * FROM  `posts` WHERE admin_id= ?  
    AND image= ? ");
    $select_posts->execute(array($admin_id, $image));

    if(!empty($image)){
        if($select_posts->rowCount() > 0){
            $message[] = 'please rename your image!';
        }elseif($image_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            $update_posts = $conn->prepare("UPDATE `posts` SET image= ? WHERE id= ?");
            $update_posts->execute(array($image, $get_id));
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'image updated!';
            if($old_image != $image AND $old_image != ''){
                unlink('../uploaded_img/'. $old_image);
            }
        }
    }else{
        $image = '';
    }
    $_SESSION['message'] = $message;
    header('Location: ./edit_post.php?post_id=' . $get_id);
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

if(isset($_POST['delete_image'])){

    $empty_image= '';

    $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE id= ?");
    $select_posts->execute(array($get_id));
    $fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC);
    if($fetch_posts['image'] != ''){
        unlink('../uploaded_img/'.$fetch_posts['image']);
    }
    $update_posts = $conn->prepare("UPDATE `posts` SET image= ? WHERE id= ?");
    $update_posts->execute(array($empty_image, $get_id));
    $message[] = 'image deleted!';
    $_SESSION['message'] = $message;
    header('Location: ./edit_post.php?post_id=' . $get_id);
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit post</title>

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

<!-- post-edit section -->
<section class="post-edit">

    <h3 class="heading">edit post</h3>

    <?php 
        $select_posts = $conn->prepare("SELECT * FROM  `posts` WHERE id= ? AND admin_id= ?");
        $select_posts->execute(array($get_id, $admin_id));
        if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){

        ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="post_id" value="<?= $fetch_posts['id'];?>">
                <input type="hidden" name="old_image" value="<?= $fetch_posts['image']; ?>">
                <input type="hidden" name="name" value="<?= $fetch_admin['name']; ?>">
                <p>post status <span>*</span></p>
                <select name="status" class="box" required>
                    <option value="<?= $fetch_posts['status']; ?>"><?= $fetch_posts['status'];?></option>
                    <option value="active">active</option>
                    <option value="deactive">deactive</option>
                </select>
                <p>post title <span>*</span></p>
                <input type="text" name="title" required placeholder="add post title"
                maxlength="100" class="box" value="<?= $fetch_posts['title']; ?>">
                <p>post content <span>*</span></p>
            <textarea name="content" class="box" cols="30" rows="10" required 
            maxlength="1000" placeholder="write your content..."><?= $fetch_posts['content']; ?></textarea>
            <p>post category <span>*</span></p>
            <select name="category" class="box" required>
                <option value="<?= $fetch_posts['category']; ?>" 
                selected><?= $fetch_posts['category']; ?></option>
                <option value="nature">nature</option>
                <option value="education">education</option>
                <option value="pets and animals">pets and animals</option>
                <option value="technology">technology</option>
                <option value="fashion">fashion</option>
                <option value="entertainment">entertainment</option>
                <option value="movies and animations">movies</option>
                <option value="gaming">gaming</option>
                <option value="music">music</option>
                <option value="sports">sports</option>
                <option value="news">news</option>
                <option value="travel">travel</option>
                <option value="comedy">comedy</option>
                <option value="design and development">design and development</option>
                <option value="food and drinks">food and drinks</option>
                <option value="lifestyle">lifestyle</option>
                <option value="personal">personal</option>
                <option value="health and fitness">health and fitness</option>
                <option value="business">business</option>
                <option value="shopping">shopping</option>
                <option value="animations">animations</option>
            </select>
                <p>post image</p>
                <input type="file" name="image" class="box" 
                accept="image/png, image/jpeg,image/webp">
                <?php 
                    if($fetch_posts['image'] != ''){
                ?>

                <img src="../uploaded_img/<?= $fetch_posts['image']; ?>" class="image" alt="">
                <input type="submit" value="delete image" name="delete_image" class="inline-delete-btn">
                <?php 
                }                
                ?>
                <div class="flex-btn">
                    <input type="submit" name="save" value="save post"  class="btn">
                    <a href="./view_posts.php" class="option-btn">go back</a>
                    <button type="submit" name="delete" 
                    onclick="return confirm('delete this post?');" class="delete-btn">delete</button>
                </div>
            </form>
        <?php 
        }
        }else{
            echo '<p class="empty">no posts added yet!</p>';
        }        
        ?>
           
</section>



<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>