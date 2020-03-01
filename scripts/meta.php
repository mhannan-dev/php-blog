<?php
        if (isset($_GET['page_id'])) {
            $page_title_id = $_GET['page_id'];
            $query = "SELECT * from pages WHERE id='$page_title_id'";
            $pages = $db->select($query);
            if ($pages) {
                while ($result = $pages->fetch_assoc()) {
                    ?>
                    <title><?php echo $result['name']; ?>-<?php echo TITLE; ?></title>


                <?php
                }
            }
        } elseif (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $query = "SELECT * from tbl_post WHERE id='$post_id'";
            $posts = $db->select($query);
            if ($posts) {
                while ($result = $posts->fetch_assoc()) {
                    ?>
                    <title><?php echo $result['title']; ?>-<?php echo TITLE; ?></title>
                <?php }
            }
        } else { ?>
            <title><?php echo $fm->title(); ?>-<?php echo TITLE; ?></title>                        	
        <?php } ?>

        <meta name="language" content="English">
        <!-- dynamic meta  description -->
        <?php
        if (isset($_GET['id'])) {
            $desc_id = $_GET['id'];
            $query = "SELECT * FROM tbl_post WHERE id='$desc_id'";
            $descs = $db->select($query);

            if ($descs) {
                while ($result = $descs->fetch_assoc()) {
                    ?>
                    <meta name="description" content="<?php echo $result['meta_desc']; ?>">
        <?php }
    	}
		} else { ?>
            <meta name="description" content="<?php echo META_DESC; ?>">
		<?php } ?>
		<!-- dynamic meta  description -->

       <!-- dynamic meta  keywords -->
        <?php
        if (isset($_GET['id'])) {
            $keyword_id = $_GET['id'];
            $query = "SELECT * FROM tbl_post WHERE id='$keyword_id'";
            $keywords = $db->select($query);

            if ($keywords) {
                while ($result = $keywords->fetch_assoc()) {
                    ?>
                    <meta name="keywords" content="<?php echo $result['tags']; ?>">
        <?php }
    	}
		} else { ?>
            <meta name="keywords" content="<?php echo KEYWORDS; ?>">
		<?php } ?>