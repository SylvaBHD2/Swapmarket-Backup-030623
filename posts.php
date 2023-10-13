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
	<link rel="stylesheet" type="text/css" href="css/style_poster.css">
    <link rel="stylesheet" href="css/ad_post_style.css">
</head>

<body>
<div class = "content">
    <div class = "banner">
        <video autoplay muted loop id="banner-video">
            <source src="../assets/video/slim_banner_video_1080.mp4" type="video/mp4">
        </video>
        <div class = "banner_content">
            <h1>Poster une annonce</h1>
            <h2></h2>
        </div>
    </div>

    <div class="post-area">
        <div class="top">
            <div class="post_button selected" id="sell_form_button"
                 onclick="document.getElementById('buy_form').classList.remove('selected'); document.getElementById('buy_form_button').classList.remove('selected');
                      document.getElementById('trade_form').classList.remove('selected'); document.getElementById('trade_form_button').classList.remove('selected');
                      document.getElementById('sell_form').classList.add('selected'); this.classList.add('selected');">
                <h3>Vendre</h3>
            </div>
            <div class="post_button" id="trade_form_button"
                 onclick="document.getElementById('sell_form').classList.remove('selected'); document.getElementById('sell_form_button').classList.remove('selected');
                            document.getElementById('buy_form').classList.remove('selected'); document.getElementById('buy_form_button').classList.remove('selected');
                            document.getElementById('trade_form').classList.add('selected'); this.classList.add('selected');">
                <h3>Echanger</h3>
            </div>
            <div class="post_button" id="buy_form_button"
                 onclick="document.getElementById('sell_form').classList.remove('selected'); document.getElementById('sell_form_button').classList.remove('selected');
                      document.getElementById('trade_form').classList.remove('selected'); document.getElementById('trade_form_button').classList.remove('selected');
                      document.getElementById('buy_form').classList.add('selected'); this.classList.add('selected');">
                <h3>Acheter</h3>
            </div>
        </div>


        <form action="#" method="POST" class = "form selected" id="sell_form" enctype="multipart/form-data" autocomplete="off">
			<!-- Debut de la solution pour menu déroulant sans erreur -->
            <div class="input-container">
                <input type="text" name="model" id="model" list="suggestions" onkeyup="showSuggestions(this.value);" required>
                <label for="model">Modèle</label>
                <datalist id="suggestions"></datalist>
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

                $conn->close();

                ?>
                <script>
                    var model_names = <?php echo json_encode($model_names); ?>;


                    function showSuggestions(str) {
                        var suggestionsDiv = document.getElementById("suggestions");
                        suggestionsDiv.innerHTML = "";

                        if (str.length === 0) {
                            return;
                        }

                        var regex = new RegExp(str, "i");
                        var matchingNames = model_names.filter(function(name) {
                            return regex.test(name);
                        });

                        matchingNames.forEach(function(name) {
                            var suggestion = document.createElement("option");
                            suggestion.innerHTML = name;
                            suggestionsDiv.appendChild(suggestion);
                        });

                        // check how many suggestions there are
                        if (matchingNames.length === 0) {
                            //add the class error to the input box
                            document.getElementById("model").classList.add("error");
                            // display a message to the user
                            document.getElementsByClassName("error-text")[0].innerHTML = "No matching names found";
                        }
                        else {
                            //remove the class error from the input box
                            document.getElementById("model").classList.remove("error");
                            // display a message to the user
                            document.getElementsByClassName("error-text")[0].innerHTML = "";
                        }
                    }
                </script>
            </div>
            <div class="error-text">test</div>
            <div class="input-container_radio">
                <span>Vente ou achat :</span>

                <input type="radio" name="askbid" value="Vend" required>
                <label for="Vend"> Vente</label>

                <input type="radio" name="askbid" value="Achète" required>
                <label for="Achète"> Achat</label>
            </div>

            <div class="input-container"> <!-- taille -->
                <input type="number" id="price" name="price" min="0" required>
                <label for="price">Taille</label>
            </div>

			<div class="input-container_radio">
				<label for="checkbox"> Négociable ?</label>
                <input type="checkbox" id="checkbox">
                <div id="text-field-container">
                    <label for="extr_price"></label><input type="price" name="extr_price" id="extr_price">
                </div>
                <script src="javascript/revealbox.js"></script>
			</div>

            <div class="input-container"> <!-- taille -->

                <input type="number" id=size name="size" min="0" required>
                <label for="size">Taille</label>
            </div>


			<!-- image insertion de photo de profil-->
            <div class = "input-container_photo"> <!-- upload de plusieurs photos -->
                <label for="photos-input-sell">Photos :</label>
                <input type="file" multiple name="image[]" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
            </div>
			<!-- submit the form -->
			<div class="submit-container">
				<input type="submit" name="formulaire" value="Poster">
			</div>
		</form>
    </div>
	
        <?php
        // session_start();
        // include_once "config.php";
        include "php/config.php";
        //$conn = mysqli_connect("localhost","root","","swapmarket_b");
        //session_start();
        // echo "Les champs ne sont pas tous remplis : $model, $price, $size, $askbid, $size, $used, $photo ";
        if(isset($_POST["formulaire"])){
            extract($_POST);
            if(!$conn){
                echo "Database connection error".mysqli_connect_error();
            }
            $model = mysqli_real_escape_string($conn, $_POST['model']);
            $askbid = mysqli_real_escape_string($conn, $_POST['askbid']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $extr_price = mysqli_real_escape_string($conn, $_POST['extr_price']);
            $size = mysqli_real_escape_string($conn, $_POST['size']);
            $used = mysqli_real_escape_string($conn, $_POST['used']);
            $userid = $_SESSION['unique_id'];
            //main crawl
            $number_of_files = sizeof($_FILES['image']['name']);
            $i = 0;
            $liste_picture = "";
            while ((isset($_FILES['image'])) && ($i < $number_of_files)){
                $img_name = $_FILES['image']['name'][$i];
                $img_type = $_FILES['image']['type'][$i];
                $tmp_name = $_FILES['image']['tmp_name'][$i];
                $img_explode = explode('.',$img_name);
                // echo $img_explode;
                $img_ext = end($img_explode);
                $img_beg = $img_explode[0];
                $extensions = ["jpeg", "png", "jpg"];
                if(in_array($img_ext, $extensions) === true){
                    $types = ["image/jpeg", "image/jpg", "image/png"];
                    if(in_array($img_type, $types) === true){
                        $time = time();
                        $new_img_name = $img_beg.$time.".".$img_ext;
                        if(move_uploaded_file($tmp_name,"php/images/posts/".$new_img_name)){
                            if ( !empty($new_img_name) && $number_of_files > 1){
                                $liste_picture = $liste_picture . $new_img_name . ":$.";
                            }else{
                            $liste_picture = $new_img_name;
                            }
                        }
                    }
                }else{
                    echo "Please upload an image file - jpeg, png, jpg";
                }
                $i = $i + 1;
            }
        }
            if(isset($_FILES['image'])) {
                $ran_id = rand(time(), 100000000);
                // local variables
                $post_state = "En ligne";
                $rand = rand(time(), 100000000);
                $interactions = 0;
                if (empty($extr_price)){
                    $extr_price = 0;
                }
                if ( !empty($model) && !empty($price) && !empty($size) && !empty($askbid) && !empty($size) && !empty($used)){
                    $sql = "INSERT INTO post(model_name,post_type,price,size,used,extreme_price,int_interactions,post_state,model_id, photo,user_id) VALUES('$model','$askbid',{$price},'$size','$used',{$extr_price},{$interactions},'$post_state',{$rand}, '$liste_picture',{$_SESSION['unique_id']})";
                    // echo "la requete est : $sql";
                    $insert_query = mysqli_query($conn, $sql);
                    // catch the error if the query fails
                    if (!$insert_query) {
                        printf("Error: %s\n", mysqli_error($conn));
                    }
                }else{
                    echo "Les champs ne sont pas tous remplis : $model,$askbid, $price, $size, $askbid, $size, $used, $new_img_name";
                }
    }
        ?>

</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>