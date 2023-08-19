DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `SiteID` mediumint default NULL,
  `Ph` mediumint default NULL,
  `Humidity` mediumint default NULL,
  `Light` varchar(255) default NULL,
  `Temperature` mediumint default NULL,
  `Pump` varchar(255) default NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1;

INSERT INTO `users` (`SiteID`,`Ph`,`Humidity`,`Light`,`Temperature`,`Pump`)
VALUES
  (1,1,67,"1",34,"1"),
  (2,11,79,"0",6,"1"),
  (1,14,60,"0",14,"0"),
  (2,6,72,"1",6,"1"),
  (1,14,88,"0",26,"1"),
  (1,0,44,"0",6,"0"),
  (2,6,45,"1",9,"0"),
  (1,3,44,"1",10,"0"),
  (1,13,21,"1",8,"1"),
  (1,6,93,"1",19,"0");
INSERT INTO `users` (`SiteID`,`Ph`,`Humidity`,`Light`,`Temperature`,`Pump`)
VALUES
  (2,9,56,"0",2,"0"),
  (1,7,57,"0",19,"0"),
  (2,7,64,"1",37,"0"),
  (1,13,28,"1",8,"0"),
  (1,1,82,"1",4,"1"),
  (1,2,94,"0",36,"0"),
  (2,2,93,"0",30,"0"),
  (1,4,67,"1",24,"0"),
  (2,12,97,"1",39,"1"),
  (2,6,55,"0",24,"1");
