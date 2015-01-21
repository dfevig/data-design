-- this is a comment in SQL (yes, the space is needed!!)
-- these statements will drop the tables and re-add them
-- this is akin to reformatting and reinstalling windows
-- never ever ever ever ever ever ever
-- do this on live data!!!
DROP TABLE IF EXISTS favorite;
DROP TABLE IF EXISTS tweet;
DROP TABLE IF EXISTS profile;

-- the CREATE TABLE function is a function that takes tons of arguments to layout the table's schema
CREATE TABLE profile (
	-- this creates the attribute for the primary key
	-- auto_increment tells mySQL to number them {1,2,3,....}
	-- not null means the attribute is required!
	profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	email VARCHAR(128) NOT NULL,
	-- to make something optional, exclude the not null
	phone VARCHAR(32),
	atHandle VARCHAR(32) NOT NULL,
	-- this officiates the primary key for the entity
	PRIMARY KEY (profileId)
);

-- create tweet entity
CREATE TABLE tweet (
	-- this is for yet another primary key...
	tweetId INT UNSIGNED AUTO_INCREMENT NOT NULL,
		-- this is for a foreign key; auto_increment is omitted by design
	profileId INT UNSIGNED NOT NULL,
	tweetContent VARCHAR(160) NOT NULL,
	-- notice dates don't need a size parameter
	tweetDate DATETIME NOT NULL,
	-- this creates an index before making a foreign key
	INDEX(profileId),
	-- this creates the actual foreign key relation
	FOREIGN KEY (profileId) REFERENCES  profile(profileId),
	-- and finally create the primary key
	PRIMARY KEY(tweetId)
);

-- create the favorite entity (a weak entity from an m-to-n for profile --> tweet)
CREATE TABLE favorite (
	-- these are not auto_increment because they are foreign keys
	profileId INT UNSIGNED NOT NULL,
	tweetId INT UNSIGNED NOT NULL,
	favoriteDate DATETIME NOT NULL,
	-- index foreign keys
	INDEX (profileId),
	INDEX (tweetId),
	-- create the foreign key relations
	FOREIGN KEY (profileId) REFERENCES profile(profileId),
	FOREIGN KEY (tweetId) REFERENCES tweet(tweetId),
	-- finally, create a composite foreign key with the two foreign keys
	PRIMARY KEY (profileId, tweetId)
);

