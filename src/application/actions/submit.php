<?php

/**
 * Process moderator applications
 *
 * Temporary handling of mod apps until a more long term solution
 * can be developed that houses all applications.
 */

// FETCH DEPENDENCIES
require_once('credentials.php');
require_once("../functions.php");

$out = array();

// DID WE GET ANY FORM DATA?
if ($_POST) {

	// FETCH FORM VALUES
	$name = $_POST['name'];
	$email = $_POST['email'];
	$fb_profile = $_POST['fb-profile'];
	$comptime = $_POST['comptime'];
	$experience = $_POST['experience'];
	$otherskills = $_POST['otherskills'];
	$justification = $_POST['justification'];

	// MERGE AVAILABILITY
	if (isset($_POST['availability']) && is_array($_POST['availability'])) {
		$availability = implode(', ', $_POST['availability']);
	} 

	// CREATE DB OBJECT
	if (db_connect()) {

		// DOES APP EXIST?
		if (!doesAppExist($email)) {

			// BUILD QUERY
			$query = "INSERT INTO `asmdss_apply`.`moderator_apps` (name, email, facebook_profile, availability, comptime, mil_exp, other_skills, justification, user_ip)	VALUES (:name, :email, :profile, :availability, :comptime, :mil_exp, :other, :justification, :ip);";
			$stmt = $pdo->prepare($query);

			try {

				// ASSOCIATE PLACEHOLDERS WITH VALUES
				$stmt->execute(
					array(
						':name' => $name,
						':email' => $email,
						':profile' => $fb_profile,
						':comptime' => $comptime,
						':mil_exp' => $experience,
						':other' => $otherskills,
						':justification' => $justification,
						':availability' => $availability,
						':ip' => getIP()
						)
					);

				// EMAIL APP CONTENTS
				$to = APP_EMAIL;
				$subject = "Mod app - " . $row['name'];

				$message = "

				<html>
				<body>
					<p>A moderator application has been received from " . $row['email'] . " on " . $row['date'] . ". The content is as follows:</p>
					<p>
						<strong>Name</strong>: " . $name . "<br />
						<strong>Email</strong>: " . $email . "<br />
						<strong>Facebook</strong>: " . $fb_profile . "<br /><br />
						<strong>Availability</strong>: " . $availability . "<br />
						<strong>Computer Time</strong>: " . $comptime . "<br />
						<strong>Mil. Experience</strong>: " . $experience . "<br /><br />
						<strong>Other Skills</strong>: " . $otherskills . "<br /><br />
						<strong>Justification</strong>: " . $justification . "
					</p>
				</body>
				</html>

				";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= APP_EMAIL . "\r\n";
				$headers .= 'From: ASMDSS Application System <devteam@asmdss.com>' . "\r\n"; 

				mail($to, $subject, $message, $headers);

				// FORM SUCCESSFUL
				$out = array('success' => true);

			// PDO ERROR!
			} catch (PDOException $e) {
				$out .= "ERROR:" . $e->getMessage();
			}


		// EXISTING APPLICATION (OR EMAIL USED ALREADY)
		} else {
			$out = array('success' => false, 'message' => 'It appears an application already exists with that email.');
		}
	}

// SUCCESS
} else {
	$out = array('success' => false, 'message' => 'No data was submitted!');
}

// DONE
echo json_encode($out);

?>	