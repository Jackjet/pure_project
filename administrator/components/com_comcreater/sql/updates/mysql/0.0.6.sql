DROP TABLE IF EXISTS `#__comcreater`;
 
CREATE TABLE `#__comcreater` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 
INSERT INTO `#__comcreater` (`title`) VALUES
	('Hello World!'),
	('Good bye World!');
