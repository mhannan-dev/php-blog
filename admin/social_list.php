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
							<th>FB</th>
                            <th>TW</th>
                            <th>LN</th>
							
							<th>Action</th>
						</tr>
					</thead>
					<tbody>


<?php

$query = "SELECT * FROM tbl_social";
$sl = $db->select($query);

if ($sl) {
    $i = 0;
    while ($result = $sl->fetch_assoc()) {
        $i++;
        #print_r($result);
        ?>
					<tr class="even gradeC">
							<td><?php echo $i; ?></td>
							<td><?php echo $result['fb']; ?></td>
                            <td><?php echo $result['tw']; ?></td>
                            <td><?php echo $result['ln']; ?></td>
							
							<td>
								<a href="social_edit.php?social_id=<?php echo $result['id']; ?>">Edit</a>
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


<?php include '../admin/inc/footer.php';?>


