


<div class="slidersection templete clear">
        <div id="slider">
        <?php
        $query = "SELECT * FROM sliders order by id desc limit 4";
        $sl = $db->select($query);
        //print_r($sl);
        if ($sl) {
            while ($result = $sl->fetch_assoc()) {    
                #print_r($result);
            ?>
            
            <a href="#"><img src="admin/<?php echo $result['image'] ?>" alt="<?php echo $result['title'] ?>" title="<?php echo $result['title'] ?>" /></a>
          
        <?php }  } ?>
        </div>