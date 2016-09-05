-- MySQL dump 8.12
--
-- Host: localhost    Database: mytimepunch
----------------------------------------------------------
-- Server version	3.23.31

--
-- Table structure for table 'status'
--

CREATE TABLE status (
  userid int(11) NOT NULL,
  status varchar(10) NOT NULL,
  PRIMARY KEY (userid)
) TYPE=MyISAM;

--
-- Dumping data for table 'status'
--

INSERT INTO status VALUES ('1','Out');

--
-- Table structure for table 'timein'
--

CREATE TABLE timein (
  id int(11) NOT NULL auto_increment,
  time datetime NOT NULL,
  userid int(11) NOT NULL,
  PRIMARY KEY (id)
) TYPE=MyISAM;


--
-- Table structure for table 'timeout'
--

CREATE TABLE timeout (
  id int(11) NOT NULL auto_increment,
  time datetime NOT NULL,
  userid int(11) NOT NULL,
  PRIMARY KEY (id)
) TYPE=MyISAM;

--
-- Table structure for table 'users'
--

CREATE TABLE users (
  id int(11) unsigned NOT NULL auto_increment,
  username varchar(25) NOT NULL,
  password varchar(50) NOT NULL,
  admin tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY username(username)
) TYPE=MyISAM;

--
-- Dumping data for table 'users'
--

INSERT INTO users VALUES (NULL,'test','378b243e220ca493',0);

--
-- Table structure for table 'notes'
--

CREATE TABLE notes (
  id int(11) unsigned NOT NULL auto_increment,
  note MEDIUMTEXT NOT NULL, 
  time datetime NOT NULL,
  userid int(11) NOT NULL,
  PRIMARY KEY (id)
) TYPE=MyISAM;

--
-- Table structure for table 'event'
--

CREATE TABLE event (
  id int(11) unsigned NOT NULL auto_increment,
  event_type_id int(11) NOT NULL,
  description varchar(200) DEFAULT NULL, 
  event_date date NOT NULL,
  userid int(11) NOT NULL,
  PRIMARY KEY (id)
) TYPE=MyISAM;

--
-- Table structure for table 'event_type'
--

CREATE TABLE event_type (
  id int(11) unsigned NOT NULL auto_increment,
  name char(50) NOT NULL, 
  active tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'event_type'
--

INSERT INTO event_type VALUES (1,'Carpool', 1);
INSERT INTO event_type VALUES (2,'Holiday', 1);
INSERT INTO event_type VALUES (3,'Sick', 1);
INSERT INTO event_type VALUES (4,'Vacation', 1);

-- End of File
