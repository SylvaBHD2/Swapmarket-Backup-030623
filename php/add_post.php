<?php
global $conn;
session_start();
    include_once "config.php";
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
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
		if(isset($_FILES['image'])){
			$img_name = $_FILES['image']['name'];
			$img_type = $_FILES['image']['type'];
			$tmp_name = $_FILES['image']['tmp_name'];
			$img_explode = explode('.',$img_name);
			$img_ext = end($img_explode);
			$extensions = ["jpeg", "png", "jpg"];
			if(in_array($img_ext, $extensions) === true){
				$types = ["image/jpeg", "image/jpg", "image/png"];
				if(in_array($img_type, $types) === true){
					$time = time();
					$new_img_name = $time.$img_name;
					if(move_uploaded_file($tmp_name,"php/images/".$new_img_name)){
						$ran_id = rand(time(), 100000000);
						// local variables
						$post_state = "En ligne";
						$rand = rand(time(), 100000000);
						$interactions = 0;
						if (empty($extr_price)){
							$extr_price = 0;
						}
						if ( !empty($model) && !empty($price) && !empty($size) && !empty($askbid) && !empty($size) && !empty($used)&& !empty($new_img_name)){
							$sql = "INSERT INTO post(model_name,post_type,price,size,used,extreme_price,int_interactions,post_state,model_id,photo,user_id) 
							VALUES('$model','$askbid',{$price},'$size','$used',{$extr_price},{$interactions},'$post_state',{$rand},'$new_img_name',{$_SESSION['unique_id']})";
							echo "la requete est : $sql";
							$insert_query = mysqli_query($conn, $sql);
							// catch the error if the query fails
							if (!$insert_query) {
								printf("Error: %s\n", mysqli_error($conn));
								
							}
						}else{
						echo "Les champs ne sont pas tous remplis : $model,$askbid, $price, $size, $askbid, $size, $used, $new_img_name";
						}
					}
				}
			}else{
				echo "Please upload an image file - jpeg, png, jpg";
			}
		}else{
			echo "Please upload an image file - jpeg, png, jpg";
		}
	}
	?>


    