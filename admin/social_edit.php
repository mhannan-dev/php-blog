<?php include '../admin/inc/header.php';?>
<?php include '../admin/inc/sidebar.php';?>
<?php 
    if (!isset($_GET['social_id']) || $_GET['social_id'] == NULL) {
        echo "<script>window.location = 'social_list.php';</script>";
        
    } else{
        $social_id = $_GET['social_id'];
    }

 ?>


        <div class="grid_10">

            <div class="box round first grid">
                <h2>Update Social Link</h2>
   
<?php

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $fb = mysqli_real_escape_string($db->link, $_POST['fb']);
                    $tw = mysqli_real_escape_string($db->link, $_POST['tw']);
                    $ln = mysqli_real_escape_string($db->link, $_POST['ln']);

                    if ($fb == ""||$tw == ""||$ln == "") {
                        echo "<span class='error'>Field must not be empty</span>";
                    } else {
                        $query = "UPDATE tbl_social SET
                                fb = '$fb',
                                tw = '$tw',
                                ln = '$ln'
                                WHERE id='$social_id'                            
                        ";
                        $social_update = $db->update($query);
                        
                        print_r($social_update);

                        if ($social_update) {
                            
                            echo "<script>window.location = 'social_list.php';</script>";
                        } else {
                            echo "<span class='error'>Social link not updated</span>";
                        }
                    }
                }
?>



<div class="block">
<?php
$query = "SELECT * from tbl_social ORDER BY id";
$s_link = $db->select($query);
if ($s_link) {

    while ($s_result = $s_link->fetch_assoc()) {
        #print_r($s_result);
        ?>
                 <form method="post" action="">
                    <table class="form">
                        <tr>
                            <td>
                                <label>Facebook</label>
                            </td>
                            <td>
                                <input type="text" name="fb" value="<?php echo $s_result['fb'] ?>" class="medium" />
                            </td>
                        </tr>
						 <tr>
                            <td>
                                <label>Twitter</label>
                            </td>
                            <td>
                                <input type="text" name="tw" value="<?php echo $s_result['tw'] ?>" class="medium" />
                            </td>
                        </tr>
						 <tr>
                            <td>
                                <label>LinkedIn</label>
                            </td>
                            <td>
                                <input type="text" name="ln" value="<?php echo $s_result['ln'] ?>" class="medium" />
                            </td>
                        </tr>
						 <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="submit" Value="Save" />
                            </td>
                        </tr>
                    </table>
                    </form>
                <?php }}?>

                </div>
            </div>
        </div>
        <?php include '../admin/inc/footer.php';?>
