<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<?php 
    if (!isset($_GET['userId']) || $_GET['userId'] == NULL) {
        echo "<script>window.location = 'user_list.php';</script>";
        
    } else{
        $userId = $_GET['userId'];
    }

 ?>



<div class="grid_10">

    <div class="box round first grid">
        <h2>View Profile</h2>
       
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo "<script>window.location = 'user_list.php';</script>";
                }
                ?>
                 <div class="block">

               <?php 
                  
                  $query = "SELECT * FROM tbl_user WHERE id='$userId'";
                  
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
                                <input type="text" name="name" readonly value="<?php echo $user_result['name']; ?>" class="medium"/>
                            </td>
                        </tr>


                        <tr>
                            <td>
                                <label>Username</label>
                            </td>
                            <td>
                                <input type="text" name="username" readonly value="<?php echo $user_result['username']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Email</label>
                            </td>
                            <td>
                                <input type="text" name="email" readonly value="<?php echo $user_result['email']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Details</label>
                            </td>
                            <td>
                                
                                <textarea name="details" id="mytextarea" cols="30" rows="10">
                                <?php echo $user_result['details']; ?>
                                </textarea>
                            </td>
                        </tr>
                       
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="submit" Value="Ok" />
                            </td>
                        </tr>
                    </table>
                </form>
                
            <?php } } ?>
            
           
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            setupTinyMCE();
            setDatePicker('date-picker');
            $('input[type="checkbox"]').fancybutton();
            $('input[type="radio"]').fancybutton();
        });
    </script>


    <?php include '../admin/inc/footer.php'; ?>