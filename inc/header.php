<?php include './config/config.php'; ?>
<?php include './lib/Database.php'; ?>
<?php include './helpers/Format.php'; ?>

<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: max-age=2592000");
?>

<?php
$db = new Database();
$fm = new Format();
?>


<html>
<head>
	
		<?php include './scripts/meta.php'; ?>        
		<!-- dynamic meta  keywords -->
        <?php include './scripts/css.php'; ?>
        <?php include './scripts/js.php'; ?>

<script type="text/javascript">
$(window).load(function() {
	$('#slider').nivoSlider({
		effect:'random',
		slices:10,
		animSpeed:500,
		pauseTime:5000,
		startSlide:0, //Set starting Slide (0 index)
		directionNav:false,
		directionNavHide:false, //Only show on hover
		controlNav:false, //1,2,3...
		controlNavThumbs:false, //Use thumbnails for Control Nav
		pauseOnHover:true, //Stop animation while hovering
		manualAdvance:false, //Force manual transitions
		captionOpacity:0.8, //Universal caption opacity
		beforeChange: function(){},
		afterChange: function(){},
		slideshowEnd: function(){} //Triggers after all slides have been shown
	});
});
</script>
</head>

<body>
	<div class="headersection templete clear">
		<a href="#">
        <?php
$query = "SELECT * FROM site_info LIMIT 1";
$post = $db->select($query);
    //print_r($post); 
if ($post) {
    while ($result = $post->fetch_assoc()) {
         //print_r($result);

        ?>


			<div class="logo">
				<img src="admin/<?php echo $result['logo'] ?>" alt="Logo"/>
                <h2><?php echo $result['title'] ?></h2>
                <p><?php echo $result['slogan'] ?></p>

            </div>
    
    <?php }
    } ?>        
		</a>
		<div class="social clear">
			<div class="icon clear">
                <?php
                    $query = "SELECT * FROM tbl_social ORDER BY id LIMIT 1";
                    $s_link = $db->select($query);
                    if ($s_link) {
                        while ($result = $s_link->fetch_assoc()) {
                            #print_r($result);
                            ?>

                            <a href="<?php echo $result['fb'] ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="<?php echo $result['tw'] ?>" target="_blank"><i class="fa fa-github"></i></a>
                            <a href="<?php echo $result['ln'] ?>" target="_blank"><i class="fa fa-linkedin"></i></a>
                            
                    <?php }
                } ?>		
			</div>
			<div class="searchbtn clear">
			        <form action="search.php" method="get">
                        <input type="text" name="search" placeholder="Search..."/>
                        <input type="submit" name="submit" value="Search"/>
                    </form>
			</div>
		</div>
	</div>
    <div class="navsection templete">
            <ul>
                <li><a

                <?php
                if (isset($_GET['page_id']) && $_GET['page_id'] == $result['id']) {
                    echo 'id="active"';
                }
                ?>
                        href="index.php">Home</a></li>
<?php
$query = "SELECT * from pages";
$pages = $db->select($query);
if ($pages) {
    while ($result = $pages->fetch_assoc()) {
        ?>
            <li><a href="page.php?page_id=<?php echo $result['id'] ?>"><?php echo $result['name'] ?></a></li>
    <?php }
} ?>
                <li><a href="contact_us.php">Contact</a></li>
            </ul>
        </div>