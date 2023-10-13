
<?php
	session_start();
	$currentPage = "Erreur 404";
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SwapMarket</title>
	<link rel="stylesheet" type="text/css" href="css/style-404.css">
</head>
<body>
	<!--header -->
	<div id = "header">
		<div class = title>
			<div>
				<a href="index.php">
					<img class= img src="img/logo.png" width="76" height="52" alt="">
				</a>
			</div>
			<div class = "TitleText">
				<a href="index.php"><h1>SwapMarket</h1></a>
			</div>
		</div>
	</div>

	<div id = content>
			<h2>
				Erreur 404...
			</h2>
			<h2>
				Il n'y à rien à voir içi !
			</h2
	</div>

	<?php include 'includes/footer.php'; ?>
	
</body>
</html>