<?php
global $conn;
session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<html lang="fr">
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SwapMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="/css/style_for_chat.css">
</head>


<body>
<?php include_once "includes/header.php"; ?>
<?php include 'includes/menu_navigation.php'; ?>
<div class="content">

  <div class="wrapper">
    <section class="users">
          <div class = header>
              <div class="content">
                  <?php
                  $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                  if(mysqli_num_rows($sql) > 0){
                      $row = mysqli_fetch_assoc($sql);
                  }
                  ?>
                  <img src="php/images/profile_picture/<?php echo $row['img']; ?>" alt="">
                  <div class="details">
                      <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
                      <p><?php echo $row['status']; ?></p>
                  </div>
              </div>
          </div>
          <div class="search">
              <span class="text">Select an user to start chat</span>
              <input type="text" placeholder="Enter name to search...">
              <button><i class="fas fa-search"></i></button>
          </div>
          <div class="users-list">

          </div>
      </section>
    <section class="chat-area">
      <div class="header">
        <?php
          // $conn = mysqli_connect($hostname, $username, $password, $dbname);
          $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
          if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
          }else{
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
      <div class="chat-box">

      </div>
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  </div>
</div>

  <script src="javascript/chat.js"></script>
<script src="javascript/users.js"></script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
