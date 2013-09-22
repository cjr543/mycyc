CREATE TABLE news(
	id int(11) NOT NULL AUTO_INCREMENT,
	title varchar(128) NOT NULL,
	slug varchar(128) NOT NULL,
	text text NOT NULL,
	PRIMARY KEY (id),
	KEY slug (slug)
);

INSERT INTO news VALUES(NULL, "Huajiachi bid 13 billion yuan", "bid", "qwldihaksjdhqowi alsdaksjdbaksjdbqowi aksjhaskdjbq asjdhaksjbzkxgrqwuiy askjdh");
INSERT INTO news VALUES(NULL, "Xi calls for closer G20 ties to boost world economy", "President Xi", "Chinese President Xi Jinping on Thursday urged Group of 20 (G20) members to build a closer partnership to shore up the world economy.");