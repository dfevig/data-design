<?php
/**
 * Class construction of the Article section of a Wikipedia page
 *
 * The construction of the Content the article table used for wikipedia.
 *
 * @author David Fevig <davidfevig@davidfevig.com>
 **/

class Article {
	/**
	 * id for the article; this is a Primary Key
	 **/
	private $articleId;
	/**
	 * category type for the wiki article
	 */
	private $categoryType;
	/**
	 * the text content of the entire article
	 **/
	private $textContent;
	/**
	 * the article title
	 **/
	private $articleTitle;

	/**
	 * constructor for Article
	 *
	 * @param int $newArticleId id of the article
	 * @param string $newArticleType string containing the article type
	 * @param string $newTextContent string containing the article text
	 * @param string $newArticleTitle string of the article title
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., incorrect strings, negative numbers)
	 *
	 **/

	public function __construct($newArticleId, $newCategoryType, $newTextContent, $newArticleTitle) {
		try {
			$this->setArticleId($newArticleId);
			$this->setCategoryType($newCategoryType);
			$this->setTextContent($newTextContent);
			$this->setArticleTitle($newArticleTitle);
		} catch(InvalidArgumentException $invalidArgument){
			throw(new InvalidArgumentException($invalidArgument->getMessage(),0,$invalidArgument));
		}	catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0 , $range));
		}
	}
	/**
	 * accessor method for Article
	 *
	 * @return int value for article id
	 */
	public function getArticleId(){
		return($this->articleId);
	}
	/**
	 * mutator method for article id
	 *
	 * @param int $newArticleId new value for article id
	 * @throws InvalidArgume  ntException if $newArticleId is not an integer
	 * @throws RangeException if $newArticleId is not positive
	 **/
	public function setArticleId($newArticleId) {
		// base case: if the article id is null, this reference without a mySQL assigned id (yet)
		if($newArticleId === null) {
			$this->articleId = null;
			return;
		}

		// verify the textContent id is valid
		$newArticleId = filter_var($newArticleId, FILTER_VALIDATE_INT);
		if($newArticleId === false) {
			throw(new InvalidArgumentException("article id is not an integer"));
		}

		// verify the article id is positive
		if($newArticleId <= 0) {
			throw(new RangeException("the article id is not a positive"));
		}
		// convert and store the article id
		$this->articleId = intval($newArticleId);
	}
	/**
	 * accessor method for category type
	 *
	 * @return string value for category type
	 **/
	public function getCategoryType() {
		return($this->categoryType);
	}

	/**
	 * mutator method for category type
	 *
	 * @param string $newCategoryType new value for author
	 * @throws InvalidArgumentException if $newCategoryType is not a string or insecure
	 **/
	public function setCategoryType($newCategoryType) {
		// verify the category type entered is secure
		$newCategoryType = trim($newCategoryType);
		$newCategoryType = filter_var($newCategoryType, FILTER_SANITIZE_STRING);
		if(empty($newCategoryType) === true) {
			throw(new InvalidArgumentException("category type is empty or insecure"));
		}
		//store the category type
		$this->categoryType = $newCategoryType;
	}
	/**
	 * accessor method for textContent
	 *
	 * @return string value for textContent
	 **/
	public function getTextContent() {
		return($this->textContent);
	}
	/**
	 * mutator method for textContent
	 *
	 * @param string $newTextContent new value for the textContent
	 * @throws InvalidArgumentException if $newtextContent is not a string or insecure
	 **/
	public function setTextContent($newTextContent) {
		$newTextContent = trim($newTextContent);
		$newTextContent = filter_var($newTextContent, FILTER_SANITIZE_STRING);
		if(empty($newTextContent) === true) {
			throw(new InvalidArgumentException("the text content is empty or insecure"));
		}
		//store the text content
		$this->textContent = $newTextContent;
	}
	/**
	 * accessor method for articleTitle
	 *
	 * @return string value articleTitle
	 **/
	public function getArticleTitle() {
		return($this->articleTitle);
	}
	/**
	 * mutator method for articleTitle
	 *
	 * @param string $newArticleTitle new value for the textContent
	 * @throws InvalidArgumentExceoption if $newArticleContent is not a string or insecure
	 **/
	public function setArticleTitle($newArticleTitle) {
		$newArticleTitle = trim($newArticleTitle);
		$newArticleTitle = filter_var($newArticleTitle,FILTER_SANITIZE_STRING);
		if(empty($newArticleTitle) === true) {
			throw(new InvalidArgumentException("the article title is empty or insecure"));
		}
		//store the text content
		$this->articleTitle = $newArticleTitle;
	}

	/**
	 * insert the article into mySQL
	 *
	 * @param resource #mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli){
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//enforce the articleId is null (i.e., don't insert a reference that already exists
		if($this->articleId !== null) {
			throw(new mysqli_sql_exception("not a new article"));
		}
		// create query template
		$query	= "INSERT INTO article(articleId, categoryType, textContent, articleTitle) VALUES (?,?,?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		//bind the member variables to the place holders in the template
		$wasClean	= $statement->bind_param("isss", $this->articleId, $this->categoryType, $this->textContent, $this->articleTitle);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}
		//update the null referenceId with what mySQL just gave us
		$this->articleId = $mysqli->insert_id;
		//clean up statement
		$statement->close();
	}
	/**
	 * deletes the article from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the articleId is not null (i.e., don't delete an article that hasn't been inserted)
		if($this->articleId === null) {
			throw(new mysqli_sql_exception("unable to delete an article that does not exist"));
		}

		// create query template
		$query	 = "DELETE FROM article WHERE articleId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->articleId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// clean up the statement
		$statement->close();
	}
	/**
	 * updates the Reference in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the referenceId is not null (i.e., don't update a reference that hasn't been inserted)
		if($this->articleId === null) {
			throw(new mysqli_sql_exception("unable to update a article that does not exist"));
		}
		// create query template
		$query	 = "UPDATE article SET categoryType = ?, textContent = ?, articleTitle = ? WHERE articleId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean	= $statement->bind_param("isss", $this->articleId, $this->categoryType, $this->textContent, $this->articleTitle);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// clean up the statement
		$statement->close();
	}
	/**
	 * gets the article by article id
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $articleId to search for
	 * @return mixed array of Articles found, Article found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getArticleByArticleId(&$mysqli, $articleId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$articleId = trim($articleId);
		$articleId = filter_var($articleId, FILTER_VALIDATE_INT);

		// create query template
		$query	 = "SELECT articleId, categoryType, textContent, articleTitle FROM article WHERE articleId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the categoryType content to the place holder in the template
		$wasClean = $statement->bind_param("i", $articleId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		// build an array of categoryType
		$articleIds = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$articleId	= new Article($row["articleId"], $row["categoryType"], $row["textContent"], $row["articleTitle"]);
				$articleIds[] = $articleId;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Article", 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfArticles = count($articleIds);
		if($numberOfArticles === 0) {
			return(null);
		} else if($numberOfArticles === 1) {
			return($articleIds[0]);
		} else {
			return($articleIds);
		}
	}
}

?>

