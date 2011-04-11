<!doctype html>
<html>
	<head>
		<title> Test results </title>

		<style type="text/css">

			body {
				margin: 50px;
				font-family: Helvetica, Verdana, Sans-serif;
				font-size: 13px;
				color: #000;
			}

			h1 {
				font-size: 18px;
			}
			
			table.results td {
				vertical-align: top;
				padding: 10px;
				border-bottom: 1px solid #ccc;
			}

			.fail {
				background-color: #f9eeee;
				border: 1px solid #ffcfcf;
				border-bottom-width: 0;
			}
			
			.pass {
				background-color: #eef9ee;
				border: 1px solid #cfffcf;
				border-bottom-width: 0;
			}

		</style>
	</head>
	<body>

		<h1>Test results</h1>
		<table class="results" cellspacing="0">
			<?php foreach($results['tests'] as $method => $result): ?>
			<tr>
				<td width="100"><strong><?=$method?></strong></td>
				<td width="50" class="<?=$result['status']?>"><?=$result['status']?></td>
				<td width="600">
					<a href="#" onclick="var e = document.getElementById('asserts-<?=$method?>'); e.style.display = (e.style.display =='block') ? 'none' : 'block';"> <?=count($result['asserts'])?> assertions run</a>
					<ul id="asserts-<?=$method?>" style="display: none">
					<?php foreach ($result['asserts'] as $assert): ?>
						<li><strong><?= ($assert['status']) ? 'PASS' : 'FAIL' ?>:</strong> <?= $assert['message'] ?></li>
					<?php endforeach; ?>
					</ul>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>

	</body>
</html>