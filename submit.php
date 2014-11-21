<?php

/**
 * Process moderator applications
 *
 * Temporary handling of mod apps until a more long term solution
 * can be developed that houses all applications.
 */

require_once('../../../credentials.php');
require_once("functions.php");

$out = NULL;

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

	if (db_connect()) {

		if (!doesAppExist($email)) {

			// BUILD QUERY
			$query = "INSERT INTO `asmdss_apply`.`moderator_apps` (name, email, facebook_profile, availability, comptime, mil_exp, other_skills, justification)	VALUES (:name, :email, :profile, :availability, :comptime, :mil_exp, :other, :justification);";
			$stmt = $pdo->prepare($query);

			try {

				// ASSOCIATE PLACEHOLDERS WITH VALUES
				$stmt->execute(
					array(
						':name' => $name,
						':email' => $email,
						':profile' => $fb_profile,
						':comptime' => $name,
						':mil_exp' => $experience,
						':other' => $otherskills,
						':justification' => $justification,
						':availability' => $availability
						)
					);

				$out .= "
				<h1>Thanks!</h1>
				<p>Your application was submitted successfully, and will be reviewed by a member of the ASMDSS staff.</p>
				";

			// PDO ERROR!
			} catch (PDOException $e) {
				$out .= "ERROR:" . $e->getMessage();
				die;
			}
		}

	// EXISTING APPLICATION (OR EMAIL USED ALREADY)
	} else {
		$out .= "
		<h1>Oops!</h1>
		<p>It looks like an application has already been submitted using that email address.</p>
		<p>If you have already submitted an application, please be patient and we will get back to you as soon as possible.</p>
		";
	}

// SUCCESS
} else {
	$out .= "
	<h1>Oops!</h1>
	<p>No form data was submitted. Please <a href='http://apply.asmdss.com/moderator/'>complete the form</a> and try again. </p>
	";
	die;
}

echo $out;
die;

?>	