<?php include './inc/header.php'; ?>
<?php 
	$post_id = mysqli_real_escape_string ($db->link, $_GET['id']);
	if (!isset($post_id) || $post_id == NULL) {
		header("Location:404.php");
	} else{
		$post_id = $post_id;	
	}

?>

	<div class="contentsection contemplete clear">
		<div class="maincontent clear">
			<div class="about">
		<?php
        $query = "select * from tbl_post where id=$post_id";
        $post = $db->select($query);
        if ($post) {
            while ($result = $post->fetch_assoc()) {
            	#print_r($result);
                
            ?>
                <h2><?php echo $result['title'] ?></h2>
				<h4><?php echo $fm->formatDate($result['date']); ?> By <a href="#"> <?php echo $result['author'] ?></a></h4>
				<a href="post.php?id=<?php echo $result['id'] ?>">
					<img src="admin/<?php echo $result['image'] ?>" alt="post image"/></a>
				
					<?php echo $result['body'] ?>

				<div class="relatedpost clear">
					<h2>Related articles</h2>
					
					<?php
				        $catId = $result['cat'];

				        $related_query = "SELECT * FROM tbl_post WHERE cat='$catId' ORDER BY rand() LIMIT 6";
				        $related_post = $db->select($related_query);
				        if ($related_post) {
	            			while ($r_result = $related_post->fetch_assoc()) {
                
            		?>
					<a href="post.php?id=<?php echo $result['id'] ?>">
						<img src="admin/<?php echo $r_result['image'] ?>" alt="post image"/>
					</a>
					
					
					
					<?php  } } else { echo "No related post found"; } ?>
				</div>
				<?php  } } else { header('Location:404.php');  } ?>
					 

				</div>

                
				</div>

		<div class="sidebar clear">
			
			<?php include './inc/sidebar.php'; ?>
			
		</div>
		<?php include './inc/sidebar.php';?>	
	</div>
<?php include './inc/footer.php'; ?>