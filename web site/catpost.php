<?php require('includes/config.php'); 
$stmt = $db->prepare('SELECT catID, catTitle FROM blog_cats WHERE catSlug =:catSlug');
$stmt->execute(array(':catSlug' => $_GET['id']));
$row = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<title><?php echo $row['catTitle'];?></title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<body>
<nav>
    <div class="logo"><?php echo $row['catTitle'];?></div>
        <ul>
            <li><a href="index.php">HOME</a></li>
        
<?php
        $stmt = $db->query('SELECT catID, catTitle, catSlug FROM blog_cats ORDER BY catID DESC');
        while($row = $stmt->fetch()){
            echo '<li><a href=catpost.php?id='.$row['catSlug'].'>'.$row['catTitle'].'</a></li>';
        }
        $stmt = $db->prepare('SELECT catID, catTitle FROM blog_cats WHERE catSlug =:catSlug');
        $stmt->execute(array(':catSlug' => $_GET['id']));
        $row = $stmt->fetch();

            if($row['catID'] == ''){
                header('Location: ./');
                    exit;
                }
?>
<?php
        if( $user->is_logged_in() ){
            if($_SESSION['memberID']==1){
                echo '<li><a href=admin/index.php class ="admin">ADMIN</a></li> ';}
                echo '<li><a href=logout.php class ="active">LOGOUT</a></li>';
            }else{
                echo '<li><a href="login.php" class ="active">LOGIN</a></li>';
        }
?>
        </ul>
</nav>
    <div id="wrapper">
<?php    
        try {
            $stmt = $db->prepare('SELECT blog_posts.postID, blog_posts.postTitle, blog_posts.postSlug, blog_posts.postDesc, blog_posts.postDate FROM blog_posts, blog_post_cats WHERE blog_posts.postID = blog_post_cats.postID AND blog_post_cats.catID =:catID ORDER BY postID DESC');
            $stmt->execute(array(':catID' => $row['catID']));
            while($row = $stmt->fetch()){
                echo '<div>';
                echo '<h1><a href="viewpost.php?id='.$row['postSlug'].'">'.$row['postTitle'].'</a></h1>';
                echo '<p>'.date('jS M Y', strtotime($row['postDate']));

            $stmt2 = $db->prepare('SELECT catTitle, catSlug FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID =:postID');
            $stmt2->execute(array(':postID' => $row['postID']));
            $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                echo '</p>';
                echo '<p>'.$row['postDesc'].'</p>';                
                echo '<p><a href="viewpost.php?id='.$row['postSlug'].'">Read More</a></p>';                
                echo '</div>';
            }
        }catch(PDOException $e){
        }
?>
    </div>
<?php require('includes/footer.php');?>
</body>
</html>