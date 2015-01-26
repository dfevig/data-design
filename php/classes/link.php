<?php
/**
 * Class construction of the link collection of a Wikipedia page
 *
 * A small example of the construction of the links used in many wikipeidia articles.
 *
 * @author David Fevig <davidfevig@davidfevig.com>
 **/

class Link {
	/**
	 * id for the link; this is a Primary Key
	 **/
	private $linkId;
	/**
	 * id for the article; this is a foreign key
	 **/
	private $articleId;
	/**
	 * link URL
	 */
	private $linkUrl;
	/**
	 * link description
	 */
	private $linkDescription;

	/**
	 * constructor for Link
	 *
	 * @param int $newArticleId id of the article
	 * @param string $newArticleType string containing the article type
	 * @param string $newTextContent string containing the article text
	 * @param string $newArticleTitle string of the article title
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., incorrect strings, negative numbers)
	 *
	 **/
	public function __construct($newLinkId, $newArticleId, $newLinkUrl, $newLinkDescription) {
		try {
			$this->setLinkId($newLinkId);
			$this->setArticleId($newArticleId);
			$this->setLinkUrl($newLinkUrl);
			$this->setLinkDescription($newLinkDescription);
		}	catch(InvalidArgumentException $invalidArgument){
			throw(new InvalidArgumentException($invalidArgument->getMessage(),0,$invalidArgument));
		}	catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0 , $range));
		}
	}


	/**
	 * accessor method to link id
	 *
	 * @return int value of link id
	 */
	public function getLinkId(){
		return($this->linkId);
	}
	/**
	 * mutator method for link id
	 *
	 * @param int $newReferenceId new value of link id
	 * @throws InvalidArgumentException if $newLinkId is not an integer
	 * @throws RangeException if $newReferenceId is not positive
	 **/
	public function setLinkId($newLinkId) {
		// base case: if the reference id is null, this reference without a mySQL assigned id (yet)
		if($newLinkId === null) {
			$this->linkId = null;
			return;
		}

		// verify the link id is valid
		$newLinkId = filter_var($newLinkId, FILTER_VALIDATE_INT);
		if($newLinkId === false) {
			throw(new InvalidArgumentException("link id is not an integer"));
		}

		// verify the link id is positive
		if($newLinkId <= 0) {
			throw(new RangeException("the link id is not a positive"));
		}
		// convert and store the link id
		$this->linkId = intval($newLinkId);
	}
	/**
	 * accessor method for article id
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
	 * accessor method for link URL
	 *
	 * @return valid URL link
	 **/
	public function getlinkUrl() {
		return($this->linkUrl);
	}

	/**
	 * mutator method for the link URL
	 *
	 * @param valid url for $newLinkUrl for new url
	 * @throws InvalidArgumentException if $newlinkUrl is not a valid or insecure
	 **/
	public function setLinkUrl($newLinkUrl) {
		// verify the author entered is secure
		$newLinkUrl = trim($newLinkUrl);
		$newLinkUrl = filter_var($newLinkUrl, FILTER_VALIDATE_URL);
		if(empty($newLinkUrl) === true) {
			throw(new InvalidArgumentException("link url is invalid or insecure"));
		}
		//store the link url
		$this->linkUrl = $newLinkUrl;
	}
	/**
	 * accessor method for link description
	 *
	 * @return string value for link Url
	 **/
	public function getLinkDescription() {
		return($this->linkDesciption);
	}
	/**
	 * mutator method for link description
	 *
	 * @param string $newLinkDescription new value for the link description
	 * @throws InvalidArgumentException if $newLinkDesciption is not a string or insecure
	 **/
	public function setLinkDescription($newLinkDescription) {
		$newLinkDescription = trim($newLinkDescription);
		$newLinkDescription = filter_var($newLinkDescription, FILTER_SANITIZE_STRING);
		if(empty($newLinkDescription) === true) {
			throw(new InvalidArgumentException("link description is empty or insecure"));
		}
		//store the link description
		$this->linkDesciption = $newLinkDescription;
	}

	/**
	 * insert this link into mySQL
	 *
	 * @param resource #mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli){
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//enforce the referenceId is null (i.e., don't insert a link that already exsits)
		if($this->linkId !== null) {
			throw(new mysqli_sql_exception("not a new link"));
		}
		// create query template
		$query	= "INSERT INTO link(linkId, articleId, linkUrl, linkDescription) VALUES (?,?,?,?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		//bind the member variables to the place holders in the template
		$wasClean	= $statement->bind_param("iiss", $this->linkId, $this->ariclteId, $this->linkUrl, $this->linkDesciption);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}
		//update the null linkId with what mySQL just gave us
		$this->linkId = $mysqli->insert_id;
		//clean up statement
		$statement->close();
	}
	/**
	 * deletes this Link from mySQL
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
		if($this->linkId === null) {
			throw(new mysqli_sql_exception("unable to delete the link that does not exist"));
		}

		// create query template
		$query	 = "DELETE FROM link WHERE linkId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->linkId);
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
	 * updates the linkin mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the linkId is not null (i.e., don't update a reference that hasn't been inserted)
		if($this->linkId === null) {
			throw(new mysqli_sql_exception("unable to update a reference that does not exist"));
		}
		// create query template
		$query	 = "UPDATE link SET articleId = ?, linkUrl = ?, linkDescription = ? WHERE linkId";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean	= $statement->bind_param("iiss", $this->ariclteId, $this->linkUrl, $this->linkDesciption, $this->linkId);
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
	 * gets the link by content
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $linkUrl name  to search for
	 * @return mixed array of Links found, Link found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getLinkByLinkUrl(&$mysqli, $linkUrl) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$linkUrl = trim($linkUrl);
		$linkUrl = filter_var($linkUrl, FILTER_SANITIZE_URL);

		// create query template
		$query	 = "SELECT linkId, articleId, linkUrl, linkDescription FROM link WHERE linkUrl LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the link url to the place holder in the template
		$linkUrl = "%linkUrl%";
		$wasClean = $statement->bind_param("s", $linkUrl);
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

		// build an array of tweet
		$linkUrls = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$linkUrl	= new Link($row["linkId"], $row["articleId"], $row["linkUrl"], $row["linkDescription"]);
				$linkUrls[] = $linkUrl;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Tweet", 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfLinkUrls = count($linkUrl);
		if($numberOfLinkUrls === 0) {
			return(null);
		} else if($numberOfLinkUrls === 1) {
			return($linkUrls[0]);
		} else {
			return($linkUrls);
		}
	}
}

?>