<?php 
require('../components/connect.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('Location: ./admin_login.php');
    exit();
}

if(isset($_POST['publish'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $status = 'active';

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder= '../uploaded_img/'.$image;

    $select_posts = $conn->prepare("SELECT * FROM  `posts` WHERE admin_id= ?  
    AND image= ? ");
    $select_posts->execute(array($admin_id, $image));

    if(isset($image)){
        if($select_posts->rowCount() > 0){
            $message[] = 'image name repreated!';
        }elseif($image_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    }

    if($select_posts->rowCount() > 0 && $image != ''){
        $message[] = 'please rename your image!';
    }else{
        $insert_posts = $conn->prepare("INSERT INTO  `posts` (admin_id, name, title, content, category,
        image, status) VALUES(?,?,?,?,?,?,?)");
        $insert_posts->execute(array($admin_id,$name, $title, $content, $category, $image, $status));
        $message[] = 'post published!';
    }

    $_SESSION['message'] = $message;
    header('Location:./add_posts.php');
    exit();
}

if(isset($_POST['draft'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $status = 'deactive';

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder= '../uploaded_img/'.$image;

    $select_posts = $conn->prepare("SELECT * FROM  `posts` WHERE admin_id= ?  
    AND image= ? ");
    $select_posts->execute(array($admin_id, $image));

    if(isset($image)){
        if($select_posts->rowCount() > 0){
            $message[] = 'image name repreated!';
        }elseif($image_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    }else{
        $image = ''; 
    }

    if($select_posts->rowCount() > 0 && $image != ''){
        $message[] = 'please rename your image!';
    }else{
        $insert_posts = $conn->prepare("INSERT INTO  `posts` (admin_id, name, title, content, category,
        image, status) VALUES(?,?,?,?,?,?,?)");
        $insert_posts->execute(array($admin_id,$name, $title, $content, $category, $image, $status));
        $message[] = 'draft saved!';
    }

    $_SESSION['message'] = $message;
    header('Location:./add_posts.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add post</title>

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

    <h3 class="heading">add post</h3>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="name" value="<?= $fetch_admin['name']; ?>">
        <p>post title <span>*</span></p>
        <input type="text" name="title" required placeholder="add post title"
        maxlength="100" class="box">
        <p>post content <span>*</span></p>
      <textarea name="content" class="box" cols="30" rows="10" required 
      maxlength="1000" placeholder="write your content..."></textarea>
      <p>post category <span>*</span></p>
      <select name="category" class="box" required>
        <option value="" disabled selected>-- select post category</option>
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
        <input type="file" name="image" class="box" accept="image/png, image/jpeg,image/webp">
        <div class="flex-btn">
            <input type="submit" name="publish" value="publish post"  class="btn">
            <input type="submit" name="draft" value="save draft" class="option-btn">
        </div>
    </form>

</section>



<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>
