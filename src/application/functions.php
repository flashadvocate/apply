<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

function define_pages() {

    // build page rules for routing system
    $rules = array(
        'moderator' => "/moderator",
        'home' => "/"
        );
    
    return $rules;
}

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

function getIP() {

    if ( function_exists( 'apache_request_headers' ) ) {
        $headers = apache_request_headers();
    } else {
        $headers = $_SERVER;
    }

        //Get the forwarded IP if it exists
    if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
        $the_ip = $headers['X-Forwarded-For'];
    } elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
        $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
    } else {
        $the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
    }
    return $the_ip;
}

function doParseAndEmail() {

    /**
     * parses applications and emails them
     * WARNING: sends ALL apps
     */
    
    global $pdo;

    if (db_connect()) {

        try {

            $stmt = $pdo->prepare("SELECT name, email, facebook_profile, availability, comptime, mil_exp, other_skills, justification, user_ip, date FROM `asmdss_apply`.`moderator_apps`");
            $stmt->execute();
            $result = $stmt->fetchAll();

            foreach($result as $row) {

                    // email content
                $to = APP_EMAIL;
                $subject = "Mod app - " . $row['name'];
                $message = "

                <html>
                <body>
                    <p>A moderator application has been received from " . $row['email'] . " on " . $row['date'] . ". The content is as follows:</p>
                    <p>
                        <strong>Name</strong>: " . $row['name'] . "<br />
                        <strong>Email</strong>: " . $row['email'] . "<br />
                        <strong>Facebook</strong>: " . $row['facebook_profile'] . "<br /><br />
                        <strong>Availability</strong>: " . $row['availability'] . "<br />
                        <strong>Computer Time</strong>: " . $row['comptime'] . "<br />
                        <strong>Mil. Experience</strong>: " . $row['mil_exp'] . "<br /><br />
                        <strong>Other Skills</strong>: " . $row['other_skills'] . "<br /><br />
                        <strong>Justification</strong>: " . $row['justification'] . "
                    </p>
                </body>
                </html>";

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= APP_EMAIL . "\r\n";
                $headers .= 'From: ASMDSS Application System <webteam@asmdss.com>' . "\r\n"; 

                mail($to, $subject, $message, $headers);
                echo "Sent an email<br />";
                sleep(2);
            }

        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage();
            die;
        }
    }

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