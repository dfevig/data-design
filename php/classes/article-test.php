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
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 *instance of the object we are testing
	 */
	private $article = null;
	// this section contains member variables with constants needed for creating a new article
	/**
	 * content of the category type
	 */
	private $categoryType = "Science";
	/**
	 * content of the article title
	 */
	private $articleTitle = "The science of things";
	/**
	 * the text content of the article
	 */
	private $textContent = "Paragraphs";

	/**
	 * sets up the mySQL connection for this test
	 */
	public function setUp() {
		//first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "dfevig", "fymurieldrynoaalawpx", "dfevig");

		// second, create an instance of the object under scrutiny
		$this->article = new Article(null, $this->categoryType, $this->articleTitle, $this->textContent);
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
	/**
	 * test inserting a valid article into mySQL
	 */
	public function testInsertValidArticle() {
		//zeroth, ensure the Article and mySQL class are sane
		$this->assertNotNull($this->article);
		$this->assertNotNull($this->mysqli);

		//first, insert the Article into mySQL
		$this->article->insert($this->mysqli);

		//second, grab an article from mySQL
		$mysqlArticle = Article::getArticleByArticleId($this->mysqli, $this->article->getArticleId());

		//third, assert the Article created and mySQL's Article are the same object
		$this->assertIdentical($this->article->getArticleId(), $mysqlArticle->getArticleId());
		$this->assertIdentical($this->article->getCategoryType(), $mysqlArticle->getCategoryType());
		$this->assertIdentical($this->article->getArticleTitle(), $mysqlArticle->getArticleTitle());
		$this->assertIdentical($this->article->getTextContent(), $mysqlArticle->getTextContent());
	}
	/**
	 * test inserting an invalid Article into mySQL
	 */
	public function testInsertInvalidArticle() {
		//zeroth, ensure the Article and mySQL class are the sane
		$this->assertNotNull($this->article);
		$this->assertNotNull($this->mysqli);

		//first, set the article id to an invented value that should never insert in the first place
		$this->article->setArticleId(50);

		//second, try to insert the Article and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->article->insert($this->mysqli);

		//third, set the Article to null to prevent the tearDown() from deleting an Article that never existed
		$this->article = null;
	}
	/**
	 * test deleting an Article from mySQL
	 */
	public function testDeleteValidArticle() {
		//zeroth, ensure the Article and mySQL class are the sane
		$this->assertNotNull($this->article);
		$this->assertNotNull($this->mysqli);

		//first, assert the Article is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->article->insert($this->mysqli);
		$mysqlArticle = Article::getArticleByArticleId($this->mysqli, $this->article->getArticleId());
		$this->assertIdentical($this->article->getArticleId(), $mysqlArticle->getArticleId());

		//second, delete the Article from mySQL and re-grab it from mySQL and assert id does not exist
		$this->article->delete($this->mysqli);
		$mysqlArticle= Article::getArticleByArticleId($this->mysqli, $this->article->getArticleId());
		$this->assertNull($mysqlArticle);

		//third, set the Article to null to prevent tearDown() from deleting a Article that has already been deleted
		$this->article = null;
	}
	/**
	 * test deleting an Article from mySQL that does not exist
	 */
	public function testDeleteInvalidArticle() {
		//zeroth, ensure the Article and mySQL class are the sane
		$this->assertNotNull($this->article);
		$this->assertNotNull($this->mysqli);

		//first, try to delete the Article before inserting it and ensure the exception is thrown
		$this->expectException('mysqli_sql_exception');
		$this->article->delete($this->mysqli);

		//second, set the Article to null to prevent tearDown() from deleting an Article that has already been deleted
		$this->article = null;
	}
	/**
	 * test updating an Article from mySQL
	 */
	public function testUpdateValidArticle() {
		//zeroth, ensure the Article and mySQL class are the sane
		$this->assertNotNull($this->article);
		$this->assertNotNull($this->mysqli);

		//first, assert the Article is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->article->insert($this->mysqli);
		$mysqlArticle = Article::getArticleByArticleId($this->mysqli, $this->article->getArticleId());
		$this->assertIdentical($this->article->getArticleId(), $mysqlArticle->getArticleId());

		//third, re-grab the Article from mySQL
		$mysqlArticle = Article::getArticleByArticleId($this->mysqli, $this->article->getArticleId());
		$this->assertNotNull($mysqlArticle);

		//forth, assert the Article is updated and mySQL's Article are the same object
		$this->assertIdentical($this->article->getArticleId(), $mysqlArticle->getArticleId());
		$this->assertIdentical($this->article->getCategoryType(), $mysqlArticle->getCategoryType());
		$this->assertIdentical($this->article->getArticleTitle(), $mysqlArticle->getArticleTitle());
		$this->assertIdentical($this->article->getTextContent(), $mysqlArticle->getTextContent());
	}

	/**
	 * test updating an Article from mySQL that does not exist
	 */
	public function testUpdateInvalidArticle() {
		//zeroth, ensure the Article and mySQL class are the sane
		$this->assertNotNull($this->article);
		$this->assertNotNull($this->mysqli);

		//first, try to update the Article before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->article->update($this->mysqli);

		//second, set the Article to null to prevent tearDown() from deleting an Article that has already been delted
		$this->article = null;
	}
}

?>
