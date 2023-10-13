<?php
global $dbname, $conn;
session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);
        if(!empty($message)){
            if($conn){
                echo "Database connection to the base: $dbname ";
              }

            $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
            VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')";
            $insert_query = mysqli_query($conn, $sql);
            // catch the error if the query fails
            if (!$insert_query) {
                printf("Error: %s\n", mysqli_error($conn));
            }
        }
        else{
            echo "Voici le 3 {$message}";
        }
    }else{
        header("location: ../login.php");
    }
?>