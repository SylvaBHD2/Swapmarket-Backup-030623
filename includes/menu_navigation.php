
<?php global $conn; ?>


<link rel="stylesheet" type="text/css" href="/css/header_style.css">
<header>
    <div class = "top-header">
        <p>Abonnez-vous à notre newsletter pour obtenir 20 crédits gratuitement !</p>
    </div>
    <div class = "bottom-header">
        <div class = left-side>
            <div class = "logo">
                <a href="index.php">
                    <img id = logo_img src="../assets/img/logo_black.png" alt="logo">
                    <img id="logo_txt" src="../assets/img/compagnie_name_black.png" alt="logo">
                </a>
            </div>
        </div>
        <div class = middle-side>
            <div class = "menu">
                <ul>
                    <li><a href = "#">Mes annonces</a></li>
                    <li><a href = "/posts.php">Poster une annonce</a></li>
                    <li><a href = "/marketplace.php">Produits</a></li>
                    <li><a href = "/users.php">Chatter</a></li>
                    <li><a href = "/research.php">Rechercher</a></li>
                    <li><a href = "/recommandation.php">Mes matchs</a></li>
                </ul>
            </div>
        </div>
        <?php
        if(isset($_SESSION['unique_id'])){
            ?>
            <div class = "right-side">

                <div class="menu">
                    <ul>
                        <li>
                            <p>Bienvenue<br/>
                                <a href="/profile.php?user_id=
                                    <?php echo $_SESSION['unique_id']?>">
                                    <?php echo $_SESSION['username']?>
                                </a>
                            </p>
                        </li>
                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                        if(mysqli_num_rows($sql) > 0){
                            $row = mysqli_fetch_assoc($sql);
                        }
                        ?>
                        <li><a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Déconnexion</a></li>
                    </ul>
                </div>
            </div>

            <?php
        }else{
            ?>
            <div class = "right-side">
                <div class="menu">
                    <ul>
                        <li><a href="/login.php">Se connecter</a></li>
                        <li><a href="/register.php">S'inscrire</a></li>
                    </ul>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</header>