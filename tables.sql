SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 
--

-- --------------------------------------------------------

--
-- Table structure for table `friendships`
--

CREATE TABLE IF NOT EXISTS `friendships` (
  `id` int(11) NOT NULL auto_increment,
  `user` varchar(50) NOT NULL,
  `friend` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photoAlbum`
--

CREATE TABLE IF NOT EXISTS `photoAlbum` (
  `id` int(11) NOT NULL auto_increment,
  `owner` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL auto_increment,
  `album` int(11) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `userDetail`
--

CREATE TABLE IF NOT EXISTS `userDetail` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '''''',
  `email` varchar(50) NOT NULL default '''''',
  `firstName` varchar(50) NOT NULL default '''''',
  `surname` varchar(50) NOT NULL default '''''',
  `picture` varchar(50) NOT NULL default 'images/default.png',
  `about` varchar(5000) NOT NULL default '''''',
  `dob` date NOT NULL,
  `gender` varchar(8) NOT NULL default '''''',
  `location` varchar(50) NOT NULL default '''''',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `usertype` varchar(10) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;
