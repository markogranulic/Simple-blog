<?php require('includes/config.php');?>
<!DOCTYPE html>
<html lang="en">
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<body>
<?php require('includes/nav.php');?>
	<div id="wrapper">
		<?php
			try {
				$stmt = $db->query('SELECT postID, postTitle,postSlug, postDesc, postDate FROM blog_posts ORDER BY postID DESC');
					while($row = $stmt->fetch()){
						echo '<div>';
						echo '<h1><a href="viewpost.php?id='.$row['postSlug'].'">'.$row['postTitle'].'</a></h1>';
						echo '<p>'.date('jS M Y', strtotime($row['postDate'])).'</p>';

				$stmt2 = $db->prepare('SELECT catTitle, catSlug FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID =:postID');
				$stmt2->execute(array(':postID' => $row['postID']));
					$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
						echo '</p>';
						echo '<p>'.$row['postDesc'].'</p>';				
						echo '<p><a href="viewpost.php?id='.$row['postSlug'].'">Read More</a></p>';		
						echo '</div>';
					}

				} catch(PDOException $e){echo $e->getMessage();}
		?>
	</div>
<?php require('includes/footer.php');?>	
</body>
</html>