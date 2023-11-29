<?php 


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
    header('Location:./home.php');
    exit();
}
?>
