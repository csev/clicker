<?php

$DATABASE_UNINSTALL = "drop table if exists {$CFG->dbprefix}solution_wiscrowd";

$DATABASE_INSTALL = array(
array( "{$CFG->dbprefix}solution_wiscrowd",
"
CREATE TABLE `clicker` (
 `link_id` int(11) NOT NULL,
 `user_id` int(11) NOT NULL,
 `guess` float DEFAULT NULL,
 `attend` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY `link_id` (`link_id`,`user_id`),
 KEY `clicker_ibfk_2` (`user_id`),
 CONSTRAINT `clicker_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `lti_link` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `clicker_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `lti_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8"));
