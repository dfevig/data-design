<?php
/**
 * Class construction of the Reference section of a Wikipedia page
 *
 * A small example of the construction of the contents the reference table used in many wikipeidia articles.
 *
 * @author David Fevig <davidfevig@davidfevig.com>
 **/

class Reference {
	/**
	 * id for the reference; this is a Primary Key
	 **/
	private $referenceId;
	/**
	 * author name, this is a foreign key
	 */
	private $author;
	/**
	 * journal/article name, this is a foreign key
	 */
	private $journalName;
		/**
	 * link type used for reference, this is a foreign key
	 */
	private $linkType;
	/**
	 * numerical page number
	 */
	private $pageNum;

	/**
	 * constructor for Reference
	 *
	 * @param int $newReferenceId id of the reference
	 * @param string $newAuthor string containing the author name
	 * @param string $newJournalName string containing the journal name
	 * @param int $newPageNum int of the page number
	 * @param string $newLinkType string of the link type associated
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., incorrect strings, negative numbers)
	 **/
	public function __construct($newReferenceId, $newAuthor, $newJournalName, $newPageNum, $newLinkType) {
		try {
			$this->setReferenceId($newReferenceId);
			$this->setAuthor($newAuthor);
			$this->setJournalName($newJournalName);
			$this->setPageNum($newPageNum);
			$this->setLinkType($newLinkType);
		} catch(InvalidArgumentException $invalidArgument){
		throw(new InvalidArgumentException($invalidArgument->getMessage(),0,$invalidArgument));
	}	catch(RangeException $range) {
		throw(new RangeException($range->getMessage(), 0 , $range));
	}
	}


	/**
	 * accessor method to reference id
	 *
	 * @return int value of reference id
	 */
	public function getReferenceId(){
		return($this->referenceId);
	}
	/**
	 * mutator method for reference id
	 *
	 * @param int $newReferenceId new value of reference id
	 * @throws InvalidArgumentException if $newReferenceId is not and integer
	 * @throws RangeException if $newReferenceId is not positive
	 **/
	public function setReferenceId($newReferenceId) {
		// base case: if the reference id is null, this reference without a mySQL assigned id (yet)
		if($newReferenceId === null) {
			$this->referenceId = null;
			return;
		}

		// verify the reference id is valid
		$newReferenceId = filter_var($newReferenceId, FILTER_VALIDATE_INT);
		if($newReferenceId === false) {
			throw(new InvalidArgumentException("reference id is not an integer"));
		}

		// verify the profile id is positive
		if($newReferenceId <= 0) {
			throw(new RangeException("the reference id is not a positive"));
		}
		// convert and store the reference id
		$this->referenceId = intval($newReferenceId);
	}
	/**
	 * accessor method for author
	 *
	 * @return string value for author
	 **/
	public function getAuthor() {
		return($this->author);
	}

