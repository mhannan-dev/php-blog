<?php include '../admin/inc/header.php';?>
<?php include '../admin/inc/sidebar.php';?>
<?php
if (!Session::get('userRole') == '0') {
    echo "<script>window.location = 'index.php';</script>";
}
?>


        <div class="grid_10">

            <div class="box round first grid">
                <h2>Add New User</h2>
               <div class="block copyblock">


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $fm->validation($_POST['username']);
    $password = $fm->validation(md5($_POST['password']));
    $role = $fm->validation($_POST['role']);
    

    $username = mysqli_real_escape_string($db->link, $username);
    $password = mysqli_real_escape_string($db->link, $password);
    $role = mysqli_real_escape_string($db->link, $role);
    
    
    

    if (empty($username) || empty($password) || empty($role)) {

        echo "<span class='error'>Field must not be empty</span>";
    } else {
        //$mail_query 		= "SELECT * FROM tbl_user WHERE email='$email' AND username='$username' LIMIT 1";
		$mail_query 		= "SELECT * FROM tbl_user WHERE username='$username' LIMIT 1";
        $mailCheck 			= $db->select($mail_query);
            
            if ($mailCheck 		!= false) {
                echo "<span style='color:red'>Username already exist</span>";
        } else{
            $query = "INSERT INTO tbl_user(username,password,role) VALUES ('$username','$password','$role')";
        
            $user_insert = $db->insert($query);
        
         //print_r($user_insert);

            if ($user_insert) {
        
            echo "<span class='success'>User inserted successfully</span>";

        } else {
           
            echo "<span class='error'>User not inserted</span>";

        }

        }
    }
}
?>
                <form action="" method="post">
                    <table class="form">
                        <tr>
                            <td><label>Username</label></td>
                            <td>
                                <input name="username" type="text" placeholder="Enter username..." required="1" class="medium" />
                            </td>
                        </tr>

                        <tr>
                            <td><label>Password</label></td>
                            <td>
                                <input name="password" type="password" placeholder="Enter password" required="1" class="medium" />
                            </td>
                        </tr>
                    <tr>
                            <td>
                                <label>User Role</label>
                            </td>
                            <td>
                                <select id="select" name="role">
                                    <option>Select Role</option>
                                    <option value="0">Admin</option>
                                    <option value="1">Author</option>
                                    <option value="2">Editor</option>
                                </select>
                            </td>
                        </tr>
                       
						<tr>
                            <td>
                                <input type="submit" name="submit" Value="Create"/>
                            </td>
                        </tr>
                    </table>
                </form>

                </div>
            </div>
        </div>
<?php include '../admin/inc/footer.php';?>
