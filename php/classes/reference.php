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
	 * @thorws RangeException if data values are out of bounds (e.g., incorrect strings, negative numbers)
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
		$this->pageNumv = intval($newPageNum);
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
		$newLinkType = filter_var(($newLinkType, FILTER_VALIDATE_URL));
		if(empty($newLinkType) === true){
			throw(new InvalidArgumentException("link type content empty or invalid"));
		}
	public function setLinkType($newLinkType) {
			//verify the link type URL is valid
			$newLinkType = trim($newLinkType);
			$newLinkType = filter_var(($newLinkType, FILTER_SANITIZE_URL));
		if($newLinkType === false){
			throw(new InvalidArgumentException("link type URL is invalid"));
		}
		//convert and store link type
		$this->linkType = intval($newLinkType);
	}

	}

?>