<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<?php 
     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "<script>window.location = 'post_list.php';</script>";
        
    } else{
        $id = $_GET['post_id'];
    }

 ?>

<div class="grid_10">

    <div class="box round first grid">
        <h2>Update Post</h2>
        <div class="block">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               
                $title = mysqli_real_escape_string($db->link, $_POST['title']);
                $body = mysqli_real_escape_string($db->link, $_POST['body']);
                $cat = mysqli_real_escape_string($db->link, $_POST['cat']);
                $author = mysqli_real_escape_string($db->link, $_POST['author']);
                $tags = mysqli_real_escape_string($db->link, $_POST['tags']);
                $userId = mysqli_real_escape_string($db->link, $_POST['userId']);
                
                
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
                    if ($title == ""||$body == ""||$cat == ""||$author == ""||$file_name == ""||$tags == ""||$userId == "") {

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
                        $query = "UPDATE tbl_post SET
                            title = '$title',
                            body = '$body',
                            cat = '$cat',
                            author = '$author',
                            image = '$uploaded_image',
                            tags = '$tags',
                            userId = '$userId',
                            WHERE id='$id'
                        ";


                        $inserted_rows = $db->insert($query);

                        if ($inserted_rows) {
                            #echo "<span class='success'>Post Updated Successfully.</span>";
                            echo "<script>window.location = 'postlist.php';</script>";
                            
                                 
                        } else {
                            echo "<span class='error'>Post Not Updated!</span>";
                        }
                    }
                } else{
                    $query = "UPDATE tbl_post SET 
                            title = '$title',
                            body = '$body',
                            cat = '$cat',
                            author = '$author',
                            #image = '$uploaded_image',
                            tags = '$tags' WHERE id='$id'
                        ";


                        $inserted_rows = $db->insert($query);

                        if ($inserted_rows) {
                            echo "<script>window.location = 'postlist.php';</script>";
                            
                                 
                        } else {
                            echo "<span class='error'>Post Not Updated!</span>";
                        }

                }  
                }
                ?>
               <?php 
                    $query = "SELECT * FROM tbl_post WHERE id='$id' ORDER BY id DESC";
                    $post = $db->select($query);
                        while ($post_result = $post->fetch_assoc()) {

                          #print_r($post_result);
                        

                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class="form">

                        <tr>
                            <td>
                                <label>Title</label>
                            </td>
                            <td>
                                <input readonly type="text" name="title" value="<?php echo $post_result['title']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Author</label>
                            </td>
                            <td>
                                <input readonly type="text" value="<?php echo Session::get('userName');?>" name="author" class="medium"/>
                                 <input type="hidden" value="<?php echo Session::get('userId');?>"  name="userId" class="medium"/>
                            </td>
                        </tr>

                          <tr>
                            <td>
                                <label>Category</label>
                            </td>
                            <td>
                                <select id="select" name="cat">


                                <?php
                                $query = "SELECT * FROM tbl_category ";
                                $category = $db->select($query);
                                if ($category) {
                                    while ($result = $category->fetch_assoc()) {
                                        ?>
                                            <option 

                                             <?php  
                                                if ($post_result['cat'] == $result['id']) { ?> 
                                                selected="selected"
                                             <?php }  ?> value="<?php echo $result['id'] ?>"><?php echo $result['name'] ?>
                                                 
                                             </option>
                                        <?php }
                                    } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Tags</label>
                            </td>
                            <td>
                                <input readonly type="text" value="<?php echo $post_result['tags']; ?>" name="tags" class="medium" />
                            </td>
                        </tr>


                        <tr>
                            <td>
                                <label>Image</label>
                            </td>
                            <td>
                                <img src="<?php echo $post_result['image'] ?>" height="100px; width:100px;">
                                <input  name="image" type="file" readonly/>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding-top: 9px;">
                                <label>Content</label>
                            </td>
                            <td>
                                <textarea class="tinymce" name="body" readonly="">
                                    <?php echo $post_result['body']; ?>
                                </textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="submit" Value="OK" />
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