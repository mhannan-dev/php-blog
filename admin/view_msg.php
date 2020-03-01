<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<?php 
    if (!isset($_GET['msg_id']) || $_GET['msg_id'] == NULL) {
        echo "<script>window.location = 'inbox.php';</script>";
        #header("Location:catlist.php");
    } else{
        $id = $_GET['msg_id'];
    }
 ?>
<div class="grid_10">
    <div class="box round first grid">
        <h2>View Message</h2>
        <div class="block">
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                     echo "<script>window.location = 'inbox.php';</script>";
                }
            ?>
                <form action="" method="post">
                    <?php
                        $query ="SELECT * FROM contact WHERE id='$id'";
                        $msg = $db->select($query);
                        if ($msg) {
                            $i=0;
                            while ($result = $msg->fetch_assoc()) { 
                               $i++;
                               #print_r($result)
                        ?>
                    <table class="form">
                        <tr>
                            <td>
                                <label>Name</label>
                            </td>
                            <td>
                                <input type="text" readonly name="name" value="<?php echo $result['fname'].' '.$result['lname']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Email</label>
                            </td>
                            <td>
                                <input type="text" readonly name="email" value="<?php echo $result['email']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding-top: 9px;">
                                <label>Content</label>
                            </td>
                            <td>
                                <textarea readonly class="tinymce" name="msg"><?php echo $result['msg']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="submit" Value="Submit" />
                            </td>
                        </tr>
                    </table>
                     <?php } } ?>
                </form>
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