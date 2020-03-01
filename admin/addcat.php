<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
       
        <div class="grid_10">
		
            <div class="box round first grid">
                <h2>Add New Category</h2>
               <div class="block copyblock"> 
                <?php 


                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $name  = $_POST['name'];
                        $name = mysqli_real_escape_string ($db->link, $name);
                        #print_r($name);

                        if (empty($name)) {
                            echo "<span class='error'>Field must not be empty</span>";
                        }
                        else{
                            $query  = "INSERT INTO tbl_category(name) VALUES ('$name')";
                            $cat_insert = $db->insert($query);
                                if ($cat_insert) {
                                    echo "<span class='success'>Category inserted successfully</span>";           
                                } else{
                                    echo "<span class='error'>Category not inserted successfully</span>";           
                                }

                        }
                    }
                ?>
                 <form method="post" action="">
                    <table class="form">					
                        <tr>
                            <td>
                                <input name="name" type="text" placeholder="Enter Category Name..." class="medium" />
                            </td>
                        </tr>
						<tr> 
                            <td>
                                <input type="submit" name="submit" Value="Save" />
                            </td>
                        </tr>
                    </table>
                </form>

                </div>
            </div>
        </div>
<?php include '../admin/inc/footer.php'; ?>
