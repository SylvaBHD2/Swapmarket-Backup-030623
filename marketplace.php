
<?php
    global $conn;
    session_start();
    include "php/config.php";
    if(!isset($_SESSION['unique_id'])){
        header("location: login.php");
    }

    // get the parameters from the url
    // get the parameters from the url
    $types = isset($_GET['type']) ? $_GET['type'] : null;
    $sizes = isset($_GET['size']) ? $_GET['size'] : null;
    $priceinterval = isset($_GET['price']) ? $_GET['price'] : null;
    $sort = isset($_GET['sort']) ? $_GET['sort'] : null;

    $choix_table = "SELECT * from post";

    // appliquer les filtres
    if ($types != null) {
        $types = explode(",", $types);
        // replace sell by Vend and buy by Achète and trade by Echange
        for ($i = 0; $i < sizeof($types); $i++) {
            if ($types[$i] == "sell") {
                $types[$i] = "Vend";
            } else if ($types[$i] == "buy") {
                $types[$i] = "Achète";
            } else if ($types[$i] == "trade") {
                $types[$i] = "Echange";
            }
        }
        $choix_table = $choix_table . " WHERE post_type IN (";
        foreach ($types as $type) {
            $choix_table = $choix_table . "'" . $type . "',";
        }
        $choix_table = substr($choix_table, 0, -1);
        $choix_table = $choix_table . ")";
    }

    if ($sizes != null) {
        $sizes = explode(",", $sizes);
        // if there is a type pararameter, we need to add an AND
        //else we need to add a WHERE
        if ($types != null) {
            $choix_table = $choix_table . " AND (";
        } else {
            $choix_table = $choix_table . " WHERE (";
        }
        $choix_table = $choix_table . "size IN (";
        foreach ($sizes as $size) {
            $choix_table = $choix_table . "'" . $size . "',";
        }
        $choix_table = substr($choix_table, 0, -1);
        $choix_table = $choix_table . "))";

    }

    if ($priceinterval != null) {
        $priceinterval = explode(",", $priceinterval);
        // if there is a size pararameter, we need to add an AND
        //else we need to add a WHERE
        if ($sizes != null || $types != null) {
            $choix_table = $choix_table . " AND (";
        } else {
            $choix_table = $choix_table . " WHERE (";
        }

        // all intervals look like a-b, so we need to split them
        foreach ($priceinterval as $interval) {
            $interval = explode("-", $interval);
            $choix_table = $choix_table . "price BETWEEN " . $interval[0] . " AND " . $interval[1] . " OR ";
        }
        $choix_table = substr($choix_table, 0, -4);
        $choix_table = $choix_table . ")";
    }

    // appliquer le tri
    if ($sort != null) {
        if ($sort=="relevance" || $sort=="date_desc") {
            $sort = "date_post DESC";
        } else if ($sort=="date_asc") {
            $sort = "date_post ASC";
        } else if ($sort=="price_desc") {
            $sort = "price DESC";
        } else if ($sort=="price_asc") {
            $sort = "price ASC";
        }
        $choix_table = $choix_table . " ORDER BY " . $sort;
    }

    // recuperer les 500 premiers posts

    echo "<script>console.log(\"$choix_table\");</script>";
    $ensemble_post = mysqli_query($conn,$choix_table);
    $contenu = mysqli_fetch_all($ensemble_post, MYSQLI_BOTH);
    $taille_contenu = mysqli_num_rows($ensemble_post);
?>


