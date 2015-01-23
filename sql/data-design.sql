-- this is a comment in SQL (yes, the space is needed!!)
-- these statements will drop the tables and re-add them
-- this is akin to reformatting and reinstalling windows
-- never ever ever ever ever ever ever
-- do this on live data!!!
DROP TABLE IF EXISTS reference;


-- the CREATE TABLE function is a function that takes tons of arguments to layout the table's schema
CREATE TABLE reference (
	-- this creates the attribute for the primary key
	-- auto_increment tells mySQL to number them {1,2,3,....}
	-- not null means the attribute is required!
	referenceId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	author VARCHAR(128) NOT NULL,
	-- to make something optional, exclude the not null
	journalName VARCHAR(128) NOT NULL,
	pageNum INT NOT NULL,
	linkType VARCHAR(128) NOT NULL,
	-- this officiates the primary key for the entity
	PRIMARY KEY (referenceId)
);

