<?php include '../admin/inc/header.php';?>
<?php include '../admin/inc/sidebar.php';?>
<?php
if (!isset($_GET['cat_id']) || $_GET['cat_id'] == null) {

    echo "<script>window.location = 'catlist.php';</script>";

    #header("Location:catlist.php");
} else {
    $id = $_GET['cat_id'];
}

?>

        <div class="grid_10">

            <div class="box round first grid">
                <h2>Add New Category</h2>
               <div class="block copyblock">
                <?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $name = mysqli_real_escape_string($db->link, $name);
    #print_r($name);

    if (empty($name)) {
        echo "<span class='error'>Field must not be empty</span>";
    } else {
        $query = "UPDATE tbl_category SET name='$name' WHERE id='$id'";
        $cat_update = $db->update($query);
        if ($cat_update) {
            #echo "<span class='success'>Category updated successfully</span>";
            echo "<script>window.location = 'catlist.php';</script>";
        } else {
            echo "<span class='error'>Category not updated successfully</span>";
        }

    }
}
?>
                <?php
$query = "SELECT * FROM tbl_category WHERE id='$id' ORDER BY id DESC";
$category = $db->select($query);
while ($cat_result = $category->fetch_assoc()) {

    #print_r($cat_result);

    ?>
                 <form method="post" action="">
                    <table class="form">
                        <tr>
                            <td>
                                <input name="name" type="text" class="medium" value="<?php echo $cat_result['name'] ?>" />
                            </td>
                        </tr>
						<tr>
                            <td>
                                <input type="submit" name="submit" Value="Update" />
                            </td>
                        </tr>
                    </table>
                </form>
               <?php }?>

                </div>
            </div>
        </div>
<?php include '../admin/inc/footer.php';?>
