<?php
require_once('../includes/config.php');
	if(!$user->is_logged_in()){ header('Location: ../login.php'); }
		if(isset($_GET['delpost'])){ 
			$stmt = $db->prepare('DELETE FROM blog_comments WHERE commID = :commID') ;
			$stmt->execute(array(':commID' => $_GET['delpost']));
				header('Location: comments.php?action=deleted');
				exit;
		} 
?>
<!doctype html>
<html lang="en">
<title>Admin - comments</title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
  <script language="JavaScript" type="text/javascript">
  	function delpost(id, title)
  		{
	  		if (confirm("Are you sure you want to delete '" + title + "'"))
	  	{
	  		window.location.href = 'comments.php?delpost=' + id;
	  	}
  	}
  </script>
<body>
	<div id="wrapper">
<?php include('includes/menu.php');?>
		<div id="main">
			<div class="add_title"><h3>COMMENTS</h3></div>
				<div id="table">
					<table>
						<tr>
							<th>Comment ID</th>
							<th>Post ID</th>
							<th>Author</th>
							<th>Title</th>
							<th>Date</th>
							<th>Status</th>
							<th>Approve</th>
							<th>Unapprove</th>
							<th>Delete</th>
						</tr>
<?php
		try {
			$stmt = $db->query('SELECT postID, commID, comm_author, commDesc, commStatus, commDate FROM blog_comments ORDER BY commID DESC');
				while($row = $stmt->fetch()){
					echo '<tr>';
					echo '<td>'.$row['commID'].'</td>';
					echo '<td>'.$row['postID'].'</td>';
					echo '<td>'.$row['comm_author'].'</td>';
					echo '<td>'.$row['commDesc'].'</td>';
					echo '<td>'.date('jS M Y', strtotime($row['commDate'])).'</td>';
					echo '<td>'.$row['commStatus'].'</td>';
					echo '<td><a href=comments.php?approve='.$row['commID'].'>Approve</a></td>';
					echo '<td><a href=comments.php?unapprove='.$row['commID'].'>Unapprove</a></td>';
?>
                		<td>
							<a href="javascript:delpost('<?php echo $row['commID'];?>','<?php echo $row['commDesc'];?>')">Delete</a>
						</td>
<?php 
        if(isset($_GET['approve'])){
            $approve=$_GET['approve'];
            $stmt2 = $db->prepare("UPDATE blog_comments SET commStatus = :commentStatus WHERE commID='$approve'") ;
            $stmt2->execute(array(
                ':commentStatus' => 'approved'
            ));
        }
        if(isset($_GET['unapprove'])){
            $unapprove=$_GET['unapprove'];
            $stmt3 = $db->prepare("UPDATE blog_comments SET commStatus = :commentStatus WHERE commID='$unapprove'") ;
            $stmt3->execute(array(
                ':commentStatus' => 'unapproved'
                ));
        }
					echo '</tr>';

		}
		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
?>
			</table>
		</div>
    </div>
</div>
</body>
</html>
