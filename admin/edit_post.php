<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<?php
    if (!isset($_GET['post_id']) || $_GET['post_id'] == null) {
        echo "<script>window.location = 'post_list.php';</script>";
    } else {
        $post_id = $_GET['post_id'];
    }
?>

<div class="grid_10">

    <div class="box round first grid">
        <h2>Edit Post</h2>
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
                
                // if ($title == ""||$body == ""||$cat == ""||$author == ""||$file_name == ""||$tags == "") {
                //     echo "<span class='error'>Field must not be empty</span>";
                // } 
                if (!empty($file_name)) {

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
                                userid = '$userid',

                                WHERE id='$post_id'
                            ";
                        $updated_rows1 = $db->insert($query);
                        if ($updated_rows1) {
                            echo "<script>window.location = 'post_list.php';</script>";
                        } else {
                            echo "<span class='error'>Post updated successfully.</span>";
                        }
                    } #end else
            } else{
                $query = "UPDATE tbl_post SET
                        title = '$title',
                        body = '$body',
                        cat = '$cat',
                        author = '$author',
                        tags = '$tags',
                        userid = '$userid'
                        WHERE id='$post_id'
                    ";
                $update_row = $db->insert($query);
                if ($update_row) {
                    echo "<script>window.location = 'post_list.php';</script>";
                } else {
                    echo "<span class='error'>Post updated successfully.</span>";
                }
            }
        }
?>

<?php
$query = "SELECT * FROM tbl_post WHERE id='$post_id'";
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
                                <input type="text" name="title" value="<?php echo $post_result['title']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Author</label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo Session::get('userName'); ?>" name="author" class="medium"/>
                                <input type="hidden" value="<?php echo Session::get('userId'); ?>"  name="userid" class="medium"/>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>Category</label>
                            </td>
                            <td>
                                <select id="select" name="cat">
                                <?php $query = "SELECT * FROM tbl_category";
                                    $category = $db->select($query);
                                    if ($category) {
                                        while ($result = $category->fetch_assoc()) {?>
            
                                            <option

                                            <?php if ($post_result['cat'] == $result['id']) {?>

                                                selected="selected"
                                            <?php }?> value="<?php echo $result['id'] ?>"><?php echo $result['name'] ?>

                                            </option>
                                <?php } }?>


                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Tags</label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $post_result['tags']; ?>" name="tags" class="medium" />
                            </td>
                        </tr>


                        <tr>
                            <td>
                                <label>Image</label>
                            </td>
                            <td>
                                <img src="<?php echo $post_result['image'] ?>" height="100px; width:100px;"><BR/>
                                <input  name="image" type="file"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding-top: 9px;">
                                <label>Content</label>
                            </td>
                            <td>
                                <textarea  id="mytextarea" class="tinymce" name="body" rows="10" cols="40">
                                    <?php echo $post_result['body']; ?>
                                </textarea>
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
            <?php } ?>
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


    <?php include '../admin/inc/footer.php';?>