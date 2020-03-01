<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>

        <div class="grid_10">
        
            <div class="box round first grid">
                <h2>Update Site Title and Description</h2>
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

                if ($file_name == ""||$title == ""||$slogan == "") {

                    echo "<span class='error'>Field must not be empty</span>";
                } elseif ($file_size > 1048567) {
                    echo "<span class='error'>Image Size should be less then 1MB!
                             </span>";
                } elseif (in_array($file_ext, $permited) === false) {
                    echo "<span class='error'>You can upload only:-"
                    . implode(', ', $permited) . "</span>";
                } else {
                    move_uploaded_file($file_temp, $uploaded_image);
                    $query = "INSERT INTO site_info(logo,title,slogan) 
                            VALUES('$uploaded_image','$title','$slogan')";
                    $inserted_rows = $db->insert($query);
                    if ($inserted_rows) {
                        #echo "<span class='success'>Post Inserted Successfully.</span>";
                        echo "<script>window.location = 'site_info_list.php';</script>";
                        
                        
                             
                    } else {
                        echo "<span class='error'>Infor Not Inserted!</span>";
                    }
                }
            }
                ?>
               

                <div class="block sloginblock">               
                  <form action="" method="post" enctype="multipart/form-data">
                    <table class="form">    
                     <tr>
                            <td>
                                <label>Logo</label>
                            </td>
                            <td>
                                <input  name="logo" type="file"/>
                            </td>
                        </tr>               
                        <tr>
                            <td>
                                <label>Website Title</label>
                            </td>
                            <td>
                                <input type="text" placeholder="Enter Website Title..."  name="title" class="medium" />
                            </td>
                        </tr>
                         <tr>
                            <td>
                                <label>Website Slogan</label>
                            </td>
                            <td>
                                <input type="text" placeholder="Enter Website Slogan..." name="slogan" class="medium" />
                            </td>
                        </tr>
                         
                         <tr>
                            <td>
                            </td>
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
