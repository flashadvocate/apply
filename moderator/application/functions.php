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

function getIP() 
{
    // populate a local variable to avoid extra function calls.
    // NOTE: use of getenv is not as common as use of $_SERVER.
    //       because of this use of $_SERVER is recommended, but 
    //       for consistency, I'll use getenv below
    $tmp = getenv("HTTP_CLIENT_IP");
    // you DON'T want the HTTP_CLIENT_ID to equal unknown. That said, I don't
    // believe it ever will (same for all below)
    if ( $tmp && !strcasecmp( $tmp, "unknown"))
        return $tmp;

    $tmp = getenv("HTTP_X_FORWARDED_FOR");
    if( $tmp && !strcasecmp( $tmp, "unknown"))
        return $tmp

    // no sense in testing SERVER after this. 
    // $_SERVER[ 'REMOTE_ADDR' ] == gentenv( 'REMOTE_ADDR' );
    $tmp = getenv("REMOTE_ADDR");
    if($tmp && !strcasecmp($tmp, "unknown"))
        return $tmp;

    return("unknown");
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