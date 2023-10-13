<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SwapMarket</title>
	<link rel="stylesheet" type="text/css" href="CSS/style_poster.css">
</head>


<?php
    global $conn;
    session_start();
    include "php/config.php";
    if(!isset($_SESSION['unique_id'])){
        header("location: login.php");
    }
    // include_once "includes/header.php";
    //$choix_table = "SELECT * from post p where  user_id != {$_SESSION['unique_id']}";
    $choix_table ="select o.* from (select * from post where user_id = {$_SESSION['unique_id']} and post_state='En ligne') as u, post as o where u.model_name=o.model_name and u.post_type!=o.post_type and ((u.price=o.price) or (u.post_type='Achète' and ((u.extreme_price=0 and ((o.extreme_price=0 and u.price>=o.price) or (o.extreme_price!=0 and u.price>=o.extreme_price))) or (u.extreme_price!=0 and ((o.extreme_price=0 and u.extreme_price>=o.price) or (o.extreme_price!=0 and u.extreme_price>=o.extreme_price))))) or (u.post_type='Vend' and ((u.extreme_price=0 and ((o.extreme_price=0 and u.price>=o.price) or (o.extreme_price!=0 and u.price>=o.extreme_price))) or (u.extreme_price!=0 and ((o.extreme_price=0 and u.extreme_price<=o.price) or (o.extreme_price!=0 and u.extreme_price<=o.extreme_price)))))) and u.size=o.size and u.used=o.used and o.post_state='En ligne' and u.user_id != o.user_id";
    $ensemble_post = mysqli_query($conn,$choix_table);
    $contenu = mysqli_fetch_all($ensemble_post, MYSQLI_BOTH);
    $taille_contenu = mysqli_num_rows($ensemble_post);
?>



<html>
    <head>
        <title>SwapMarket</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/style_produit.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php 
        include 'includes/menu_navigation.php';
        include 'php/config.php';
		global $db;
	    ?>
        <div id = content>
                <h3>Les offres</h3>
                <link href="./home.css" rel="stylesheet" />
                        <div class="home-feature-card">
                        <div class="home-container1">
                            <div class="home-container2">
                            <div class="home-container3">
                                <h2 class="home-text">Nom chaussure</h2>
                                <button class="home-button button">
                                <svg viewBox="0 0 1024 1024" class="home-icon">
                                    <path
                                    d="M810 274l-238 238 238 238-60 60-238-238-238 238-60-60 238-238-238-238 60-60 238 238 238-238z"
                                    ></path>
                                </svg>
                                </button>
                            </div>
                            </div>
                        </div>
                        <img
                            alt="image"
                            src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?crop=entropy&amp;cs=tinysrgb&amp;fit=max&amp;fm=jpg&amp;ixid=Mnw5MTMyMXwwfDF8c2VhcmNofDF8fHNob2VzfGVufDB8fHx8MTY3MDM0MTgyNg&amp;ixlib=rb-4.0.3&amp;q=80&amp;w=200"
                            class="home-image"
                        />
                        <span class="home-text1">Price : 80€</span>
                        <span class="home-text2">Contact : 0780415684 </span>
                        </div>
                <?php 
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
    </body>
</html>