
<?php
ini_set('display_errors', 1);
global $username, $hostname, $password, $dbname;
	session_start();
	include 'php/config.php';
	global $db;
	$con = mysqli_connect($hostname,$username,$password,$dbname);
	$sql = "SELECT * from users";
	if ($result = mysqli_query($con, $sql)) {
    // Return the number of rows in result set
    $rowcount = mysqli_num_rows( $result );
	}
	// Close the connection
	mysqli_close($con);
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SwapMarket</title>
	<link rel="stylesheet" type="text/css" href="css/index_style.css">
</head>
<body>
	<!--header -->
	<?php include 'includes/menu_navigation.php'; ?>

    <div class = "content">
        <div class = "banner">
            <video autoplay muted loop id="banner-video">
                <source src="assets/video/banner_video_1080.mp4" type="video/mp4">
            </video>
            <div class = "banner_content">
                <div class="banner_content_top">
                    <a href = "products.html?type=buy"><p class = "buy_button">Buy</p></a>
                    <img src="assets/img/logo_white.svg" alt="logo">
                    <a href = "products.html?type=sell"><p class = "sell_button">Sell</p></a>
                </div>
                <a href = "ad_post.html"><p class = "post_button">Poster une annonce</p></a>
            </div>
        </div>
        <div class = cards>
            <div class = card>
                <div class = left-card>
                    <div class = text>
                        <h2>Buy</h2>
                        <p>Vous cherchez une paire de chaussures en particulier ? Utilisez cette fonction pour publier une demande d'achat et être contacté par les vendeurs potentiels. Il vous suffit de remplir le formulaire avec les détails de l'article que vous recherchez et les vendeurs pourront vous contacter avec leurs offres.</p>
                    </div>
                </div>
                <div class = right-card>
                    <!-- a video running in the right card background -->
                    <video autoplay muted loop id="wtb_video">
                        <source src="assets/video/wtb_video_720.mp4" type="video/mp4">
                    </video>
                </div>
            </div>

            <div class = card>
                <div class = left-card>
                    <video autoplay muted loop id="wts_video">
                        <source src="assets/video/wts_video_720.mp4" type="video/mp4">
                    </video>
                </div>
                <div class = right-card>
                    <div class = text>
                        <h2>Sell</h2>
                        <p>Vous avez des sneakers à vendre ? Utilisez notre fonction WTS pour publier votre annonce et être contacté par les acheteurs potentiels. Il vous suffit de remplir le formulaire et les acheteurs pourront vous contacter avec leurs offres. Vous pouvez également publier des photos pour présenter vos sneakers.</p>
                    </div>
                </div>
            </div>

            <div class = card>
                <div class = left-card>
                    <div class = text>
                        <h2>Trade</h2>
                        <p>Vous cherchez à échanger une paire de chaussures contre une autre ? Utilisez notre fonction WTT pour publier votre offre et être contacté par les vendeurs ou les acheteurs potentiels. Il vous suffit de remplir le formulaire  et les personnes intéressées pourront vous contacter pour discuter de l'échange.</p>
                    </div>
                </div>
                <div class = right-card>
                    <!-- a video running in the right card background -->
                    <video autoplay muted loop id="wtt-video">
                        <source src="assets/video/wtt_video_720.mp4" type="video/mp4">
                    </video>
                </div>
            </div>


        </div>
    </div>
	<?php include 'includes/footer.php'; ?>
</body>
</html>