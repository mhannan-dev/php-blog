<?php include './inc/header.php'; ?>
<?php
if (!isset($_GET['page_id']) || $_GET['page_id'] == NULL) {
    header("Location: 404.php"); 
    exit();
} else {
    $page_id = $_GET['page_id'];
}
?>
 <?php
            $query = "SELECT * FROM pages WHERE id ='$page_id'";

            $pages = $db->select($query);
            if ($pages) {

                while ($result = $pages->fetch_assoc()) {

                    #print_r($result);
                    ?>

<div class="contentsection contemplete clear">
	<div class="maincontent clear">
			<div class="about">
				<h2><?php echo $result['name']; ?></h2>
	
				<p><?php echo $result['body']; ?></p>
				
				
	        </div>

	</div>
	<?php include './inc/sidebar.php';?>	
</div>
<?php  } } else { header('Location:404.php');  } ?>
<?php './inc/footer.php' ?>