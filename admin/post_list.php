<?php include '../admin/inc/header.php';?>
      <?php include '../admin/inc/sidebar.php';?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Post List</h2>
    <?php

					if (isset($_GET['delpost'])) {
						$delpost = $_GET['delpost'];
						$del_post = "DELETE FROM tbl_post WHERE id='$delpost'";
						$dlt_pst = $db->delete($del_post);
						if ($dlt_pst) {
							echo "<span class='success'>Post deleted successfully</span>";
						} else {
							echo "<span class='error'>Post not deleted successfully</span>";
						}
					}
	?>

<?php

if (isset($_GET['post_id'])) {
	$post_id = $_GET['post_id'];
	$up_post = "DELETE FROM tbl_post WHERE id='$post_id'";
	$updt_pst = $db->delete($up_post);
	if ($updt_pst) {
		echo "<span class='success'>Post deleted successfully</span>";
	} else {
		echo "<span class='error'>Post not deleted successfully</span>";
	}
}
?>



                <div class="block">
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>SL.</th>
							<th>Post Title</th>
							<th>Description</th>
							<th>Category</th>
							<th>Author</th>
							<th>Date</th>
							<th>Image</th>
							<th>Tags</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
$query = "SELECT tbl_post.*, tbl_category.name FROM tbl_post INNER JOIN tbl_category ON tbl_post.cat = tbl_category.id ORDER BY tbl_post.title DESC";

$post = $db->select($query);
if ($post) {
    $i = 0;
    while ($result = $post->fetch_assoc()) {
        $i++;
        #print_r($result);
        ?>
						<tr class="even gradeC">
							<td><?php echo $i; ?></td>
							<td><?php echo $result['title']; ?></td>
							<td><?php echo $fm->textShorten($result['body'], 40) ?></td>
							<td><?php echo $result['name']; ?></td>
							<td><?php echo $result['author']; ?></td>
							<td><?php echo $fm->formatDate($result['date']); ?></td>
							<td><img src="<?php echo $result['image'] ?>" height="35px; width:35px;"></td>
							<td class="center"><?php echo $result['tags']; ?></td>
							<td>
									<a href="post_view.php?post_id=<?php echo $result['id']; ?>">View</a>
									
									<?php if (Session::get('userId') == $result['userid'] || Session::get('userRole') == '0') {?>

									
									|| <a href="edit_post.php?post_id=<?php echo $result['id']; ?>">Edit</a>
									
									|| <a onclick="return confirm('Are you sure want to delete this item?')"  href="?delpost=<?php echo $result['id']; ?>">Delete</a>
									
									<?php }?>
							</td>

						</tr>

            		<?php }}?>

					</tbody>
				</table>

               </div>
            </div>
        </div>

	<script type="text/javascript">
        $(document).ready(function () {
            setupLeftMenu();
            $('.datatable').dataTable();
			setSidebarHeight();
        });
    </script>

<?php include '../admin/inc/footer.php';?>


