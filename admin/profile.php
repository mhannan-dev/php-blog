<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<?php
        $userId     =   Session::get('userId');
        
        $userRole   =   Session::get('userRole');
        $username   =   Session::get('userName');

    
?>

<div class="grid_10">

    <div class="box round first grid">
        <h2>Update Profile</h2>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {    
                $name = mysqli_real_escape_string($db->link, $_POST['name']);
                $username = mysqli_real_escape_string($db->link, $_POST['username']);
                $email = mysqli_real_escape_string($db->link, $_POST['email']);
                $details = mysqli_real_escape_string($db->link, $_POST['details']);
                
            
                $query = "UPDATE tbl_user SET
                            name = '$name',
                            username = '$username',      
                            email = '$email',      
                            details = '$details'      
                            WHERE id='$userId'
                        ";        
                        $updated_rows = $db->update($query);
                        
                        if ($updated_rows) {
                            echo "<span class='success'>User Updated Successfully.</span>";
                            #echo "<script>window.location = 'postlist.php';</script>";
    
                        } else {
                            echo "<span class='error'>User Not Updated!</span>";
                        }
                    }
                ?>
                <div class="block">

            <?php 
                
                 $query = "SELECT * FROM tbl_user WHERE id='$userId' AND role='$userRole'";
                
                $getUser = $db->select($query);
                #print_r($getUser);
                
                if ($getUser) {
                        while ($user_result = $getUser->fetch_assoc()) {
                            #print_r($user_result);
                ?>
                <form action="" method="post">
                    <table class="form">
                    <tr>
                            <td>
                                <label>Name</label>
                            </td>
                            <td>
                                <input type="text" name="name" value="<?php echo $user_result['name']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Username</label>
                            </td>
                            <td>
                                <input type="text" name="username" value="<?php echo $user_result['username']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Email</label>
                            </td>
                            <td>
                                <input type="text" name="email" value="<?php echo $user_result['email']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Details</label>
                            </td>
                            <td>
                                
                                <textarea name="details" id="mytextarea" rows="4" cols="50">
                                <?php echo $user_result['details']; ?>
                                </textarea> 
                                
                            </td>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="submit" Value="Update" />
                            </td>
                        </tr>
                    </table>
                </form>
                
            <?php } } ?>
            
            </div>
        </div>
    </div>


    <?php include '../admin/inc/footer.php'; ?>