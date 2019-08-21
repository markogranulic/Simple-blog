<nav>
    <div class="logo">BLOG</div>
        <ul>
            <li><a href="index.php">HOME</a></li>
        
<?php
        $stmt = $db->query('SELECT catTitle, catSlug FROM blog_cats ORDER BY catID DESC');
        while($row = $stmt->fetch()){
            echo '<li><a href="catpost.php?id='.$row['catSlug'].'">'.$row['catTitle'].'</a></li>';
}
?>
<?php

        if( $user->is_logged_in() ){ 
            if($_SESSION['memberID']==1 || $_SESSION['memberID']==2){
                echo '<li><a href=admin/index.php class ="admin">ADMIN</a></li> ';}
                echo '<li><a href=logout.php class ="active">LOGOUT</a></li>';
            }else{
                echo '<li><a href="login.php" class ="active">LOGIN</a></li>';
            }
?>
        </ul>
</nav>
