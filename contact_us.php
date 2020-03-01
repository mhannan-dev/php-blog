<?php include './inc/header.php'?>
     <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fname = $fm->validation($_POST['fname']);
    $lname = $fm->validation($_POST['lname']);
    $email = $fm->validation($_POST['email']);
    $msg = $fm->validation($_POST['msg']);

    $fname = mysqli_real_escape_string($db->link, $fname);
    $lname = mysqli_real_escape_string($db->link, $lname);
    $email = mysqli_real_escape_string($db->link, $email);
    $msg = mysqli_real_escape_string($db->link, $msg);

    $err = "";

    if (empty($fname)) {
        $err = "Last name must not be empty";

    } elseif (empty($lname)) {
        $err = "Last name must not be empty";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "In valid email";

    } elseif (empty($msg)) {
        $err = "Message must not be empty";

    } else {
        $query = "INSERT INTO contact(fname,lname,email,msg)VALUES('$fname','$lname','$email','$msg')";
        $inserted_rows = $db->insert($query);
        if ($inserted_rows) {
            $msg = "Message sent successfully.......";
        } else {
            $err = "Message not sent.....";
        }
    }
}

?>

<div class="contentsection contemplete clear">
    <div class="maincontent clear">
        <div class="about">


            <h2>Contact us</h2>
            <?php
if (isset($err)) {
    echo "<span style='color:red'>$err</span>";
}if (isset($msg)) {
    echo "<span style='color:green'>$msg</span>";
}
?>
<form action="" method="post">
    <table>
        <tr>
            <td>Your First Name:</td>
            <td>
                <input type="text" name="fname" placeholder="Enter first name" required="1" />
            </td>
        </tr>
        <tr>
            <td>Your Last Name:</td>
            <td>
                <input type="text" name="lname" placeholder="Enter Last name" required="1" />
            </td>
        </tr>

        <tr>
            <td>Your Email Address:</td>
            <td>
                <input type="email" name="email" placeholder="Enter Valid Email Address" required="1" />
            </td>
        </tr>
        <tr>
            <td>Your Message:</td>
            <td>
                <textarea name="msg" required="1"></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" name="submit" value="Submit" />
            </td>
        </tr>
    </table>
</form>
</div>
</div>
<div class="sidebar clear">
    <?php include './inc/sidebar.php'?>
</div>
</div>
<?php include './inc/footer.php'?>