	/**
	 * mutator method for author
	 *
	 * @param string $newAuthor new value for author
	 * @throws InvalidArgumentException if $newAuthor is not a string or insecure
	 **/
	public function setAuthor($newAuthor) {
		// verify the author entered is secure
		$newAuthor = trim($newAuthor);
		$newAuthor = filter_var($newAuthor, FILTER_SANITIZE_STRING);
		if(empty($newAuthor) === true) {
			throw(new InvalidArgumentException("Author name is empty or insecure"));
		}
		//store the tweet content
		$this->author = $newAuthor;
	}
	/**
	 * accessor method for journal name
	 *
	 * @return string value for journal name
	 **/
	public function getJournalName() {
		return($this->journalName);
	}
	/**
	 * mutator method for journal name
	 *
	 * @param string $newJournalName new value for the journal name
	 * @throws InvalidArgumentException if $newJournalName is not a string or insecure
	 **/
	public function setJournalName($newJournalName) {
		$newJournalName = trim($newJournalName);
		$newJournalName = filter_var($newJournalName, FILTER_SANITIZE_STRING);
		if(empty($newJournalName) === true) {
			throw(new InvalidArgumentException("Journal name is empty or insecure"));
		}
		//store the journal name
		$this->journalName = $newJournalName;
	}
	/**
	 * accessor method for the page number
	 *
	 * @return int value for page number
	 **/
	public function getPageNum(){
		return($this->pageNum);
	}
	/**
	 * mutator method for the page number
	 *
	 * @param int $newPageNum new value of page number
	 * @throws InvalidArgumentException if $newPageNum is not and integer
	 * @throws RangeException if $newPageNum is not positive
	 **/
	public function setPageNum($newPageNum) {
		//verify the page number is valid
		$newPageNum = filter_var($newPageNum, FILTER_VALIDATE_INT);
		if($newPageNum === false) {
				throw(new InvalidArgumentException("Page number is not a valid integer"));
		}
		//verify page number is positive
		if($newPageNum <= 0) {
			throw(new RangeException("the page number is not a positive"));
		}
		//convert and store the page number
		$this->pageNum = intval($newPageNum);
	}
	/**
	 * accessor method for the link type
	 *
	 * @return string value of reference id
	 */
	public function linkType(){
		return($this->linkType);
	}
	/**
	 * mutator method for link type
	 *
	 * @param string $newLinkType new value of link type
	 * @throws InvalidArgumentException if $newLinkType is not a string
	 * @throws InvalidArgumentException if$newLinkType is not URL
	 **/
	public function setLinkType($newLinkType) {
		//verify the link type is valid
		$newLinkType = trim($newLinkType);
		$newLinkType = filter_var($newLinkType, FILTER_VALIDATE_URL);
		if(empty($newLinkType) === true){
			throw(new InvalidArgumentException("link type content empty or invalid"));
		}
		//convert and store link type
		$this->linkType = intval($newLinkType);
	}

	/**
	 * insert this reference into mySQL
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
		if($this->referenceId !== null) {
			throw(new mysqli_sql_exception("not a new reference"));
		}
		// create query template
		$query	= "INSERT INTO reference(referenceId, author, journalName, linkType, pageNum) VALUES (?,?,?,?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		//bind the member variables to the place holders in the template
		$wasClean	= $statement->bind_param("isssi", $this->referenceId, $this->author, $this->journalName, $this->linkType, $this->pageNum);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}
		//update the null referenceId with what mySQL just gave us
		$this->referenceId = $mysqli->insert_id;
		//clean up statement
		$statement->close();
	}
	/**
	 * deletes this Reference from mySQL
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
		if($this->referenceId === null) {
			throw(new mysqli_sql_exception("unable to delete a reference that does not exist"));
		}

		// create query template
		$query	 = "DELETE FROM reference WHERE referenceId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->referenceId);
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
		if($this->referenceId === null) {
			throw(new mysqli_sql_exception("unable to update a reference that does not exist"));
		}
		// create query template
		$query	 = "UPDATE reference SET author = ?, journalName = ?, linkType = ?, pageNum = ? WHERE referenceId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean	= $statement->bind_param("isssi", $this->referenceId, $this->author, $this->journalName, $this->linkType, $this->pageNum);
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
	 * gets the Reference by content
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $author name  to search for
	 * @return mixed array of References found, Reference found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getReferenceByAuthor(&$mysqli, $author) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$author = trim($author);
		$author = filter_var($author, FILTER_SANITIZE_STRING);

		// create query template
		$query	 = "SELECT referenceId, author, journalName, linkType, pageNum FROM reference WHERE author LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the tweet content to the place holder in the template
		$author = "%$author%";
		$wasClean = $statement->bind_param("s", $author);
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
		$authors = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$author	= new Reference($row["referenceId"], $row["author"], $row["journalName"], $row["linkType"], $row["pageNum"]);
				$authors[] = $author;
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
		$numberOfAuthors = count($authors);
		if($numberOfAuthors === 0) {
			return(null);
		} else if($numberOfAuthors === 1) {
			return($authors[0]);
		} else {
			return($authors);
		}
	}
}

?>