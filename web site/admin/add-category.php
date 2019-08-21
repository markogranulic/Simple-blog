<?php 
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: ../login.php'); }
?>
<!doctype html>
<html lang="en">
<title>Admin - Add Category</title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<body>
    <div id="wrapper">
<?php include('includes/menu.php');?>
        <div id="main">
            <div class="add_title"><h3>ADD CATEGORY</h3></div>
<?php
    if(isset($_POST['submit'])){
        extract($_POST);
        if($catTitle ==''){
            $error[] = 'Please enter the Category.';
        }
        if(!isset($error)){
            try {
                $catSlug = slug($catTitle);
                $stmt = $db->prepare('INSERT INTO blog_cats (catTitle,catSlug) VALUES (:catTitle, :catSlug)') ;
                $stmt->execute(array(
                    ':catTitle' => $catTitle,
                    ':catSlug' => $catSlug
                ));
                    header('Location: categories.php?action=added');
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
                    <h3>TITLE</h3>
                        <input type='text' name='catTitle' class='add_input' value='<?php if(isset($error)){ echo $_POST['catTitle'];}?>'><br><br>
                        <button type='submit' name='submit'> ADD </button>
               </fieldset>
            </form>
        </div>
    </div>
</body>
</html>