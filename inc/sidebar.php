<div class="samesidebar clear">
    				<h2>Categories</h2>
					<ul>
						<?php
					        $cat_query = "SELECT * FROM tbl_category";
					        $cat_post = $db->select($cat_query);
					        if ($cat_post) {
				            while ($cat_pst_result = $cat_post->fetch_assoc()) {

			                
			            ?>
						<li><a href="cat_posts.php?cat_post=<?php echo $cat_pst_result['id'] ?>"><?php echo $cat_pst_result['name'] ?></a></li>
						<?php } } else { ?>
								<li>No Category Created</li>
						<?php } ?>
					</ul>
    
				<h2>Latest articles</h2>
			<?php
        		$query = "select * from tbl_post limit 5";
        		$post = $db->select($query);
        		if ($post) {
            		while ($result = $post->fetch_assoc()) {

                
            	?>
            		<div class="popular clear">
						<h3><a href="post.php?id=<?php echo $result['id'] ?>"><?php echo $result['title']; ?></a></h3>
						 <a href="post.php?id=<?php echo $result['id'] ?>">
                        <img src="admin/<?php echo $result['image'] ?>" alt="post image"/></a>

						 <?php echo $fm->textShorten($result['body'], 150) ?>
                    
					</div>

			<?php  } } else { header('Location:404.php');  } ?>
					
					
					
					
	
			</div>


