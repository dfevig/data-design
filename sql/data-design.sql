-- this is a comment in SQL (yes, the space is needed!!)
-- these statements will drop the tables and re-add them
-- this is akin to reformatting and reinstalling windows
-- never ever ever ever ever ever ever
-- do this on live data!!!
DROP TABLE IF EXISTS article;


-- the CREATE TABLE function is a function that takes tons of arguments to layout the table's schema
CREATE TABLE article (
	-- this creates the attribute for the primary key
	-- auto_increment tells mySQL to number them {1,2,3,....}
	-- not null means the attribute is required!
	articleId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	categoryType VARCHAR(250) NOT NULL,
	contents VARCHAR(250) NOT NULL,
	PRIMARY KEY (articleId)
);

