<?php include '../admin/inc/header.php'; ?>
        <?php include '../admin/inc/sidebar.php'; ?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Site Informations</h2>
                <?php 
                    if (isset($_GET['site_info_id'])) {
                        $site_info_id = $_GET['site_info_id'];
                        $del_query = "DELETE FROM site_info WHERE id='$site_info_id'";
                        $del_info = $db->delete($del_query);

                         if ($del_info) {
                                    echo "<span class='success'>Informations deleted successfully</span>";           
                                } else{
                                    echo "<span class='error'>Informations not deleted successfully</span>";
                                }

                    }
                ?>
                <div class="block">        
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Serial No.</th>
							<th>Title</th>
							<th>Slogan</th>
							<th>Logo</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
				        $query = "SELECT * FROM site_info ORDER BY id";
				        $info = $db->select($query);
				        if ($info) {
				        	$i=0;
				            while ($result = $info->fetch_assoc()) {
				            	$i++;
				            	#print_r($result);
                		?>
							<tr class="odd gradeX">
								<td><?php echo $i; ?></td>
								<td><?php echo $result['title']; ?></td>
								<td><?php echo $result['slogan']; ?></td>
								<td><img src="<?php echo $result['logo'] ?>" height="100px; width:100px;"></td>
								<td>
									<a href="edit_site_info.php?info_id=<?php echo $result['id']; ?>">Edit</a> 
									|| <a onclick="return confirm('Are you sure want to delete this item?')"  href="?site_info_id=<?php echo $result['id']; ?>">Delete</a>
								</td>
							</tr>

					<?php } } ?>
						
						
						
					</tbody>
				</table>
               </div>
            </div>
        </div>
        <?php include '../admin/inc/footer.php'; ?>
