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