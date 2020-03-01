<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<?php
if (!isset($_GET['page_id']) || $_GET['page_id'] == NULL) {
    echo "<script>window.location = 'index.php';</script>";
} else {
    $id = $_GET['page_id'];
}
?>

<?php
if (isset($_GET['del_page'])) {
    $delpage = $_GET['del_page'];
    $del_page = "DELETE FROM pages WHERE id='$delpage'";
    $dlt_page = $db->delete($del_page);

    if ($dlt_page) {
        echo "<span class='success'>Page deleted successfully</span>";
    } else {
        echo "<span class='error'>Page not deleted successfully</span>";
    }
}
?>


<div class="grid_10">

    <div class="box round first grid">
        <h2>Page</h2>
        <div class="block">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $name = mysqli_real_escape_string($db->link, $_POST['name']);
                $body = mysqli_real_escape_string($db->link, $_POST['body']);


                if ($name == "" || $body == "") {

                    echo "<span class='error'>Field must not be empty</span>";
                } else {

                    $page_query = "UPDATE pages SET name='$name', body='$body' WHERE id='$id'";

                    $updated_rows = $db->update($page_query);

                    if ($updated_rows) {
                        echo "<script>window.location = 'index.php';</script>";
                        #echo "<span class='success'>Page updated successfully</span>";
                    } else {
                        echo "<span class='error'>Page Not Inserted!</span>";
                    }
                }
            }
            ?>
            <?php
            $query = "SELECT * FROM pages WHERE id ='$id'";

            $pages = $db->select($query);
            if ($pages) {

                while ($result = $pages->fetch_assoc()) {

                    #print_r($result);
                    ?>

                    <form action="" method="post">
                        <table class="form">

                            <tr>
                                <td>
                                    <label>Name</label>
                                </td>
                                <td>
                                    <input type="text" name="name" value="<?php echo $result['name'] ?>" class="medium"/>
                                </td>
                            </tr>

                            <tr>
                                <td style="vertical-align: top; padding-top: 9px;">
                                    <label>Content</label>
                                </td>
                                <td>
                                    <textarea id="mytextarea" class="tinymce" name="body">
                                        <?php echo $result['body'] ?>

                                    </textarea>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="submit" name="submit" Value="UPDATE" />
									<?php if (Session::get('userRole') == '0') { ?>
										&nbsp; <a onclick="return confirm('Are you sure want to delete this page?')"  href="?del_page=<?php echo $result['id']; ?>">Delete</a>
									<?php } ?>
									

                                    

                                </td>
                            </tr>
                        </table>
                    </form>
                <?php }
            }
            ?>

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