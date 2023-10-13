<?php
global $conn;
session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "includes/menu_navigation.php"; ?>
<body>
  <!-- Profile pic, name and main infos about the user -->
  <div class="infos">
    <section>
      <div class="header">
        <?php 
          //reception of the form with the user id; If no form has been sent, the user id is the session id
          $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
          if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
          }
          else{
            header("location: users.php");
          }
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/profile_picture/<?php echo $row['img']; ?>" alt="">
        <div class="details">
          <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
          <p><?php echo $row['status']; ?></p>
        </div>
      </div>

      <div class="contact">
        <div class="contact-info">
          <div class="contact-info-item">
            <span>Email</span>
            <p><?php echo $row['email']; ?></p>
          </div>
          <br>
          <div class="contact-info-item">
            <span>Swapper Since</span>
            <p><?php echo $row['creation_date']; ?></p>
          </div>
          <br>
          <div class="contact-info-item">
            <span>Buy rating :<?php echo $row['buy_rating']; ?>
            </span>
          </div>
          <br>
          <div class="contact-info-item">
            <span>Sell rating :<?php echo $row['sell_rating']; ?>
            </span>
          </div>
          <br>
        </div>
        <div class="action-button">
          <?php
          //if the user is not the session user, he can send a message to the user
          if ($user_id != $_SESSION['unique_id']) {
            ?>
            <a href="chat.php?user_id=<?php echo $row['unique_id'] ?>">Send a message</a>
            <?php
          }
          ?>
        </div>
      </div>
  
    </section>
    <!-- this section shows every post of this particular user -->
    <section>
      posts from the user :
      <div class="post-list">
      <?php
// session_start();
// include_once "config.php";
$user_id = $_GET['user_id'];
$choix_table = "SELECT * from post where user_id = $user_id";
$ensemble_post = mysqli_query($conn,$choix_table);
$contenu = mysqli_fetch_all($ensemble_post, MYSQLI_BOTH);
$taille_contenue = mysqli_num_rows($ensemble_post);
$i = 0;
while ($i < $taille_contenue) {
    $tempo_photo = $contenu[$i]["photo"];
    ?>
    <div class="produit">  
        <h3>
            <?php echo $contenu[$i]["post_type"],"</br>"; ?>
            <img class="image_produit" src="php/images/posts/<?php echo $tempo_photo ?>" alt="image indisponible..."/>
        </h3>
            <?php echo $contenu[$i]["model_name"],"</br> Prix : ",$contenu[$i]["price"],"€</br>Déjà portée : ",
            $contenu[$i]["used"],"</br> prix extreme : ",$contenu[$i]["extreme_price"]; ?>
        <form method="post">
                <input type="submit" name=<?php echo $contenu[$i]["post_id"]?> id=<?php echo $contenu[$i]["post_id"]?> value="supprimer l'annonce">
        </form>
        <?php
        // delete the post from the database where for every post_id there is a button with the same name
        if (isset($_POST[$contenu[$i]["post_id"]])) {
            $post_id = $contenu[$i]["post_id"];
            $sql = "DELETE FROM post WHERE post_id = $post_id";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo "post deleted";
                header("location: profile.php?user_id=$user_id");
            } else {
                echo "error";
            }
        }
        ?>
    </div>
    <?php
    $i = $i + 1;
}
?>
      </div>
      
    </section>
    <!-- <script src="javascript/linkedpost.js"></script> -->
</body>