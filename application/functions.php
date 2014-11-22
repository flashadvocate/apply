<?php


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


function doesAppExist($email) {
    global $pdo;
    
    if (db_connect()) {
        try {
            $sth = $pdo->prepare('SELECT email FROM `asmdss_apply`.`moderator_apps` WHERE email = :email LIMIT 1');
            $sth->bindParam(':email', $email);
            $sth->execute();
            $user = $sth->fetch(PDO::FETCH_OBJ);
        }
        catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }
    
    if ($email === $user->email) {
        return true;
    } else {
        return false;
    }
    
}

?>