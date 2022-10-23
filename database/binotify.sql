CREATE DATABASE `binotify`;

DROP TABLE IF EXISTS `binotify`.`album`;
CREATE TABLE `binotify`.`album` (
  `album_id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(64) NOT NULL,
  `penyanyi` varchar(128) NOT NULL,
  `total_duration` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `tanggal_terbit` date NOT NULL,
  `genre` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`album_id`)
);
INSERT INTO `binotify`.`album` VALUES (1,'The Best of Me','Westlife',120,'/images/album/1.jpg','2010-01-01','Pop');

DROP TABLE IF EXISTS `binotify`.`song`;
CREATE TABLE `binotify`.`song` (
  `song_id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(64) NOT NULL,
  `penyanyi` varchar(128) NOT NULL,
  `tanggal_terbit` date NOT NULL,
  `genre` varchar(64) DEFAULT NULL,
  `duration` int NOT NULL,
  `audio_path` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `album_id` int DEFAULT NULL,
  PRIMARY KEY (`song_id`),
  FOREIGN KEY (`album_id`) REFERENCES `album` (`album_id`)
);

DROP TABLE IF EXISTS `binotify`.`user`;
CREATE TABLE `binotify`.`user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
);
INSERT INTO `binotify`.`user` VALUES (1,'lorem@gmail.com','password','loremipsum',1),(2,'ipsum@gmail.com','password','ipsumlorem',1);