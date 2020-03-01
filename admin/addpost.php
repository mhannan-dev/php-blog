<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<div class="grid_10">

    <div class="box round first grid">
        <h2>Add New Post</h2>
        <div class="block">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               
                $title = mysqli_real_escape_string($db->link, $_POST['title']);
                $body = mysqli_real_escape_string($db->link, $_POST['body']);
                $cat = mysqli_real_escape_string($db->link, $_POST['cat']);
                $author = mysqli_real_escape_string($db->link, $_POST['author']);
                $tags = mysqli_real_escape_string($db->link, $_POST['tags']);
                $userid = mysqli_real_escape_string($db->link, $_POST['userid']);
            
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
                if ($title == ""||$body == ""||$cat == ""||$author == ""||$file_name == ""||$tags == ""||$userid == "") {
                    echo "<span class='error'>Field must not be empty</span>";
                } elseif ($file_size > 1048567) {
                    echo "<span class='error'>Image Size should be less then 1MB!
                        </span>";
                } elseif (in_array($file_ext, $permited) === false) {
                    echo "<span class='error'>You can upload only:-"
                    . implode(', ', $permited) . "</span>";
                } else {
                    move_uploaded_file($file_temp, $uploaded_image);
                    $query = "INSERT INTO tbl_post(title,body,cat,author,image,tags,userid) 
                            VALUES('$title','$body','$cat','$author','$uploaded_image','$tags','$userid')";
                    $inserted_rows = $db->insert($query);
                    if ($inserted_rows) {
                    
                        echo "<script>window.location = 'post_list.php';</script>";
                    } else {
                        echo "<span class='error'>Post not inserted successfully.</span>";
                    }
                }
            }
                ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <table class="form">
                        <tr>
                            <td>
                                <label>Title</label>
                            </td>
                            <td>
                                <input type="text" name="title" placeholder="Enter Post Title..." class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Author</label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo Session::get('userName');?>"  name="author" class="medium"/>
                                <input type="hidden" value="<?php echo Session::get('userId');?>"  name="userid" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Category</label>
                            </td>
                            <td>
                                <select id="select" name="cat">
                                <?php
                                $query = "SELECT * FROM tbl_category";
                                $category = $db->select($query);
                                if ($category) {
                                    while ($result = $category->fetch_assoc()) {
                                        ?>
                                            <option value="<?php echo $result['id'] ?>"><?php echo $result['name'] ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>SEO Related Tags</label>
                            </td>
                            <td>
                                <input type="text" name="tags" placeholder="Enter tags" class="medium" />
                            </td>
                        </tr>
                         <tr>
                            <td style="vertical-align: top; padding-top: 9px;">
                                <label>SEO Related Description</label>
                            </td>
                            <td>
                                <textarea id="mytextarea2" class="tinymce" name="meta_desc"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Upload Image</label>
                            </td>
                            <td>
                                <input  name="image" type="file"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding-top: 9px;">
                                <label>Content</label>
                            </td>
                            <td>
                                <textarea id="mytextarea" class="tinymce" name="body"></textarea>
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