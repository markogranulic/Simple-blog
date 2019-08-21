<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: ../login.php'); }
?>
<!doctype html>
<html lang="en">
<title>Admin - Edit Category</title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<body>
<div id="wrapper">
<?php include('includes/menu.php');?>
    <div id="main">
      <div class="add_title"><h3>EDIT POSTS</h3></div>
<?php
    if(isset($_POST['submit'])){
        extract($_POST);
        if($catID ==''){
            $error[] = 'This post is missing a valid id!.';
        }
        if($catTitle ==''){
            $error[] = 'Please enter the title.';
        }
        if(!isset($error)){
            try {
                $catSlug = slug($catTitle);
                $stmt = $db->prepare('UPDATE blog_cats SET catTitle = :catTitle, catSlug = :catSlug WHERE catID = :catID') ;
                $stmt->execute(array(
                    ':catTitle' => $catTitle,
                    ':catSlug' => $catSlug,
                    ':catID' => $catID
                ));
                header('Location: categories.php?action=updated');
                exit;
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    if(isset($error)){
        foreach($error as $error){
            echo $error.'<br />';
        }
    }
       try {
            $stmt = $db->prepare('SELECT catID, catTitle FROM blog_cats WHERE catID = :catID') ;
            $stmt->execute(array(':catID' => $_GET['id']));
            $row = $stmt->fetch(); 
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
?>
        <form action='' method='post'>
            <fieldset class="add_field">
                    <input type='hidden' name='catID' class='add_input' value='<?php echo $row['catID'];?>'>
                <p><label>Title</label><br />
                    <input type='text' name='catTitle' class='add_input' value='<?php echo $row['catTitle'];?>'></p>
                <button type='submit' name='submit' value='Update'>UPDATE</button>
            </fieldset>
        </form>
    </div>
</div>
</body>
</html>    
