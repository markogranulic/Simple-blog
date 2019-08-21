<?php
require_once('../includes/config.php');
    if(!$user->is_logged_in()){ header('Location: ../login.php'); }
        if(isset($_GET['delcat'])){ 
            $stmt = $db->prepare('DELETE FROM blog_cats WHERE catID = :catID') ;
            $stmt->execute(array(':catID' => $_GET['delcat']));
                header('Location: categories.php?action=deleted');
                exit;
        } 
?>
<!doctype html>
<html lang="en">
<title>Admin - categories</title>
<?php require('includes/head.php');?>
<?php require('includes/header.php');?>
    <script language="JavaScript" type="text/javascript">
        function delcat(id, title)
        {
            if (confirm("Are you sure you want to delete '" + title + "'"))
        {
            window.location.href = 'categories.php?delcat=' + id;
        }
    }
    </script>
<body>
<div id="wrapper">
<?php include('includes/menu.php');?>
    <div id="main">
        <div class="add_title"><h3>CATEGORIES</h3></div>
			<div id="table">
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Action</th>
                    </tr>
<?php
        try {
            $stmt = $db->query('SELECT catID, catTitle, catSlug FROM blog_cats ORDER BY catTitle DESC');
                while($row = $stmt->fetch()){
                    echo '<tr>';
                    echo '<td>'.$row['catTitle'].'</td>';
?>
                    <td>
                        <a href="edit-category.php?id=<?php echo $row['catID'];?>">Edit</a> | 
                        <a href="javascript:delcat('<?php echo $row['catID'];?>','<?php echo $row['catSlug'];?>')">Delete</a>
                    </td>
<?php 
                    echo '</tr>';
                }
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
?>
                </table>
                    <a href='add-category.php'><button type='submit' name='submit'>ADD</button></a>
           </div>
    </div>
</div>
</body>
</html>