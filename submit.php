<?php

/**
 * Process moderator applications
 *
 * Temporary handling of mod apps until a more long term solution
 * can be developed that houses all applications.
 */

require_once('../../../credentials.php');
require_once('../../storymod/inc/lib.php');


error_reporting(E_ALL);
ini_set('display_errors', '1');

$out = NULL;

// did we get any form data?
if ($_POST) {

	// fetch form values
	$name = $_POST['name'];
	$email = $_POST['email'];
	$fb_profile = $_POST['fb-profile'];
	$comptime = $_POST['comptime'];
	$experience = $_POST['experience'];
	$otherskills = $_POST['otherskills'];
	$justification = $_POST['justification'];

	// merge availability
	if (isset($_POST['availability']) && is_array($_POST['availability'])) {
		$availability = implode(', ', $_POST['availability']);
	} 

	
	if (db_connect()) {

		// build query
		$query = "INSERT INTO `asmdss_apply`.`moderator_apps` (name, email, facebook_profile, availability, comptime, mil_exp, other_skills, justification)	VALUES (:name, :email, :profile, :availability, :comptime, :mil_exp, :other, :justification);";
		$stmt = $pdo->prepare($query);


		try {

			// associate placeholders with values
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

			$out .= "<p>Your application was submitted successfully</p>";

		} catch (PDOException $e) {
			$out .= "ERROR:" . $e->getMessage();
		}
	}

} else {
	$out .= "<p>No data was submitted.</p>";
	die;
}

echo $out;
die;

?>	