<html>
    <head>
        <title>SwapMarket</title>
        <meta charset="utf-8">

        <link rel="stylesheet" href="css/products_style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="javascript/products.js"></script>
        <script src="javascript/products_sort&filter.js"></script>
    </head>
    <body>
        <?php
        include 'includes/menu_navigation.php';
        include 'php/config.php';
		global $db;
	    ?>

        <!-- AJOUTS -->

        <div class = "content">
            <div class = "banner">
                <video autoplay muted loop id="banner-video">
                    <source src="assets/video/slim_banner_video_1080.mp4" type="video/mp4">
                </video>
                <div class = "banner_content">
                    <h1>Produits</h1>
                    <h2></h2>
                </div>
            </div>
            <div class = main-container>
                <div class = product-area-head>
                    <div class = applied-filters>
                        <p>Filtres appliqués (cliquez pour retirer) : </p>
                    </div>
                    <p class = "nb-results"><?php echo $taille_contenu ?> résultats</p>
                    <div class = sort>
                        <label for="sort">Trier par</label>
                        <select name="sort" id="sort" onchange="changeSort(this);">
                            <option value="relevance">Pertinence</option>
                            <option value="price_asc">Prix (croissant)</option>
                            <option value="price_desc">Prix (décroissant)</option>
                            <option value="date_asc">Date de sortie (croissant)</option>
                            <option value="date_desc">Date de sortie (décroissant)</option>
                        </select>
                    </div>
                </div>
                <div class = product-area>
                    <div class = filters>
                        <div class = filter id=type-filter>
                            <p>Type d'annonce</p>
                            <div class = filter-content>
                                <div class = filter-item>
                                    <input type="checkbox" id="sell" name="type" value="sell" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="sell">Vente</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="buy" name="type" value="buy" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="buy">Achat</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="trade" name="type" value="trade" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="trade">Echange</label>
                                </div>
                            </div>
                        </div>
                        <div class = filter id=size-filter>
                            <p>Taille</p>
                            <ul class = filter-content>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-33" name="size" value="33" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-33">33</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-34" name="size" value="34" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-34">34</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-35" name="size" value="35" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-35">35</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-36" name="size" value="36" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-36">36</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-37" name="size" value="37" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-37">37</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-38" name="size" value="38" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-38">38</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-39" name="size" value="39" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-39">39</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-40" name="size" value="40" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-40">40</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-41" name="size" value="41" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-41">41</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-42" name="size" value="42" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-42">42</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-43" name="size" value="43" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-43">43</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-44" name="size" value="44" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-44">44</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-45" name="size" value="45" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-45">45</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-46" name="size" value="46" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-46">46</label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="size-47" name="size" value="47" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="size-47">47</label>
                                </li>
                            </ul>
                        </div>
                        <div class = filter id=color-filter>
                            <p>Couleur</p>
                            <div class = filter-content>
                                <div class = filter-item>
                                    <input type="checkbox" id="color-red" name="color" value="red" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="color-red">Rouge</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="color-blue" name="color" value="blue" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="color-blue">Bleu</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="color-green" name="color" value="green" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="color-green">Vert</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="color-yellow" name="color" value="yellow" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="color-yellow">Jaune</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="color-black" name="color" value="black" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="color-black">Noir</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="color-white" name="color" value="white" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="color-white">Blanc</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="color-grey" name="color" value="grey" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="color-grey">Gris</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="color-pink" name="color" value="pink" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="color-pink">Rose</label>
                                </div>
                            </div>
                        </div>
                        <div class = filter id=brand-filter>
                            <p>Marque</p>
                            <div class = filter-content>
                                <div class = filter-item>
                                    <input type="checkbox" id="brand-adidas" name="brand" value="adidas" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="brand-adidas">Adidas</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="brand-nike" name="brand" value="nike" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="brand-nike">Nike</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="brand-puma" name="brand" value="puma" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="brand-puma">Puma</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="brand-newbalance" name="brand" value="newbalance" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="brand-newbalance">New Balance</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="brand-jordan" name="brand" value="jordan" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="brand-jordan">Jordan</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="brand-vans" name="brand" value="vans" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="brand-vans">Vans</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="brand-converse" name="brand" value="converse" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="brand-converse">Converse</label>
                                </div>
                                <div class = filter-item>
                                    <input type="checkbox" id="brand-asics" name="brand" value="asics" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="brand-asics">Asics</label>
                                </div>
                            </div>
                        </div>
                        <div class = filter id=price-filter>
                            <p>Prix</p>
                            <ul class = filter-content>
                                <li class = filter-item>
                                    <input type="checkbox" id="price-0-50" name="price" value="0-50" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="price-0-50"><span>0-50€</span></label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="price-50-100" name="price" value="50-100" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="price-50-100"><span>50-100€</span></label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="price-100-150" name="price" value="100-150" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="price-100-150"><span>100-150€</span></label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="price-150-200" name="price" value="150-200" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="price-150-200"><span>150-200€</span></label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="price-200-250" name="price" value="200-250" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="price-200-250"><span>200-250€</span></label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="price-250-300" name="price" value="250-300" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="price-250-300"><span>250-300€</span></label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="price-300-350" name="price" value="300-350" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="price-300-350"><span>300-350€</span></label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="price-350-400" name="price" value="350-400" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="price-350-400"><span>350-400€</span></label>
                                </li>
                                <li class = filter-item>
                                    <input type="checkbox" id="price-400-450" name="price" value="400-450" onclick="if (this.checked) addFilter(this); else removeFilter(this)">
                                    <label for="price-400-450"><span>400-450€</span></label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class = products>
                        <?php
                        $i = 0;
                        while ($i < $taille_contenu) {
                            ?>
                            <div class="product">




                                <!-- user profile linked to the post -->
                                    <?php

                                    // include "php/data.php;"
                                    $sql = "SELECT * FROM users WHERE unique_id = {$contenu[$i]["user_id"]}";
                                    $output = "";
                                    // echo $sql;
                                    $query = mysqli_query($conn, $sql);
                                    // echo $query;
                                    if(mysqli_num_rows($query) > 0){
                                        while($row = mysqli_fetch_assoc($query)){
                                            $output .= '<a class="user" href="profile.php?user_id='. $row['unique_id'] .'">
                                                        <img src="php/images/profile_picture/'. $row['img'] .'" alt="">
                                                        <span>'. $row['fname']. " " . $row['lname'] .'</span>
                                                </a>';
                                        }
                                    }else{
                                        $output .= 'No user found related to your search term';
                                    }
                                    echo $output;
                                    ?>








                                <!-- user profile linked to the post -->
                                <?php
                                $liste_picture = explode(":$.",$contenu[$i]['photo']);
                                $nombre_picture = sizeof($liste_picture);
                                $j = 0;
                                ?>
                                <img id="<?php echo "product_picture{$i}"?>" src="php/images/posts/<?php echo $liste_picture[$j] ?>" alt="image indisponibles...">

                                <div class = product-content>
                                    <div class="product-top">
                                        <p class = product-name><?php echo $contenu[$i]["model_name"]?></p>
                                        <?php
                                        if($nombre_picture > 1) { ?>
                                            <select name="picture_choose">
                                                <?php while($j < $nombre_picture - 1) { ?>
                                                    <option value="php/images/posts/<?php echo $liste_picture[$j]?>" oncLick="Change<?php echo "{$i}{$j}" ?>(this);">Picture <?php echo $j ?></option>
                                                    <script>
                                                        function Change<?php echo "{$i}{$j}" ?>() {
                                                            document.getElementById("<?php echo "product_picture{$i}"?>").src = "php/images/posts/<?php echo $liste_picture[$j]?>";
                                                        };
                                                    </script>
                                                    <?php
                                                    $j = $j + 1;
                                                } ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                    <div class = product-bottom>
                                        <div class = product-price>
                                            <p>à partir de</p>
                                            <p class = price ><?php echo $contenu[$i]["price"]?> €</p>
                                        </div>

                                        <div class = product-wt>
                                            <p>WT</p>
                                            <div class = wt-sbt>
                                                <!-- if $contenu[$i]["post_type"] == "vente" ajouter la classe selected à la lettre correspondante -->
                                                <?php
                                                if($contenu[$i]["post_type"] == "Vend") { ?><p id="S" class="selected">S</p>
                                                <?php } else { ?> <p id="S" class="">S</p><?php }
                                                if($contenu[$i]["post_type"] == "Achète") { ?><p id="B" class="selected">B</p>
                                                <?php } else { ?> <p id="B" class="">B</p><?php }
                                                if($contenu[$i]["post_type"] == "Echange") { ?><p id="T" class="selected">T</p>
                                                <?php } else { ?> <p id="T" class="">T</p><?php }
                                                ?>
                                            </div>
                                        </div>
                                        <div class = product-price>
                                            <p>taille</p>
                                            <p class = price><?php echo (int)$contenu[$i]["size"]?></p>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <?php
                            $i = $i + 1;
                        }
                        ?>
                    </div>
                </div>
                <div class="pages">
                    <!-- when we click on the arrow, if it has the class disabled, we don't do anything, otherwise we call the function changePage with the parameter "previous" or "next" -->
                    <div class=arrows>
                        <h3 class = "left-arrow" onclick="if (!this.classList.contains('disabled')) changePage('previous')"><</h3>
                        <h3 class = "right-arrow" onclick="if (!this.classList.contains('disabled')) changePage('next')">></h3>
                    </div>
                    <p class = page_state></p>
                </div>

            </div>
        </div>




        <!-- AVANT MODIF -->

        <div id = content>

        </div>
    </body>
</html>