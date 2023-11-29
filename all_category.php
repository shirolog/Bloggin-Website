<?php 
require('./assets/components/connect.php');
session_start();


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: ./login.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>all category</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./assets/components/user_header.php'); ?>
    

<!-- categories section -->
<section class="categories">

    <h1 class="heading">all categories</h1>

    <div class="box-container">

        <div class="box"><span>01</span><a href="./category.php?category=nature">nature</a></div>
        <div class="box"><span>02</span><a href="category.php?category=eduction">education</a></div>
        <div class="box"><span>03</span><a href="category.php?category=pets and animals">pets and animals</a></div>
        <div class="box"><span>04</span><a href="category.php?category=technology">technology</a></div>
        <div class="box"><span>05</span><a href="category.php?category=fashion">fashion</a></div>
        <div class="box"><span>06</span><a href="category.php?category=entertainment">entertainment</a></div>
        <div class="box"><span>07</span><a href="category.php?category=movies">movies</a></div>
        <div class="box"><span>08</span><a href="category.php?category=gaming">gaming</a></div>
        <div class="box"><span>09</span><a href="category.php?category=music">music</a></div>
        <div class="box"><span>10</span><a href="category.php?category=sports">sports</a></div>
        <div class="box"><span>11</span><a href="category.php?category=news">news</a></div>
        <div class="box"><span>12</span><a href="category.php?category=travel">travel</a></div>
        <div class="box"><span>13</span><a href="category.php?category=comedy">comedy</a></div>
        <div class="box"><span>14</span><a href="category.php?category=design and development">design and development</a></div>
        <div class="box"><span>15</span><a href="category.php?category=food and drinks">food and drinks</a></div>
        <div class="box"><span>16</span><a href="category.php?category=lifestyle">lifestyle</a></div>
        <div class="box"><span>17</span><a href="category.php?category=health and fitness">health and fitness</a></div>
        <div class="box"><span>18</span><a href="category.php?category=business">business</a></div>
        <div class="box"><span>19</span><a href="category.php?category=shopping">shopping</a></div>
        <div class="box"><span>20</span><a href="category.php?category=animations">animations</a></div>
        </div>

    </div>

</section>



<!-- footer section -->
<?php  require('./assets/components/footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>
</body>
</html>


