<?php require('includes/config.php'); 
    if(isset($_GET['delpost'])){ 
        $stmt = $db->prepare('DELETE FROM blog_comments WHERE commID = :commID') ;
        $stmt->execute(array(':commID' => $_GET['delpost']));
        header('Location: viewpost.php?action=deleted');
        exit;
    } 
        $stmt = $db->prepare('SELECT postID, postTitle, postCont, postDate FROM blog_posts WHERE postSlug = :postSlug');
        $stmt->execute(array(':postSlug' => $_GET['id']));
        $row = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<title><?php echo $row['postTitle'];?></title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
<body>
<?php require('includes/nav.php');?>
<?php
        $stmt = $db->prepare('SELECT postID, postTitle, postCont, postDate FROM blog_posts WHERE postSlug = :postSlug');
        $stmt->execute(array(':postSlug' => $_GET['id']));
        $row = $stmt->fetch();
            if($row['postID'] == ''){
                header('Location: ./');
                exit;
            }
?>
<div id="content">
	<div id="wrapper">
			<?php	
			    echo '<div>';
				echo '<h1>'.$row['postTitle'].'</h1>';
                echo '<p>Posted on '.date('jS M Y', strtotime($row['postDate'])).'</p>';
                echo '<p>'.$row['postCont'].'</p>';
                echo '<hr>';			
			    echo '</div>';
            ?>
        <?php if( $user->is_logged_in() ){ //if user is login, show comment box?>
           <div class="comment_box">
                <form method='POST' id='contactform'>
                    <fieldset class="comment_field">
                       
                        <div class="comment_label">
                            <label for='message'>Message</label>
                            <textarea  placeholder='Some text..' name="comment_title" class='comment_input' rows="5"></textarea>
                        </div>
                        <div>
                            <button type='submit' name='create_comment'>Submit</button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="comment">
                <?php //Insert comments into DB
                    if(isset($_POST['create_comment'])){
                        if(!empty($_POST['comment_title'])){  
                            try{
                                $user=$_SESSION['username'];
                                $memberID=$_SESSION['memberID'];
                                $comment_title = $_POST['comment_title'];
                                $query =$db->prepare("INSERT INTO blog_comments (postID, memberID, comm_author, commDesc, commStatus, commDate) VALUES (:postID, :memberID, :comment_author, :comment_title, :comment_status, :commDate)");
                                $query->execute(array(
                                    ':postID' => $row['postID'],
                                    ':memberID' => $memberID,
                                    ':comment_author' => $user,
                                    ':comment_title' => $comment_title,
                                    ':comment_status' => 'approved',
                                    ':commDate' => date('Y-m-d H:i:s')
                                ));
                                }catch(PDOException $e) {echo $e->getMessage();}
                        }else{echo 'Please enter alias and comment field!!';}
                    }
                ?>  
            </div>  
                <?php //Show comments from DB
                    $stmt = $db->query("SELECT * FROM blog_comments, blog_members, blog_posts WHERE blog_comments.commStatus='approved' AND blog_comments.memberID=blog_members.memberID AND blog_comments.postID=blog_posts.postID");
                        while($row = $stmt->fetch()){
                            if ($row['postSlug']==$_GET['id']){
                                echo '<div class="comment">';
                                echo '<h2>'.$row['comm_author'].'</h2>';
                                echo '<h5>Comment on '.date('jS M Y', strtotime($row['commDate'])).'</h5>';
                                echo '<p>'.$row['commDesc'].'</p>';
                                    if ($row['username']==$_SESSION['username']){
                ?>
                                    <a href="javascript:delpost('<?php echo $row['commID'];?>','<?php echo $row['commDesc'];?>')" class='delete'><i class="fas fa-trash"></i></a>
                <?php
                                }
                                echo '</div>';
                            }
                        }
                ?>
        <?php } ?>
    </div>
<?php require('includes/footer.php');?>
</div>
</body>
</html>