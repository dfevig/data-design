<?php
/**
 * Class construction of the Article section of a Wikipedia page
 *
 * A small example of the construction of the contents the article table used for wikipedia.
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
	 */
	private $contents;

	/**
	 * constructor for Article
	 *
	 * making a place holder to be filled later
	 **/
	public function __construct($newArticleId, $newCategoryType, $newContents) {
		try {
			$this->setArtilceId($newArticleId);
			$this->setCategoryType($newCategoryType);
			$this->setContents($newContents);
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
	 * @throws InvalidArgumentException if $newArticleId is not an integer
	 * @throws RangeException if $newArticleId is not positive
	 **/
	public function setArticleId($newArticleId) {
		// base case: if the article id is null, this reference without a mySQL assigned id (yet)
		if($newArticleId === null) {
			$this->articleId = null;
			return;
		}

		// verify the contents id is valid
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
		//store the tweet content
		$this->author = $newCategoryType;
	}
	/**
	 * accessor method for contents
	 *
	 * @return string value for contents
	 **/
	public function getContents() {
		return($this->contents);
	}
	/**
	 * mutator method for contents
	 *
	 * @param string $newContents new value for the contents
	 * @throws InvalidArgumentException if $newContents is not a string or insecure
	 **/
	public function setContents($newContents) {
		$newContents = trim($newContents);
		$newContents = filter_var($newContents, FILTER_SANITIZE_STRING);
		if(empty($newContents) === true) {
			throw(new InvalidArgumentException("the contents is empty or insecure"));
		}
		//store the contents
		$this->contents = $newContents;
	}

	/**
	 * insert the contents into mySQL
	 *
	 * @param resource #mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli){
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//enforce the referenceId is null (i.e., don't insert a reference that already exists
		if($this->articleId !== null) {
			throw(new mysqli_sql_exception("not a new article"));
		}
		// create query template
		$query	= "INSERT INTO reference(articleId, categoryType, contents) VALUES (?,?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		//bind the member variables to the place holders in the template
		$wasClean	= $statement->bind_param("iss", $this->articleId, $this->categoryType, $this->contents);
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

		// enforce the referenceId is not null (i.e., don't delete a reference that hasn't been inserted)
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
		$query	 = "UPDATE article SET categoryType = ?, contents = ? WHERE articleId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean	= $statement->bind_param("iss", $this->articleId, $this->categoryType, $this->contents);
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
	 * gets the article by content
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $contentType to search for
	 * @return mixed array of Articles found, Article found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getArticleByCategoryType(&$mysqli, $categoryType) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$categoryType = trim($categoryType);
		$categoryType = filter_var($categoryType, FILTER_SANITIZE_STRING);

		// create query template
		$query	 = "SELECT articleId, contents FROM article WHERE categoryType LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the categoryType content to the place holder in the template
		$categoryType = "%categoryType%";
		$wasClean = $statement->bind_param("s", $categoryType);
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
		$categoryTypes = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$categoryType	= new Article($row["articleId"], $row["categoryType"], $row["contents"]);
				$categoryTypes[] = $categoryType;
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
		$numberOfArticles = count($categoryTypes);
		if($numberOfArticles === 0) {
			return(null);
		} else if($numberOfArticles === 1) {
			return($categoryTypes[0]);
		} else {
			return($categoryTypes);
		}
	}
}

?>

