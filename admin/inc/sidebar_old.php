
   <div class="grid_2">
            <div class="box sidemenu">
                <div class="block" id="section-menu">
                    <ul class="section menu">
                       <?php if (Session::get('userRole') == '0') { ?>
                        
                       
                        <li><a class="menuitem">Social</a>
                            <ul class="submenu">
                                <li><a href="social.php">Social Media</a></li>
                                
                                <li><a href="copyright.php">Copyright</a></li>
                            </ul>
                        </li>
                        
                        <li><a class="menuitem">Site info</a>
                            <ul class="submenu">
                                <li><a href="titleslogan.php">Add</a></li>
                                
                                <li><a href="site_info_list.php">Informations</a></li>
                            </ul>
                        </li>

			            <li><a class="menuitem">Slider</a>
                            <ul class="submenu">
                                <li><a href="add_slider.php">New</a></li>
                                
                                <li><a href="slider_list.php">All slider</a></li>
                            </ul>
                        </li>			
                         <li><a class="menuitem">Pages</a>
                            <ul class="submenu">
                                 <?php if (Session::get('userRole') == '0') { ?>
                                        <li><a href="add_new_page.php">Add new page</a></li>
                                    <?php } ?>

                                
                                <?php
                        $query = "SELECT * from pages";
                        $pages = $db->select($query);
                        if ($pages) {
                            
                            while ($result = $pages->fetch_assoc()) {
                               
                                #print_r($result);
                        ?>

                                <li><a href="page.php?page_id=<?php echo $result['id'] ?>"><?php echo $result['name'] ?></a></li>
                            <?php } } ?>
                            </ul>
                        </li>
                         
                         <?php }  ?>
                        <li><a class="menuitem">Category Option</a>
                            <ul class="submenu">
                                <li><a href="addcat.php">Add Category</a> </li>
                                <li><a href="catlist.php">Category List</a> </li>
                            </ul>
                        </li>

                        <li><a class="menuitem">Post Option</a>
                            <ul class="submenu">
                                <li><a href="addpost.php">Add Post</a> </li>
                                <li><a href="post_list.php">Post List</a> </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>