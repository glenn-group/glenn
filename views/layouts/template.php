
<?php include("../View.php");?>
<!DOCTYPE html>
<head>
<title>Title</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div id="header">
		<h1>Haj!</h1>
	</div>
	<div id="content">
	<?php $content = View::load("testView");
	echo $content;
	?>
	
    </div>
    <div id="footer">
		<h1>Footer</h1>
    </div>
	
	
</body>
</html>