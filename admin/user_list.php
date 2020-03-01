<?php include '../admin/inc/header.php'; ?>
        <?php include '../admin/inc/sidebar.php'; ?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>User List</h2>
                <?php 
                    if (isset($_GET['delUser'])) {
                        $delUser = $_GET['delUser'];
                        $del_query = "DELETE FROM tbl_user WHERE id='$delUser'";
                        $del_user = $db->delete($del_query);

                         if ($del_user) {
                                    echo "<span class='success'>User deleted successfully</span>";           
                                } else{
                                    echo "<span class='error'>User not deleted</span>";
                                }

                    }
                ?>
                <div class="block">        
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Serial No.</th>
							
							<th>Username</th>
							
							<th>Role</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
				        $query = "SELECT * FROM tbl_user ORDER BY id DESC";
				        $category = $db->select($query);
				        if ($category) {
				        	$i=0;
				            while ($result = $category->fetch_assoc()) {
				            	$i++;
				            	#print_r($result);
                		?>
							<tr class="odd gradeX">
								<td><?php echo $i; ?></td>
								
								<td><?php echo $result['username']; ?></td>
								
								<td>
									<?php 
										if ($result['role'] == '0') {
											echo "Admin";
										} elseif ($result['role'] == '1') {
											echo "Author";
										} elseif ($result['role'] == '2') {
											echo "Editor";
										} 
									?>
										
									</td>
								<td>
									<a href="views_user.php?userId=<?php echo $result['id']; ?>">View</a> 
									 

									

                                    <?php 
										if (Session::get('userRole') == '0') 
										{ ?>

                                        || <a onclick="return confirm('Are you sure want to delete this user?')"  href="?delUser=<?php echo $result['id']; ?>">Delete</a>

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
