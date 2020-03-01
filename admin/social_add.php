<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
       
        <div class="grid_10">
		
            <div class="box round first grid">
                <h2>Add Social Link</h2>
               <div class="block copyblock"> 
                <?php 


                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $fb = $fm->validation($_POST['fb']);
                        $tw = $fm->validation($_POST['tw']);
                        $ln = $fm->validation($_POST['ln']);
                        #print_r($name);

                        if ($fb == "" || $tw == "" || $ln == "") {
                            echo "<span class='error'>Field must not be empty</span>";
                        }
                        else{
                            $query  = "INSERT INTO tbl_social(fb,tw,ln) VALUES ('$fb','$tw','$ln')";
                            $cat_insert = $db->insert($query);
                                if ($cat_insert) {
                                    echo "<span class='success'>Social link inserted successfully</span>";           
                                } else{
                                    echo "<span class='error'>Social link not updated</span>";           
                                }

                        }
                    }
                ?>
                 <form method="post" action="">
                    <table class="form">					
                        <tr>
                            <td>
                                <input name="fb" type="text" placeholder="Enter FB link" class="medium" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input name="tw" type="text" placeholder="Enter TW link" class="medium" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input name="ln" type="text" placeholder="Enter LinkedIn link" class="medium" />
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
