<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>


        <div class="grid_10">
            <div class="box round first grid">
                <h2>Inbox</h2>
                <div class="block">
                <?php 
					if (isset($_GET['seen_id'])) {
						$seen_id = $_GET['seen_id'];

				 		$query  = "UPDATE contact SET status='1' WHERE id='$seen_id'";
				 		$msg_update = $db->update($query);
				    	    if ($msg_update) {
				                echo "<span class='success'>Message sent to seen box</span>";           
				            	    } 
				                  else{
				                       echo "<span class='error'>Something wrong......</span>";           
				                   }
				            }
 				?>
 				


                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Serial No.</th>
							<th>Name</th>
							<th>Message</th>
							<th>Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
				        $query ="SELECT * FROM contact WHERE status='0' ORDER BY id DESC";
				        $contacts = $db->select($query);
				        if ($contacts) {
				        	$i=0;
				            while ($result = $contacts->fetch_assoc()) {
				            	$i++;
                		?>
						<tr class="odd gradeX">
							<td><?php echo $i; ?></td>
							<td><?php echo $result['fname'].' '.$result['lname']; ?></td>
							
							<td><?php echo $fm->textShorten($result['msg'], 40) ?></td>
							<td><?php echo $fm->formatDate($result['created']); ?></td>
							<td>
								<a href="view_msg.php?msg_id=<?php echo $result['id']; ?>">View</a>&nbsp; ||
								<a href="reply_msg.php?msg_id=<?php echo $result['id']; ?>">Reply</a>&nbsp;||
								<a href="?seen_id=<?php echo $result['id']; ?>" onclick="return confirm('Are you sure to move this to seen box?')">Seen</a>&nbsp; 								
								
							</td>
						</tr>
					<?php } } ?>
						
						
						
						
					</tbody>
				</table>
               </div>
            </div>
            <div class="box round first grid">
                <h2>Seen Message</h2>
                <?php 
                    if (isset($_GET['del_msg'])) {
                        $del_msg = $_GET['del_msg'];
                        $del_query = "DELETE FROM contact WHERE id='$del_msg'";
                        $delmsg = $db->delete($del_query);

                         if ($delmsg) {
                                    echo "<span class='error'>Message deleted successfully</span>";           
                                } else{
                                    echo "<span class='error'>Message not deleted successfully</span>";
                                }

                    }
                ?>

                <div class="block">        
                   <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Serial No.</th>
							<th>Name</th>
							<th>Message</th>
							<th>Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
				        $query ="SELECT * FROM contact WHERE status='1' ORDER BY id DESC";
				        $contacts = $db->select($query);
				        if ($contacts) {
				        	$i=0;
				            while ($result = $contacts->fetch_assoc()) {
				            	$i++;
                		?>
						<tr class="odd gradeX">
							<td><?php echo $i; ?></td>
							<td><?php echo $result['fname'].' '.$result['lname']; ?></td>
							
							<td><?php echo $fm->textShorten($result['msg'], 40) ?></td>
							<td><?php echo $fm->formatDate($result['created']); ?></td>
							<td>
								<a href="?del_msg=<?php echo $result['id']; ?>" onclick="return confirm('Are you sure want to delete this msg?')">Delete</a>&nbsp;
								
											
								
							</td>
						</tr>
					<?php } } ?>
						
						
						
						
					</tbody>
				</table>
               </div>
            </div>

        </div>
        <?php include '../admin/inc/footer.php'; ?>
