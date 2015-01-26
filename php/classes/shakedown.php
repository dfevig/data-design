<?php
//first require your class
require_once("article.php");

$article = new Article(null, "science","Paragraphs", "Stuff");

// connect to mySQL and populate the database
// yes, this is bad - but we'll isolate the parameters later.
try {
	//tell mysqli to throw exceptions
	mysqli_report(MYSQLI_REPORT_STRICT);

	// now go ahead and connect
	$mysqli = new mysqli('localhost', 'dfevig', 'fymurieldrynoaalawpx', 'dfevig');

	// now, insert into mySQL
	$article->insert($mysqli);

	//finally, disconnet form mySQL
	$mysqli->close();

	//var_dump the result to affirm we got a real primary key
	var_dump($article);

} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . "<br />";
	echo $exception->getFile() . ":" . $exception->getLine();
}

?>