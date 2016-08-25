<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	
<!--Much of this code was excerpted from Andrew Harris' "PHP6/MySQL Programming for the Absolute Beginner.-->
<!--The purpose of this project was less to do with creating the program, as learning how to use git version control for myself.-->

	<title>Spelling Wordsearch Key - matt-poston.com</title>
	
	<!-- stylesheet(s) -->

	<link rel="stylesheet" type="text/css" href="wordsearch.css">

</head>

<body>
   <?php
   
		$puzzleName = $_SESSION["puzzleName"];
		$key = $_SESSION["key"];
		
		print <<<KEY
		
			<h1>$puzzleName - KEY</h1>
			<div>$key</div>
		
KEY;
		
   ?>
<br><br>
<div><a href="wordsearch.html">Return to Generator.</a></div>
    
  
</body>
</html>