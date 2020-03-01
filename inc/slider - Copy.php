
<div class="slidersection templete clear">
        <div id="slider">
            	 <?php
        $query = "SELECT * FROM sliders ORDER BY timestamp DESC limit 4";
        $sl = $db->select($query);
        if ($post) {
            while ($result = $sl->fetch_assoc()) {    
                #print_r($result);
            ?>
            
            <a href="#"><img src="admin/<?php echo $result['image'] ?>" alt="nature 1" title="<?php echo $result['title'] ?>" /></a>
          
        <?php }  } ?>
        </div>

</div>