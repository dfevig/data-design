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
	private $journal;
		/**
	 * link type used for reference, this is a foreign key
	 */
	private $linkType;
	/**
	 * numerical page number
	 */
	private $page;

	/**
	 * constructor for Reference
	 * PLACE HOLDER FOR CONSTRUCTOR
	 */

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
	 */
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

}

?>