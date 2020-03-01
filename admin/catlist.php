<?php include '../admin/inc/header.php'; ?>
        <?php include '../admin/inc/sidebar.php'; ?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Category List</h2>
                <?php 
                    if (isset($_GET['delcat'])) {
                        $delcat = $_GET['delcat'];
                        $del_query = "DELETE FROM tbl_category WHERE id='$delcat'";
                        $del_cat = $db->delete($del_query);

                         if ($del_cat) {
                                    echo "<span class='success'>Category deleted successfully</span>";           
                                } else{
                                    echo "<span class='error'>Category not deleted successfully</span>";
                                }
					}
				
                ?>
                <div class="block">        
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Serial No.</th>
							<th>Category Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
				        $query = "SELECT * from tbl_category ORDER BY id";
				        $category = $db->select($query);
				        if ($category) {
				        	$i=0;
				            while ($result = $category->fetch_assoc()) {
				            	$i++;
				            	#print_r($result);
                		?>
							<tr class="odd gradeX">
								<td><?php echo $i; ?></td>
								<td><?php echo $result['name']; ?></td>
								<td>
								
									
									<?php if (Session::get('userRole') == '0') { ?>
										<a href="editcat.php?cat_id=<?php echo $result['id']; ?>">Edit</a> 
									|| <a onclick="return confirm('Are you sure want to delete this item?')"  href="?delcat=<?php echo $result['id']; ?>">Delete</a>
								<?php } ?>
								</td>
							</tr>

					<?php } } ?>
						
						
						
					</tbody>
				</table>
               </div>
            </div>
        </div>
        <?php include '../admin/inc/footer.php'; ?>
