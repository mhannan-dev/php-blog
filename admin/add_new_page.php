<?php include '../admin/inc/header.php'; ?>
<?php include '../admin/inc/sidebar.php'; ?>
<div class="grid_10">

    <div class="box round first grid">
        <h2>Add New Page</h2>
        <div class="block">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               
                $name = mysqli_real_escape_string($db->link, $_POST['name']);
                $body = mysqli_real_escape_string($db->link, $_POST['body']);
                

                if ($name == ""|| $body == "") {

                    echo "<span class='error'>Field must not be empty</span>";
                }  else {
                    
                    $query = "INSERT INTO pages(name,body) VALUES('$name','$body')";
                            
                    $inserted_rows = $db->insert($query);
                    if ($inserted_rows) {
                        
                        echo "<script>window.location = 'page_list.php';</script>";
                        
                        
                             
                    } else {
                        echo "<span class='error'>Page Not Inserted!</span>";
                    }
                }
            }
                ?>

                <form action="" method="post">
                    <table class="form">

                        <tr>
                            <td>
                                <label>Name</label>
                            </td>
                            <td>
                                <input type="text" name="name" placeholder="Enter Page Name" class="medium"/>
                            </td>
                        </tr>
                     
                        <tr>
                            <td style="vertical-align: top; padding-top: 9px;">
                                <label>Content</label>
                            </td>
                            <td>
                                <textarea id="mytextarea" class="tinymce" name="body"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="submit" Value="Save" />
                            </td>
                        </tr>
                    </table>
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