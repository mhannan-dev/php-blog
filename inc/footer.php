<div class="footersection templete clear">
	  <div class="footermenu clear">
		<ul>
                    <?php
                        $query = "SELECT * from pages";
                        $pages = $db->select($query);
                        if ($pages) {
                            while ($result = $pages->fetch_assoc()) {
        ?>
                        <li><a href="page.php?page_id=<?php echo $result['id'] ?>"><?php echo $result['name'] ?></a></li>
        <?php } } ?>
                        
			
		</ul>
	  </div>
	  <?php
        $query = "SELECT * FROM footer";
        $text = $db->select($query);
        if ($text) {
            while ($result = $text->fetch_assoc()) {
            	#print_r($result);
                
            ?>

	  <p>&copy;<?php echo $result['note'] ?> <?php echo date('Y') ?></p>
	<?php } } ?>
          
	</div>
	
<script type="text/javascript" src="js/scrolltop.js"></script>
</body>
</html>