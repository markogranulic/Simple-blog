<footer>
    <div class="f1">
        <h2>Recent Posts</h2>
        <hr>
            <?php
            $stmt = $db->query('SELECT postTitle, postSlug FROM blog_posts ORDER BY postID DESC LIMIT 5');
            while($row = $stmt->fetch()){
                echo '<p><a href="viewpost.php?id='.$row['postSlug'].'">'.$row['postTitle'].'</a></p>';
            }
            ?>
    </div>
    <div class="f2">
        <h2>Contact</h2>  
        <h4>Blog street 88</h4>
        <h4>info@blog.com</h4>
        <h4><a href="https://www.google.com/maps/place/Mihajla+Pupina+4,+Banja+Luka+78000/@44.7735727,17.1822788,17z/data=!3m1!4b1!4m5!3m4!1s0x475e031d95c6ac83:0x3dc4382e0b0ae006!8m2!3d44.7735727!4d17.1844675" target="blank">We are here!</a></h4>
        <h4>Copyright&copy;Marko Granulić</h4>
    </div>
</footer>