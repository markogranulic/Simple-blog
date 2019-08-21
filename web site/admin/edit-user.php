<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: ../login.php'); }
?>
<!doctype html>
<html lang="en">
<title>Admin - Edit User</title>
<?php require('includes/head.php');?>
<body>
<header>
	<div class="headerap">
		<p>ADMIN PANEL</p>
	</div>
</header>
<div id="wrapper">
<?php include('includes/menu.php');?>
		<div id="main">
      <div class="add_title"><h3>EDIT USERS</h3></div>
<?php
	if(isset($_POST['submit'])){
		extract($_POST);
		if($username ==''){
			$error[] = 'Please enter the username.';
		}
		if( strlen($password) > 0){
			if($password ==''){
				$error[] = 'Please enter the password.';
			}
			if($passwordConfirm ==''){
				$error[] = 'Please confirm the password.';
			}
			if($password != $passwordConfirm){
				$error[] = 'Passwords do not match.';
			}
		}
			if($email ==''){
			$error[] = 'Please enter the email address.';
		}
		if(!isset($error)){
			try {
				if(isset($password)){
					$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, password = :password, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':password' => $hashedpassword,
						':email' => $email,
						':memberID' => $memberID
					));
				} else {
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':email' => $email,
						':memberID' => $memberID
					));

				}
				header('Location: users.php?action=updated');
				exit;
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		}
	}
?>
<?php
				if(isset($error)){
					foreach($error as $error){
						echo $error.'<br />';
					}
				}
			try {
				$stmt = $db->prepare('SELECT memberID, username, email FROM blog_members WHERE memberID = :memberID') ;
				$stmt->execute(array(':memberID' => $_GET['id']));
				$row = $stmt->fetch(); 
			} catch(PDOException $e) {
					echo $e->getMessage();
			}
?>
			<form action='' method='post'>
				<fieldset class="add_field">
					<input type='hidden' name='memberID'class='add_input'  value='<?php echo $row['memberID'];?>'>
					<p><label>Username</label><br />
					<input type='text' name='username' class='add_input' value='<?php echo $row['username'];?>'></p>
					<p><label>Password (only to change)</label><br />
					<input type='password' name='password' class='add_input' value=''></p>
					<p><label>Confirm Password</label><br />
					<input type='password' name='passwordConfirm' class='add_input' value=''></p>
					<p><label>Email</label><br />
					<input type='text' name='email' class='add_input' value='<?php echo $row['email'];?>'></p>
					<button type='submit' name='submit' value='Update User'>UPDATE</button>
				</fieldset>
			</form>
		</div>
</div>
</body>
</html>	
