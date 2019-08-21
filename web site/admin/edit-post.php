<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: ../login.php'); }
?>
<!doctype html>
<html lang="en">
<title>Admin - Edit Post</title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
  <script>
          tinymce.init({
              selector: "textarea",
              plugins: [
                  "advlist autolink lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          });
  </script>
<body>
<div id="wrapper">
<?php include('includes/menu.php');?>
<div id="main">
      <div class="add_title"><h3>EDIT POSTS</h3></div>
<?php
	if(isset($_POST['submit'])){
		extract($_POST);
		if($postID ==''){
			$error[] = 'This post is missing a valid id!.';
		}
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}
		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}
		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}
		if(!isset($error)){
			try {
				$postSlug = slug($postTitle);
				$stmt = $db->prepare('UPDATE blog_posts SET postTitle = :postTitle, postSlug = :postSlug, postDesc = :postDesc, postCont = :postCont WHERE postID = :postID') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postSlug' => $postSlug,
					':postDesc' => $postDesc,
					':postCont' => $postCont,
					':postID' => $postID
				));
					$stmt = $db->prepare('DELETE FROM blog_post_cats WHERE postID = :postID');
					$stmt->execute(array(':postID' => $postID));
					if(is_array($catID)){
						foreach($_POST['catID'] as $catID){
							$stmt = $db->prepare('INSERT INTO blog_post_cats (postID,catID)VALUES(:postID,:catID)');
							$stmt->execute(array(
								':postID' => $postID,
								':catID' => $catID
							));
						}
					}
				header('Location: index.php?action=updated');
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

			$stmt = $db->prepare('SELECT postID, postTitle, postDesc, postCont FROM blog_posts WHERE postID = :postID') ;
			$stmt->execute(array(':postID' => $_GET['id']));
			$row = $stmt->fetch(); 
		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
?>
	<form action='' method='post'>
		<fieldset class="add_field">
			<input type='hidden' name='postID' class='add_input' value='<?php echo $row['postID'];?>'>
			<p><label>Title</label><br />
			<input type='text' name='postTitle' class='add_input' value='<?php echo $row['postTitle'];?>'></p>
			<p><label>Description</label><br />
			<textarea name='postDesc' cols='60' rows='10' class='add_input'><?php echo $row['postDesc'];?></textarea></p>
			<p><label>Content</label><br />
			<textarea name='postCont' cols='60' rows='10' class='add_input'><?php echo $row['postCont'];?></textarea></p>
			<fieldset>
    			<legend>Categories</legend>
<?php
	$checked = null;
    $stmt2 = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle');
    while($row2 = $stmt2->fetch()){
        $stmt3 = $db->prepare('SELECT catID FROM blog_post_cats WHERE catID = :catID AND postID = :postID') ;
        $stmt3->execute(array(':catID' => $row2['catID'], ':postID' => $row['postID']));
        $row3 = $stmt3->fetch(); 
        	echo "<input type='checkbox' name='catID[]' value='".$row2['catID']."' $checked> ".$row2['catTitle']."<br />";
    }
?>
				<br><button type='submit' name='submit' value='Update'>UPDATE</button>
			</fieldset>
		</fieldset>
	</form>
	</div>
</div>
</body>
</html>	
