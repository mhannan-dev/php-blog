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
        <h2>Reply Message</h2>
        <div class="block">
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                     $to        = $fm->validation($_POST['toEmail']);
                     $frmEmail  = $fm->validation($_POST['frmEmail']);
                     $subject   = $fm->validation($_POST['subj']);
                     $msg       = $fm->validation($_POST['msg']);

                     $sendmail  =  mail($to,$frmEmail,$subject,$msg);
                         if ($sendmail) {
                            echo "<span class='success'>Message sent successfully";                         
                         } else{
                            echo "<span class='error'>Something went wrong";                         
                         }
                }
            ?>
                <form action="" method="post">
                    <?php
                        $query ="SELECT * FROM contact WHERE id='$id'";
                        $msg = $db->select($query);
                        if ($msg) {
                            #$i=0;
                            while ($result = $msg->fetch_assoc()) { 
                               #$i++;
                               #print_r($result)
                        ?>
                    <table class="form">
                        
                        <tr>
                            <td>
                                <label>To</label>
                            </td>

                            <td>
                                <input type="text" readonly  name="toEmail" value="<?php echo $result['email']; ?>" class="medium"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>From</label>
                            </td>

                            <td>
                                <input type="text"  name="frmEmail" placeholder="Enter your email address" class="medium"/>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>Subject</label>
                            </td>

                            <td>
                                <input type="text"  name="subj" placeholder="Enter your subject" class="medium"/>
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
                                <input type="submit" name="submit" Value="Sent" />
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