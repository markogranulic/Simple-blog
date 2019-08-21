<?php require_once('includes/config.php');?>
<!DOCTYPE html>
<html lang="en">
<title>SIGN UP</title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<body>
<?php require('includes/nav.php');?>>
	<div id="wrapper">
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
			$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);
				try {
					$stmt = $db->prepare('INSERT INTO blog_members (username,password,email) VALUES (:username, :password, :email)') ;
					$stmt->execute(array(
						':username' => $username,
						':password' => $hashedpassword,
						':email' => $email,
						
					));
					header('Location: login.php');
					exit;
				} catch(PDOException $e) {
					echo $e->getMessage();
				}
		}
	}
	
?>
	<div id="login">
		<form action='' method='post'>
			<fieldset class="comment_field">
			<?php
					if(isset($error)){
			foreach($error as $error){
				echo '<p class="error">'.$error.'</p>';
			}
		}
		?>
				<div class="comment_label">
					<p><label>Username</label><br />
					<input type='text' name='username' class='comment_input' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p>
				</div>
				<div class="comment_label">
					<p><label>Password</label><br />
					<input type='password' name='password' class='comment_input' value='<?php if(isset($error)){ echo $_POST['password'];}?>'></p>
				</div>	
				<div class="comment_label">
					<p><label>Confirm Password</label><br />
					<input type='password' name='passwordConfirm' class='comment_input' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>
				</div>
				<div class="comment_label">
					<p><label>Email</label><br />
					<input type='text' name='email' class='comment_input' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p><br>
					<p><button type='submit' name='submit' class='comment_input' value='Add User'>Sign up</button></p>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<?php require('includes/footer.php');?>
</body>
</html>