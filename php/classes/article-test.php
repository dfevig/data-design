<?php
//first, require the SimpleTest framework <http://simpletest.org/>
//this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once("article.php");

/**
 * Unit test for the Article Class
 *
 * This is a SimpleTest test case for the CRUD methods of the Article class.
 *
 * @see Article
 * @author David Fevig <davidfevig@davidfevig.com>
 **/

class ArticleTest extends UnitTestCase {
	/**
	 * sets up the mySQL connection for this test
	 **/
	private $mysqli = null;
	/**
	 * instance of the object we are going to test with
	 **/
	private $article = null;

	/**
	 * sets up the mySQL connection for this test
	 */
	public function setUp() {
		//first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "dfevig", "fymurieldrynoaalawpx", "dfevig");

		// second, create an instance of the object under scrutiny
		$this->article = new Article(null,"Science", "Paragraphs about Science", "In Sci");
	}

	/**
	 * tears down he connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->article !== null) {
			$this->article->delete($this->mysqli);
			$this->article = null;
		}

		//disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}
}

?>
