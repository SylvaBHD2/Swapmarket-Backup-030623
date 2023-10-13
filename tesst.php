Hello world
<?php
$host_name = 'db5013222910.hosting-data.io';
$database = 'dbs11093044';
$user_name = 'dbu1740503';
$password = '6^Hs43Fpm9xB5%';

$link = new mysqli($host_name, $user_name, $password, $database);

if ($link->connect_error) {
  die('<p>La connexion au serveur MySQL a échoué: '. $link->connect_error .'</p>');
} else {
  echo '<p>Connexion au serveur MySQL établie avec succès.</p>';
}
echo 'HeLlo world 2' ?>