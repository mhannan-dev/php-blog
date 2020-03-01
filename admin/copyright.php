<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>


        <div class="grid_10">
        
            <div class="box round first grid">
                <h2>Update Copyright</h2>
               <?php 
            
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                         $note = $fm->validation($_POST['note']);
                         $note = mysqli_real_escape_string($db->link,$note);
                         

                        if ($note == "") {
                            echo "<span class='error'>Field must not be empty</span>";
                        }
                        else{
                             $query = "UPDATE footer SET
                            note = '$note' WHERE id='1'";
                        $updated_rows = $db->update($query);

                        if ($updated_rows) {
                            echo "<script>window.location = 'index.php';</script>";
                            
                                 
                        } else {
                            echo "<span class='error'>Not Updated Copyright</span>";
                        }

                        }
                    }
                ?>
                

                <div class="block">   
                              
                    <?php
                        $query = "SELECT * from footer";
                        $c_txt = $db->select($query);
                        if ($c_txt) {
                           
                            while ($result = $c_txt->fetch_assoc()) {
                              
                                #print_r($result);
                        ?>

                  <form method="post">
                    <table class="form">                    
                        <tr>
                            <td>
                                <input type="text" name="note" value="<?php echo $result['note'] ?>" class="medium" />
                            </td>
                        </tr>
                        
                         <tr> 
                            <td>
                                <input type="submit" name="submit" Value="Update" />
                            </td>
                        </tr>
                    </table>
                    </form>
                <?php } } ?>

                
                </div>
            </div>
        </div>
        <?php include '../admin/inc/footer.php'; ?>
