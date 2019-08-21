<?php
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: ../login.php'); }
?>
<!doctype html>
<html lang="en">
<title>Admin - Add Post</title>
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
			<div class="add_title"><h3>ADD POST</h3></div>
<?php
	if(isset($_POST['submit'])){
		extract($_POST);
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
				$stmt = $db->prepare('INSERT INTO blog_posts (postTitle,postSlug,postDesc,postCont,postDate) VALUES (:postTitle, :postSlug, :postDesc, :postCont, :postDate)') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postSlug' => $postSlug,
					':postDesc' => $postDesc,
					':postCont' => $postCont,
					':postDate' => date('Y-m-d H:i:s')
					
				));
				$postID = $db->lastInsertId();
				if(is_array($catID)){
					foreach($_POST['catID'] as $catID){
						$stmt = $db->prepare('INSERT INTO blog_post_cats (postID,catID)VALUES(:postID,:catID)');
						$stmt->execute(array(
							':postID' => $postID,
							':catID' => $catID
						));
					}
				}
				header('Location: index.php?action=added');
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
			<p><label>Title</label><br />
				<input type='text' name='postTitle' class='add_input' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>
			<p><label>Description</label><br />
				<textarea name='postDesc' cols='60' rows='10' class='add_input'><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>
			<p><label>Content</label><br />
				<textarea name='postCont' cols='60' rows='10' class='add_input'><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>
			
<?php    
    $stmt2 = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle');
    while($row2 = $stmt2->fetch()){
        if(isset($_POST['catID'])){
            if(in_array($row2['catID'], $_POST['catID'])){
               $checked="checked='checked'";
            }else{
               $checked = null;
            }
        }
        echo "<input type='checkbox' name='catID[]' value='".$row2['catID']."'> ".$row2['catTitle']."<br />";
    }
?>
			<br>
			<button type='submit' name='submit'>ADD</button>
		</fieldset>
	</form>
</div>
</body>
</html>