<?php

$protocol = ($_SERVER['REQUEST_SCHEME'] == 'http') ? 'http://' : 'https://';
$url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

$url = parse_url($url);



?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<!-- <link rel="icon" href="favicon.ico"> -->

	<link href="styles/css/bootstrap.min.css" rel="stylesheet">
	<link href="styles/css/main.css" rel="stylesheet">

	<script src="styles/js/jquery.min.js"></script>

	<title>АнтиПлагиат</title>
</head>

<body>