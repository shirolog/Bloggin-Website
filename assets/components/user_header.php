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

<header class="header">

    <section class="flex">

        <a href="home.php" class="logo">Blogo.</a>

        <form action="search.php" method="get" class="search-form">
            <input type="text" name="search_box" placeholder="search posts..." maxlength="100"
            required>
            <button type="submit" name="search_btn" class="fas fa-search"></button>
        </form>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <nav class="navbar">
            <a href="home.php"><i class="fas fa-angle-right"></i>
            <span>home</span></a>
            <a href="posts.php"><i class="fas fa-angle-right"></i>
            <span>posts</span></a>
            <a href="all_category.php"><i class="fas fa-angle-right"></i>
            <span>category</span></a>
            <a href="authors.php"><i class="fas fa-angle-right"></i>
            <span>authors</span></a>
            <a href="login.php"><i class="fas fa-angle-right"></i>
            <span>login</span></a>
            <a href="register.php"><i class="fas fa-angle-right"></i>
            <span>register</span></a>
        </nav>

        <div class="profile">
            <?php 
                $select_users = $conn->prepare("SELECT * FROM  `users` WHERE id= ?");
                $select_users->execute(array($user_id));
                if($select_users->rowCount() > 0){
                    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
            ?>

                <p><?= $fetch_users['name']; ?></p>
                <a href="update.php" class="btn">update profile</a>
                <div class="flex-btn">
                    <a href="login.php" class="option-btn">login</a>
                    <a href="register.php" class="option-btn">register</a>
                </div>
                <a href="http:assets/components/user_logout.php" class="delete-btn"
                onclick="return confirm('logout from the website?');">logout</a>

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
    </section>
</header>

