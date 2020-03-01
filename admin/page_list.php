<?php include '../admin/inc/header.php';?>
      <?php include '../admin/inc/sidebar.php';?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Pages</h2>

                <div class="block">
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>SL.</th>
							<th>Page Title</th>
							<th>Description</th>

						</tr>
					</thead>
					<tbody>

					<?php
				    $query = "SELECT * from pages ORDER BY id";
				    $pages = $db->select($query);
				    if ($pages) {
				    $i=0;
				    while ($result = $pages->fetch_assoc()) {
				       	$i++;
				       	#print_r($result);
                	?>


						<tr class="even gradeC">
							<td><?php echo $i; ?></td>
							<td><?php echo $result['name']; ?> </td>
							<td><?php echo $result['body']; ?></td>
						</tr>

					<?php } } ?>

					</tbody>
				</table>
               </div>
            </div>
        </div>
<?php include '../admin/inc/footer.php';?>


