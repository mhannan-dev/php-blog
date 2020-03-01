<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<div class="grid_10">

    <div class="box round first grid">
        <h2>Add Slider</h2>
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

                if ($title == ""||$file_name == "") {

                    echo "<span class='error'>Field must not be empty</span>";
                } elseif ($file_size > 1048567) {
                    echo "<span class='error'>Image Size should be less then 1MB!
                             </span>";
                } elseif (in_array($file_ext, $permited) === false) {
                    echo "<span class='error'>You can upload only:-"
                    . implode(', ', $permited) . "</span>";
                } else {
                    move_uploaded_file($file_temp, $uploaded_image);
                    $query = "INSERT INTO sliders(image,title) 
                            VALUES('$uploaded_image','$title')";
                    $inserted_rows = $db->insert($query);
                    if ($inserted_rows) {
                        #echo "<span class='success'>Slider Inserted Successfully.</span>";
                        echo "<script>window.location = 'slider_list.php';</script>";
                        
                    } else {
                        echo "<span class='error'>Slider Not Inserted!</span>";
                    }
                }
            }
                ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <table class="form">
                        <tr>
                            <td>
                                <label>Slider Image</label>
                                
                            </td>
                            <td>
                                <input  name="image" type="file"/>
                                <span style="color: green; font-size: 12px;">Upload image W=960px & H=280px</span>
                            </td>
                        </tr>
                            <tr>
                            <td>
                                <label>Description</label>
                                
                            </td>
                            <td>
                                <input type="text" name="title" placeholder="Enter desc..." class="medium"/>
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
            </div>
        </div>
    </div>




    <?php include '../admin/inc/footer.php'; ?>