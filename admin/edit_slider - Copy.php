<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<?php 
    if (!isset($_GET['slider_id']) || $_GET['slider_id'] == NULL) {
        echo "<script>window.location = 'slider_list.php';</script>";
        
    } else{
        $slider_id = $_GET['slider_id'];
    }

 ?>

<div class="grid_10">

    <div class="box round first grid">
        <h2>Update Post</h2>
        <div class="block">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               
                $title = mysqli_real_escape_string($db->link, $_POST['title']);
                
            
                // Image upload
                $permited = array('jpg', 'jpeg', 'png', 'gif');
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_temp = $_FILES['image']['tmp_name'];
                $div = explode('.', $file_name);
                $file_ext = strtolower(end($div));
                $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
                $uploaded_image = "upload/" . $unique_image;
                //Image upload

                 
                if (!empty($file_name)) {
                
                    if ($title == "") {

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
                        $query = "UPDATE sliders SET
                            image = '$uploaded_image',

                            WHERE id ='$slider_id'
                        ";


                        $inserted_rows = $db->insert($query);

                        if ($inserted_rows) {
                            #echo "<span class='success'>Post Updated Successfully.</span>";
                            echo "<script>window.location = 'slider_list.php';</script>";
                            
                                 
                        } else {
                            echo "<span class='error'>Slider Not Updated!</span>";
                        }
                    }
                } else{
                    $query = "UPDATE sliders SET 
                            title = '$title',
                            
                            #image = '$uploaded_image',
                            WHERE id ='$slider_id'
                        ";


                        $inserted_rows = $db->insert($query);

                        if ($inserted_rows) {
                            echo "<script>window.location = 'slider_list.php';</script>";
                            
                                 
                        } else {
                            echo "<span class='error'>Slider Not Updated!</span>";
                        }

                }  
                }
                ?>
               <?php 
                    $query = "SELECT * FROM sliders WHERE id='$slider_id'";
                    $get_slider = $db->select($query);
                        while ($slider_result = $get_slider->fetch_assoc()) {
                          #print_r($slider_result);
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class="form">

                        <tr>
                            <td>
                                <label>Title</label>
                            </td>
                            <td>
                                <input type="text" name="title" value="<?php echo $slider_result['title']; ?>" class="medium"/>
                            </td>
                        </tr>
                        

                    

                        <tr>
                            <td>
                                <label>Upload New Image</label>
                            </td>
                            <td>
                                <img src="<?php echo $slider_result['image'] ?>" height="100px; width:250px;"> <br/>
                                <input  name="image" type="file"/>
                            </td>
                        </tr>
                        
                        <tr>
                            <td></td>
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

    <script type="text/javascript">
        $(document).ready(function () {
            setupTinyMCE();
            setDatePicker('date-picker');
            $('input[type="checkbox"]').fancybutton();
            $('input[type="radio"]').fancybutton();
        });
    </script>


    <?php include '../admin/inc/footer.php'; ?>