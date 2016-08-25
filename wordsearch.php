<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	
<!--Much of this code was excerpted from Andrew Harris' "PHP6/MySQL Programming for the Absolute Beginner.-->
<!--The purpose of this project was less to do with creating the program, as learning how to use git version control for myself.-->

	<title>Spelling Wordsearch Generator - matt-poston.com</title>
	
	<!-- stylesheet(s) -->

	<link rel="stylesheet" type="text/css" href="wordsearch.css">

</head>

<body>
   <?php
   
		if (!filter_has_var(INPUT_POST, "spellingWords")){
			print <<<MESSAGE
			
				<div>Please return to the <a href="wordsearch.html">previous page</a> to enter your list of spelling words.</div>
			
MESSAGE;
		}
		
		else {
			$puzzleData = array(
				"width" => filter_input(INPUT_POST, "cols"),
				"height" => filter_input(INPUT_POST, "rows"),
				"name" => filter_input(INPUT_POST, "puzzleName")
			);
			
			if (parseList() == TRUE){
				$legalBoard = FALSE;
				
				while ($legalBoard == FALSE){
					clearBoard();
					$legalBoard = fillBoard();
				}
				
				$key = $board;
				$keyPuzzle = makeBoard($key);
				
				addJunk();
				$puzzle = makeBoard($board);
				
				printPuzzle();
			}
		}
		
		function parseList(){
			
			global $word, $puzzleData, $spellingWords;
			
			$spellingWords = filter_input(INPUT_POST, "spellingWords");
			$itWorked = TRUE;
			
			$spellingWords = strtoupper($spellingWords);
			
			$word = split("\n", $spellingWords);
			
			foreach ($word as $currentWord) {
				$currentWord = rtrim($currentWord);
				
				if ((strlen($currentWord) > $puzzleData["width"]) &&
					 (strlen($currentWord) > $puzzleData["height"])){
						print "$currentWord is too long to fit into the puzzle. Please modify the row or column height, and try again.<br><br><a href='wordsearch.html'>Go back.</a>";
						$itWorked = FALSE;
				}
			}
			return $itWorked;
		}
		
		function clearBoard(){
			
			global $board, $puzzleData;
			
			for ($row = 0; $row < $puzzleData["height"]; $row++){
				for ($col = 0; $col < $puzzleData["width"]; $col++){
					$board[$row][$col] = ".";
				}
			}
		}
		
		function fillBoard(){
			
			global $word;
			$direction = array("N", "S", "E", "W");
			$itWorked = TRUE;
			$counter = 0;
			$keepGoing = TRUE;
			
			while($keepGoing){
				$dir = rand(1,2); //adjusted variable to only pull E or S options.
				$result = addWord($word[$counter], $direction[$dir]);
				if ($result == FALSE){
					$keepGoing = FALSE;
					$itWorked = FALSE;
				}
				$counter++;
				if ($counter >= count($word)){
					$keepGoing = FALSE;
				}
			}
			return $itWorked;
		}
		
		function addWord($theWord, $dir){
			
			global $board, $puzzleData;
			
			$theWord = rtrim($theWord);
			
			$itWorked = TRUE;
			
			switch($dir) {
				
				case "E":
					$newCol = rand(0, $puzzleData["width"] - 1 - strlen($theWord));
					$newRow = rand(0, $puzzleData["height"] - 1);
					
					for ($i = 0; $i < strlen($theWord); $i++){
						$boardLetter = $board[$newRow][$newCol + $i];
						$wordLetter = substr($theWord, $i, 1);
						
						if (($boardLetter == $wordLetter) || ($boardLetter == ".")) {
							
							$board[$newRow][$newCol + $i] = $wordLetter;
						
						} else {
						
							$itWorked = FALSE;
						}
					}
					break;
				
				//case "W":
				//	$newCol = rand(strlen($theWord), $puzzleData["width"] -1);
				//	$newRow = rand(0, $puzzleData["height"] -1);
				//	
				//	for ($i = 0; $i < strlen($theWord); $i++){
				//		$boardLetter = $board[$newRow][$newCol - $i];
				//		$wordLetter = substr($theWord, $i, 1);
				//		
				//		if (($boardLetter == $wordLetter) || ($boardLetter == ".")){
				//			
				//			$board[$newRow][$newCol - $i] = $wordLetter;
				//		
				//		} else {
				//			
				//			$itWorked = FALSE;
				//		}
				//	}
				//	break;
				
				case "S":
					$newCol = rand(0, $puzzleData["width"] -1);
					$newRow = rand(0, $puzzleData["height"] - 1 - strlen($theWord));
					
					for ($i = 0; $i < strlen($theWord); $i++){
						$boardLetter = $board[$newRow + $i][$newCol];
						$wordLetter = substr($theWord, $i, 1);
						
						if (($boardLetter == $wordLetter) || ($boardLetter == ".")){
							
							$board[$newRow + $i][$newCol] = $wordLetter;
							
						} else {
							
							$itWorked = FALSE;
						}
					}
					break;
				
				//case "N":
				//	$newCol = rand(0, $puzzleData["width"] -1);
				//	$newRow = rand(strlen($theWord), $puzzleData["height"] -1);
				//	
				//	for ($i=0; $i < strlen($theWord); $i++){
				//		$boardLetter = $board[$newRow - $i][$newCol];
				//		$wordLetter = substr($theWord, $i, 1);
				//		
				//		if (($boardLetter == $wordLetter) || ($boardLetter == ".")){
				//			
				//			$board[$newRow - $i][$newCol] = $wordLetter;
				//			
				//		} else {
				//			
				//			$itWorked = FALSE;
				//		}
				//	}
				//	break;
					
			}
			return $itWorked;
		}
		
		function makeBoard($theBoard){
			global $puzzleData;
			$puzzle = "";
			$puzzle .= "<table>\n";
			
			for ($row = 0; $row < $puzzleData["height"]; $row++){
				$puzzle .= "<tr>\n";
				
				for ($col = 0; $col < $puzzleData["width"]; $col++){
					$puzzle .= "  <td>{$theBoard[$row][$col]}</td>\n";
				}
				$puzzle .= "</tr>\n";
			}
			$puzzle .= "</table>\n";
			return $puzzle;
		}
		
		function addjunk(){
			global $board, $puzzleData;
			
			for ($row = 0; $row < $puzzleData["height"]; $row++){
				for ($col = 0; $col < $puzzleData["width"]; $col++){
					if ($board[$row][$col] == "."){
						$newLetter = rand(65, 90);
						$board[$row][$col] = chr($newLetter);
					}
				}
			}
		}
		
		function printPuzzle(){
			global $puzzle, $word, $keyPuzzle, $puzzleData;
			
			print <<<PUZZLE
		
				<h1>{$puzzleData["name"]}</h1>
				<div id="puzzle">$puzzle</div><br><br>
				<div id="list"><h3>Word List</h3>
				<ul>
			
PUZZLE;

			foreach ($word as $theWord){
				$theWord = rtrim($theWord);
				print "<li>$theWord</li>\n";
			}
			print "</ul></div>\n";
			$puzzleName = $puzzleData["name"];
			
			$_SESSION["key"] = $keyPuzzle;
			$_SESSION["puzzleName"] = $puzzleName;
			
			print <<<KEY
	
				<form action = "puzzleKey.php" method = "post" id = "keyForm">
					<div>
						<input type = "submit" value = "Show me the answers!">
					</div>
				</form>
			
KEY;
		}
		   
   ?>
    
  
</body>
</html>