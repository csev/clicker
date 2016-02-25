<?php

$DATABASE_UNINSTALL = "drop table if exists {$CFG->dbprefix}clicker";

$DATABASE_INSTALL = array(
array( "{$CFG->dbprefix}clicker",
"CREATE TABLE `{$CFG->dbprefix}clicker` (
 `link_id` int(11) NOT NULL,
 `user_id` int(11) NOT NULL,
 `guess` float DEFAULT NULL,
 `count` int(11) NOT NULL,
 `attend` Date DEFAULT NULL,
 `ipaddr` VARCHAR(64) DEFAULT NULL,


 CONSTRAINT `{$CFG->dbprefix}clicker_ibfk_1` 
   FOREIGN KEY (`link_id`) 
   REFERENCES `{$CFG->dbprefix}lti_link` (`link_id`) 
   ON DELETE CASCADE ON UPDATE CASCADE,

 CONSTRAINT `{$CFG->dbprefix}clicker_ibfk_2` 
   FOREIGN KEY (`user_id`)
   REFERENCES `{$CFG->dbprefix}lti_user` (`user_id`) 
   ON DELETE CASCADE ON UPDATE CASCADE,

   UNIQUE(link_id, user_id, attend)
) ENGINE=InnoDB DEFAULT CHARSET=utf8"));
