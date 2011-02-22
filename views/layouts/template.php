
<?php include('_header.php'); ?>
	
    <div id="content">
	<?php $content = View::load("testView");
	echo $content;
	?>
    </div>
    
<?php include('_footer.php'); ?>
