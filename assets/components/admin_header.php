<header class="header">

    <a href="dashboard.php" class="logo">Admin <span>Panel</span></a>

    <div class="profile">
        <?php 
        $select_admin= $conn->prepare("SELECT * FROM `admin` WHERE id= ?");
        $select_admin->execute(array($admin_id));
        $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

        ?>
        <p><?= $fetch_admin['name']; ?></p>
        <a href="update_profile.php" 
        class="btn">update profile</a>
    </div>

    <nav class="navbar">
        <a href="dashboard.php">
        <i class="fas fa-home"></i>home</a>
        <a href="add_posts.php">
        <i class="fas fa-pen"></i>add post</a>
        <a href="view_posts.php">
        <i class="fas fa-eye"></i>view posts</a>
        <a href="admin_accounts.php">
        <i class="fas fa-user"></i>accounts</a>
        <a href="admin_logout.php" 
        onclick="return confirm('logout from the website?');"><i class="fas fa-right-from-bracket"></i>
        <span style="color: var(--red);">logout</span></a>
    </nav>

    <div class="flex-btn">
        <a href="admin_login.php" 
        class="option-btn">login</a>
        <a href="register_admin.php" 
        class="option-btn">register</a>
    </div>
</header>
<div id="menu-btn" class="fas fa-bars"></div>