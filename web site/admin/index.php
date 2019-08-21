<?php require_once('../includes/config.php');
	if(!$user->is_logged_in()){ header('Location: ../login.php'); }
		if(isset($_GET['delpost'])){ 
			$stmt = $db->prepare('DELETE FROM blog_posts WHERE postID = :postID') ;
			$stmt->execute(array(':postID' => $_GET['delpost']));
				header('Location: index.php?action=deleted');
				exit;
		} 
?>
<!doctype html>
<html lang="en">
<title>Admin - blog</title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<script language="JavaScript" type="text/javascript">
  function delpost(id, title)
  {
	  if (confirm("Are you sure you want to delete '" + title + "'"))
	  {
	  	window.location.href = 'index.php?delpost=' + id;
	  }
  }
  </script>
<body>
<div id="wrapper">
<?php include('includes/menu.php');?>
	<div id="main">
		<div class="add_title"><h3>POSTS</h3></div>
			<div id="table">
				<table>
					<tr>
						<th>Title</th>
						<th>Date</th>
						<th>Action</th>
					</tr>
<?php
		try {

			$stmt = $db->query('SELECT postID, postTitle, postDate FROM blog_posts ORDER BY postID DESC');
			while($row = $stmt->fetch()){
				echo '<tr>';
				echo '<td>'.$row['postTitle'].'</td>';
				echo '<td>'.date('jS M Y', strtotime($row['postDate'])).'</td>';
				?>
					<td>
						<a href="edit-post.php?id=<?php echo $row['postID'];?>">Edit</a> | 
						<a href="javascript:delpost('<?php echo $row['postID'];?>','<?php echo $row['postTitle'];?>')">Delete</a>
					</td>
<?php 
				echo '</tr>';
			}
		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
?>
				</table>
					<a href='add-post.php'><button type='submit' name='submit'>ADD</button></a>
		</div>
	</div>
</div>
</body>
</html>
