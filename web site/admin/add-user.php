<?php
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: ../login.php'); }
?>
<!doctype html>
<html lang="en">
<title>Admin - Add User</title>
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
			<div class="add_title"><h3>ADD USERS</h3></div>
<?php
	if(isset($_POST['submit'])){
		extract($_POST);
		if($username ==''){
			$error[] = 'Please enter the username.';
		}
		if($password ==''){
			$error[] = 'Please enter the password.';
		}
		if($passwordConfirm ==''){
			$error[] = 'Please confirm the password.';
		}
		if($password != $passwordConfirm){
			$error[] = 'Passwords do not match.';
		}
		if($email ==''){
			$error[] = 'Please enter the email address.';
		}
		if(!isset($error)){

			$hashedpassword = $user->password_hash($passwordConfirm, PASSWORD_BCRYPT);

			try {
				$stmt = $db->prepare('INSERT INTO blog_members (username,password,email, status) VALUES (:username, :password, :email, :status)') ;
				$stmt->execute(array(
					':username' => $username,
					':password' => $hashedpassword,
					':email' => $email,
					':status' => 'User'
					
				));
					header('Location: users.php?action=added');
					exit;
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		}
	}
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
?>
		<form action='' method='post'>
			<fieldset class="add_field">
				<p><label>Username</label><br />
					<input type='text' name='username' class='add_input' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p>
				<p><label>Password</label><br />
					<input type='password' name='password' class='add_input' value='<?php if(isset($error)){ echo $_POST['password'];}?>'></p>
				<p><label>Confirm Password</label><br />
					<input type='password' name='passwordConfirm' class='add_input' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>
				<p><label>Email</label><br />
					<input type='text' name='email' class='add_input' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p>
				<button type='submit' name='submit' value='Add User'>ADD</button>
			</fieldset>
		</form>
	</div>
</div>
</body>
</html>
