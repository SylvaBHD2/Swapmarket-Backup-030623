<?php
global $conn;
session_start();
	include_once "php/config.php";
	if(!isset($_SESSION['unique_id'])){
		header("location: login.php");
	}
		include 'includes/menu_navigation.php'; 
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SwapMarket</title>
	<link rel="stylesheet" type="text/css" href="css/style_research.css">
</head>

<body>
	<h3>Rechercher une annonce</h3>
	<div class="form" id="forumlaire">
		
		<form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
			<!-- Debut de la solution pour menu déroulant sans erreur -->
			<label> Quel modèle? </label> 
			<input type="text" name="model" id="model" onkeyup="showSuggestions(this.value)">
			<div id="suggestions"></div>
			<input type="hidden" name="proposition" id="proposition" value="">
			<?php
				$sql = "SELECT DISTINCT model_name FROM model";
				$result = $conn->query($sql);

				$model_names = array();

				if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					array_push($model_names, $row["model_name"]);
				}
				}
				else {
					echo "0 results";
				  }

				$conn->close();

			?>
			<script>
			function sendPropostion(i) {
				var string_fodder = document.getElementById("suggestion" + i).innerHTML.replaceAll("\"","\\\"");
				document.getElementById("proposition").value = string_fodder + "[]" + document.getElementById("proposition").value;
			}
			</script>

			<script>
			var model_names = <?php echo json_encode($model_names); ?>;
			function showSuggestions(str) {
				document.getElementById("proposition").value = "";
			var suggestionsDiv = document.getElementById("suggestions");
			suggestionsDiv.innerHTML = "";

			if (str.length === 0) {
				return;
			}

			var regex = new RegExp(str, "i");
			var matchingNames = model_names.filter(function(name) {
				return regex.test(name);
			});

			var numeros = 0;

			matchingNames.forEach(function(name) {
				var suggestion = document.createElement("div");
				
				suggestion.innerHTML = name;
				suggestion.id = "suggestion" + numeros;
				suggestionsDiv.appendChild(suggestion);
				sendPropostion(numeros);
				numeros = numeros + 1;
			});
			}
			</script>
			<!-- fin de la solution pour menu déroulant de suggestions (marche pas) -->
			<div class="error-text"></div>
			
			<div class="name-details">

				<div class="field input">
					<label>Vente ou achat ?</label><br>
                    <label for="Vend"> Vente
                        <input type="radio" name="askbid" value="Vend" >
                    </label>
                    <label for="Achète"> Achat
                        <input type="radio" name="askbid" value="Achète" >
                    </label>
				</div>
				
				<div class="field input">
					<p>Quel est votre prix?
                    <label for="price">Prix :
                        <input type="number" name="price" placeholder="en euros" >
                    </label>€
				</div>
			</div>
			<div class="field input">
				<label>Taille ? </label>
                <label for="size">Taille :
                    <input type="text" name="size" placeholder="Taille" >
                </label>
            </div>
			<div class="field input">
				<label>Déja porté ? </label>
                <label for="used">
                    Oui<input type="radio" name="used" id="used" value="Yes" >
                    Non<input type="radio" name="used" id="used" value="No" >
                </label>
			</div>
			<!-- submit the form -->
			<div class="button">
				<input type="submit" name="formulaire" value="Recherche">
			</div>
		</form>
    </div>
	<?php
	include "php/config.php";
	$sql = "SElECT * FROM post";
    if(isset($_POST["formulaire"])){
		extract($_POST);
		if(!$conn){
			echo "Database connection error".mysqli_connect_error();
		}
        /*
		$model = mysqli_real_escape_string($conn, $_POST['model']);
		$askbid = mysqli_real_escape_string($conn, $_POST['askbid']);
		$price = mysqli_real_escape_string($conn, $_POST['price']);
		$extr_price = mysqli_real_escape_string($conn, $_POST['extr_price']);
		$size = mysqli_real_escape_string($conn, $_POST['size']);
		$used = mysqli_real_escape_string($conn, $_POST['used']);
        */
		//main crawl
		//post(post_id,model_name	post_type,price	size,used,extreme_price,int_interactions,post_state	model_id,photo)
		//var_dump($_POST);
		if ( $_POST['proposition'] != '' || isset($_POST['askbid']) || $_POST['price'] != '' || $_POST['size'] != '' || isset($_POST['used'])){
			$sql = $sql . " WHERE";
					if (isset($_POST['proposition'])) {
						//var_dump(isset($_POST['proposition']));
						if($_POST['proposition'] != "") {
							//var_dump($_POST['proposition'] != '');
							$sql = $sql . " (";
							$tmp_liste = mysqli_real_escape_string($conn, $_POST['proposition']);
							$liste_proposition = explode("[]",$_POST['proposition']);
							$taille_liste = sizeof($liste_proposition);
							$z = 0;
							//var_dump($liste_proposition);
							//var_dump($taille_liste);
							while($z < $taille_liste - 1) {
								$tmp_proposition = $liste_proposition[$z];
								$sql = $sql . "model_name = \"$tmp_proposition\"";
								$z = $z + 1;
								if ($z < $taille_liste - 1) {
									$sql = $sql . " or ";
								}
							}
							$sql = $sql . " )";
				if (isset($_POST['askbid']) || $_POST['price'] != '' || $_POST['size'] != ''  || isset($_POST['used'])) {
					$sql = $sql . " and";
				}}}
				if (isset($_POST['askbid']) ) { 
                    $askbid = mysqli_real_escape_string($conn, $_POST['askbid']);
                    $sql = $sql . " post_type = '$askbid'";
				if($_POST['price'] != '' || $_POST['size'] != ''  || isset($_POST['used'])) {
					$sql = $sql . " and";
				}
			}
            if (isset($_POST['price']) ) {
                if ( $_POST['price'] != '') {
                $price = mysqli_real_escape_string($conn, $_POST['price']);
                $sql = $sql . " (price = {$price} or (extreme_price != 0 and ((post_type = 'Achète' and extreme_price >= {$price}) or (post_type = 'Vend' and extreme_price <= {$price}) ) ) or (extreme_price = 0 and ((post_type = 'Achète' and price >= {$price}) or (post_type = 'Vend' and price <= {$price}) ) ))";
                if($_POST['size'] != ''  || isset($_POST['used'])) {
					$sql = $sql . " and";
				}}
            }
			if (isset($_POST['size'] )) {
                if ($_POST['size'] != '') {
                $size = mysqli_real_escape_string($conn, $_POST['size']);
				$sql = $sql . " size = {$size}";
				if(!isset($_POST['used'])) {
					$sql = $sql . " and";
				}}
			}
			if (isset($_POST['used'])) {
                $used = mysqli_real_escape_string($conn, $_POST['used']);
				$sql = $sql . " used = '$used'";
			}
            /*
			$sql = "SElECT * FROM post post(model_name,post_type,price,size,used,extreme_price,int_interactions,post_state,model_id,photo) 
			VALUES('$model','$askbid',{$price},'$size','$used',{$extr_price},{$interactions},'$post_state',{$rand},'$photo')";
			*/
			//echo "la requete est : $sql";
            //printf($sql);
            //printf($sql);
			$ensemble_post = mysqli_query($conn, $sql);
			// catch the error if the query fails
			if (!$ensemble_post) {
				printf("Error: %s\n", mysqli_error($conn));
				
			}
			//echo($ensemble_post);
		}
		//verify the connection
	}
	//var_dump($sql);
    $ensemble_post = mysqli_query($conn, $sql); 
    $contenu = mysqli_fetch_all($ensemble_post, MYSQLI_BOTH);
    $taille_contenu = mysqli_num_rows($ensemble_post);
    /*
    $taille_contenu = 0;
    exit();
    while(!empty($contenu[$taille_contenu])) {
        $taille_contenu = $taille_contenu + 1;
    }
    */
	$i = 0;
	while ($i < $taille_contenu) {
		//$tempo_photo = $contenu[$i]["photo"];
		?>
		<div class="produit">
			<!-- user profile linked to the post -->
			<a href="/profile.php?user_id=<?php echo $contenu[$i]["user_id"]?>">
				<?php 
					// include "php/data.php;" 
					$sql = "SELECT * FROM users WHERE unique_id = {$contenu[$i]["user_id"]}";
					$output = "";
					// echo $sql;
					$query = mysqli_query($conn, $sql);
					// echo $query;
					if(mysqli_num_rows($query) > 0){
						while($row = mysqli_fetch_assoc($query)){
							$output .= '<a href="profile.php?user_id='. $row['unique_id'] .'">
										<div class="wrapper">
											<img src="php/images/'. $row['img'] .'" alt="">
												<div class="details">
													<span>'. $row['fname']. " " . $row['lname'] .'</span>
												 </div>
										</div>
									</a>';
						}
					}else{
						$output .= 'No user found related to your search term';
					}
					echo $output;
				?>
			</a>
			<h3>
				<?php echo $contenu[$i]["post_type"],"</br>"; 
				$liste_picture = explode(":$.",$contenu[$i]['photo']);
				$nombre_picture = sizeof($liste_picture);
				$j = 0;
				?>
				<img class="image_produit" id="<?php echo "product_picture{$i}"?>" src=php/images/<?php echo $liste_picture[$j] ?> alt="image indisponibles..."/>
				<?php
				if($nombre_picture > 1) { ?>
				<select name="picture_choose">
					<?php while($j < $nombre_picture - 1) { ?>
					<option value="php/images/<?php echo $liste_picture[$j]?>" oncLick="Change<?php echo "{$i}{$j}" ?>(this);">Picture <?php echo $j ?></option>
					<script>
						function Change<?php echo "{$i}{$j}" ?>() {
							document.getElementById("<?php echo "product_picture{$i}"?>").src = "php/images/<?php echo $liste_picture[$j]?>";
						};
					</script>
					<?php 
					$j = $j + 1;
				} ?>
				</select>
				<?php } ?>
			</h3>
				<?php echo $contenu[$i]["model_name"],"</br> Prix : ",$contenu[$i]["price"],"€</br>Déjà portée : ",
				$contenu[$i]["used"],"</br> prix extreme : ",$contenu[$i]["extreme_price"]; ?>
			
		</div>
		<?php
		$i = $i + 1;
	}
	?>

<footer>
    <?php include "includes/footer.php" ?>
</footer>
</body>
</html>