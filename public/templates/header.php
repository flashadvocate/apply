<?php

if (isset($_GET['parse_apps_and_email'])) {
	if (doParseAndEmail()) {
		echo "Applications successfully emailed";
	} else {
		echo "Applications failed to parse";
	}
	die;
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"> -->
	<link rel="stylesheet" href="public/css/bootstrap.css">
	<link rel="stylesheet" href="public/css/shadows.css">
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css"> -->
	<link rel="icon" type="image/png" href="public/images/favicon.ico">
	<script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="public/scripts/submit.js"></script>
	<title>ASMDSS: Staff Applications</title>
</head>

<body>
	<div class="container">