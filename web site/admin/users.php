<?php
require_once('../includes/config.php');
	if(!$user->is_logged_in()){ header('Location: ../login.php'); }
		if(isset($_GET['deluser'])){ 
			if($_GET['deluser'] !='1'){
				$stmt = $db->prepare('DELETE FROM blog_members WHERE memberID = :memberID') ;
				$stmt->execute(array(':memberID' => $_GET['deluser']));
					header('Location: users.php?action=deleted');
					exit;
				}
			} 
?>
<!doctype html>
<html lang="en">
<title>Admin - users</title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<script language="JavaScript" type="text/javascript">
  function deluser(id, title)
  {
	  if (confirm("Are you sure you want to delete '" + title + "'"))
	  {
	  	window.location.href = 'users.php?deluser=' + id;
	  }
  }
  </script>
<body>
	<div id="wrapper">
<?php include('includes/menu.php');?>
	<div id="main">
		<div class="add_title"><h3>USERS</h3></div>
			<div id="table">
				<table>
					<tr>
						<th>Username</th>
						<th>Email</th>
						<th>Action</th>
					</tr>
<?php
		try {
			$stmt = $db->query('SELECT memberID, username, email FROM blog_members ORDER BY username');
			while($row = $stmt->fetch()){
				echo '<tr>';
				echo '<td>'.$row['username'].'</td>';
				echo '<td>'.$row['email'].'</td>';
?>
				<td>
					<a href="edit-user.php?id=<?php echo $row['memberID'];?>">Edit</a> 
						<?php if($row['memberID'] != 1){?>
						| <a href="javascript:deluser('<?php echo $row['memberID'];?>','<?php echo $row['username'];?>')">Delete</a>
<?php } ?>
				</td>
<?php 
				echo '</tr>';
			}
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
?>
			</table>
				<a href='add-user.php'><button type='submit' name='submit'>ADD</button></a>
		</div>
	</div>
</div>
</body>
</html>
