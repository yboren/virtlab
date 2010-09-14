create database guestbook;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL auto_increment,
  `parent` int(11) default '0',
  `thread` int(11) default '0',
  `name` tinytext,
  `email` tinytext,
  `subject` tinytext,
  `time` int(11) NOT NULL default '0',
  `ip` varchar(15) default NULL,
  `topic_emoticon` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `thread` (`thread`),
  KEY `id` (`id`),
  KEY `parent` (`parent`)
) TYPE=MyISAM;


CREATE TABLE `messages_text` (
  `mesid` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  UNIQUE KEY `mesid` (`mesid`)
) TYPE=MyISAM;

