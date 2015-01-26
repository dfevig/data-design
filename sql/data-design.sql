-- this is a comment in SQL (yes, the space is needed!!)
-- these statements will drop the tables and re-add them
-- this is akin to reformatting and reinstalling windows
-- never ever ever ever ever ever ever
-- do this on live data!!!
DROP TABLE IF EXISTS reference;

CREATE TABLE reference (
	referenceId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	articleId INT UNSIGNED NOT NULL,
	author VARCHAR(250) NOT NULL,
	journalName VARCHAR(250) NOT NULL,
	pageNum INT UNSIGNED NOT NULL,
	linkType VARCHAR(250) NOT NULL,
	FOREIGN KEY (articleId) REFERENCES article(articleId),
	PRIMARY KEY (referenceId)
);

