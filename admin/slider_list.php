<?php include '../admin/inc/header.php';?>
      <?php include '../admin/inc/sidebar.php';?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>All Sliders</h2>
                  <?php
if (isset($_GET['delete_slider'])) {
    $delete_slider = $_GET['delete_slider'];
    $delete_slider = "DELETE FROM sliders WHERE id='$delete_slider'";
    $delete_slider = $db->delete($delete_slider);

    if ($delete_slider) {
        echo "<span class='success'>Slider deleted successfully</span>";
    } else {
        echo "<span class='error'>Slider not deleted successfully</span>";
    }

}
?>

                <div class="block">
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>SL.</th>
							<th>Title</th>
							<th>Image</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>


						<?php
$query = "SELECT * FROM sliders ORDER BY  timestamp DESC limit 4";

$sl = $db->select($query);
if ($sl) {
    $i = 0;
    while ($result = $sl->fetch_assoc()) {
        $i++;
        #print_r($result);
        ?>
					<tr class="even gradeC">
							<td><?php echo $i; ?></td>
							<td><?php echo $result['title']; ?></td>
							<td><img src="<?php echo $result['image'] ?>" height="100px; width:150px;"></td>
							<td>
								<a href="edit_slider.php?slider_id=<?php echo $result['id']; ?>">Edit</a>
								||
								<a onclick="return confirm('Are you sure want to delete this item?')"  href="?delete_slider=<?php echo $result['id']; ?>">Delete</a>
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


