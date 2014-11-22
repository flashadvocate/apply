<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

function db_connect() {

	global $pdo;
	try {
		$pdo = new PDO("mysql:host=localhost;dbname=asmdss_stories", DB_USER, DB_PASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch (PDOException $e) {
		echo "<div class='alert alert-danger'><i class='fa fa-exclamation-circle'></i><strong>Database connection error</strong>: " . $e->getMessage() . "</div>";
	}

	return true;
}




function doesAppExist($string) {
    global $pdo;
    
    if (db_connect()) {
        if (isset($string)) {
            $string = strtolower($string);
            
            try {
                $sth = $pdo->prepare('SELECT count(*) FROM `asmdss_apply`.`moderator_apps` WHERE email= :email LIMIT 1');
                $sth->bindParam(':email', $string);
                $sth->execute();
                $count = $sth->fetchColumn();
                
            }
            catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
            
            if ($count > 0) {
                return true;
            } else {
                return false;
            }
            
            
            if (!$count) {
                die('Could not get data (doesAppExist): ' . mysql_error());
            }
            
        } else {
            die('Cant connect to mysql (userexists)');
        }
    }
}


?>