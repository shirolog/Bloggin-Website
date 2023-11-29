<?php 
require('../components/connect.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($_SESSION['admin_id'])){
    header('Location: ./admin_login.php');
    exit();
}

if(isset($_POST['delete'])){

    $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id= ?");
    $select_posts->execute(array($admin_id));
    $fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC);
    if($fetch_posts['image'] != ''){
        unlink('../uploaded_img/'.$fetch_posts['image']);
    }

    $delete_posts = $conn->prepare("DELETE FROM `posts` WHERE admin_id= ? ");
    $delete_posts->execute(array($admin_id));
    $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE admin_id= ? ");
    $delete_comments->execute(array($admin_id));
    $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE admin_id= ? ");
    $delete_likes->execute(array($admin_id));
    $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id= ? ");
    $delete_admin->execute(array($admin_id));
    header('Location: ../admin/admin_logout.php');
    exit();

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admins accounts</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

    
<!-- header section -->
<?php require('../components/admin_header.php') ?>


<!-- account section -->
<section class="account">

    <h1 class="heading">admins accounts</h1>

    <div class="box-container">

        <div class="box" style="order: -2;">
            <p>register new admin</p>
            <a href="./register_admin.php" class="option-btn">register now</a>
        </div>

        <?php 
        $select_admin = $conn->prepare("SELECT * FROM  `admin`");
        $select_admin->execute();
        if($select_admin->rowCount() > 0){
            while($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)){
            
                $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id= ? ");
                $select_posts->execute(array($fetch_admin['id']));
                $total_posts = $select_posts->rowCount();
        ?>

            <div class="box" style="<?php if($fetch_admin['id'] == $admin_id)
            {echo 'order: -1';} ?>">
                <p>id : <span><?= $fetch_admin['id']; ?></span></p>
                <p>username : <span><?= $fetch_admin['name']; ?></span></p>
                <p>total posts : <span><?= $total_posts; ?></span></p>
                <?php 
                    if($fetch_admin['id'] == $admin_id){
                ?>
                    <div class="flex-btn">

                        <a href="./update_profile.php" class="option-btn">update</a>
                        <form action="" method="post" >
                            <button type="submit" name="delete" class="delete-btn"
                            onclick="return confirm('delete the account?');">delete</button>    
                        </form>

                    </div>

                <?php 
                }
                ?>
            </div>

        <?php 
        }
        }else{
            echo '<p class="empty">no accounts found!</p>';
        }        
        ?>
    </div>

</section>



<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>