<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<?php 
    if (!isset($_GET['info_id']) || $_GET['info_id'] == NULL) {
        echo "<script>window.location = 'site_info_list.php';</script>";
        
    } else{
        $id = $_GET['info_id'];
    }

 ?>

<div class="grid_10">

    <div class="box round first grid">
        <h2>Update Info.</h2>
        <div class="block">
           <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               
                $title = mysqli_real_escape_string($db->link, $_POST['title']);
                $slogan = mysqli_real_escape_string($db->link, $_POST['slogan']);
                
                
                
                
                // Image upload
                $permited = array('jpg', 'jpeg', 'png', 'gif');
                $file_name = $_FILES['logo']['name'];
                $file_size = $_FILES['logo']['size'];
                $file_temp = $_FILES['logo']['tmp_name'];
                $div = explode('.', $file_name);
                $file_ext = strtolower(end($div));
                $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
                $uploaded_image = "upload/" . $unique_image;
                //Image upload

                 
                if (!empty($file_name)) {
                    if ($file_name == ""||$title == ""||$slogan == "") {

                    echo "<span class='error'>Field must not be empty</span>";
                }

                    if ($file_size > 1048567) {
                        echo "<span class='error'>Image Size should be less then 1MB!
                                 </span>";
                    } elseif (in_array($file_ext, $permited) === false) {
                        echo "<span class='error'>You can upload only:-"
                        . implode(', ', $permited) . "</span>";
                    } else {
                       
                        move_uploaded_file($file_temp, $uploaded_image);
                        $query = "UPDATE site_info SET
                            logo = '$uploaded_image',
                            title = '$title',
                            slogan = '$slogan' WHERE id='$id'                            
                        ";


                        $inserted_rows = $db->insert($query);

                        if ($inserted_rows) {
                            #echo "<span class='success'>Post Updated Successfully.</span>";
                            echo "<script>window.location = 'site_info_list.php';</script>";
                            
                                 
                        } else {
                            echo "<span class='error'>Post Not Updated!</span>";
                        }
                    }
                } else{
                   $query = "UPDATE site_info SET
                            #logo = '$uploaded_image',
                            title = '$title',
                            slogan = '$slogan' WHERE id='$id'                            
                        ";


                        $inserted_rows = $db->insert($query);

                        if ($inserted_rows) {
                            echo "<script>window.location = 'site_info_list.php';</script>";
                            
                                 
                        } else {
                            echo "<span class='error'>Post Not Updated!</span>";
                        }

                }  
                }
                ?>


               <?php 
                    $query = "SELECT * FROM site_info WHERE id='$id' ORDER BY id DESC";
                    $post = $db->select($query);
                        while ($post_result = $post->fetch_assoc()) {
                          #print_r($post_result);
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class="form">    
                     <tr>
                            <td>
                                <label>Logo</label>
                            </td>
                            <td>
                                <img src="<?php echo $post_result['logo'] ?>" height="100px; width:100px;">
                                <input  name="logo" type="file"/>
                            </td>
                        </tr>               
                        <tr>
                            <td>
                                <label>Website Title</label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $post_result['title']; ?>"  name="title" class="medium" />
                            </td>
                        </tr>
                         <tr>
                            <td>
                                <label>Website Slogan</label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $post_result['slogan']; ?>" name="slogan" class="medium" />
                            </td>
                        </tr>
                         <tr>
                            <td>
                            </td>
                            <td>
                                <input type="submit" name="submit" Value="Update" />
                            </td>
                        </tr>
                    </table>
                    </form>
            <?php }  ?>
            </div>
        </div>
    </div>


    <?php include '../admin/inc/footer.php'; ?>