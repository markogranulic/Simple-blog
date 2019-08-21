<?php require_once('includes/config.php');
if($user->is_logged_in()){  
        header('Location: index.php'); 
}
?>
<!doctype html>
<html lang="en">
<title>LOG IN</title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<body>
<?php require('includes/nav.php');?>
    <div id="wrepper">
        
<?php
	if(isset($_POST['submit'])){
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
                if($user->login($username,$password)){ 
                            header('Location: index.php');
                            exit;
                        } else {
                            $message = '<p class="error">Wrong username or password</p>';
                }
    }
    
?>
        <div id="login">
            <form action="" method="post">
            <fieldset class="comment_field">
            <?php if(isset($message)){ echo $message; }?>
				<div class="comment_label">
                <p><label>Username</label><input type="text" name="username" class='comment_input' value=""  /></p>
                </div>
                <div class="comment_label">
                <p><label>Password</label><input type="password" name="password" class='comment_input' value=""  /></p>
                </div>
                <div class="comment_label">
                <p><button type="submit" name="submit" class='comment_input' value="Login">Log in</button></p>
                </div>
                <div class="comment_label">
                <p>Don´t have an account? <a href='register.php'> Sign up..</a></p>
                </div>
            </form>
        </div>
    </div>
<?php require('includes/footer.php');?>
</body>
</html>
