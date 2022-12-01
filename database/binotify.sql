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
INSERT INTO `binotify`.`album` VALUES (1,'The Best of Me','Ardhito Pramono',919,'./assets/images/album/1.jpg','2010-01-01','Pop');
INSERT INTO `binotify`.`album` VALUES (2,'The Worst of Me','Badlife',1267,'./assets/images/album/2.jpg','2010-01-01','Pop');

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
SET PERSIST information_schema_stats_expiry=0;
INSERT INTO `binotify`.`song` VALUES
(1,'Tokyo Drift','Ardhito Pramono','2000-10-16','sedih',257,'./assets/musics/song1.mp3','./assets/images/image1.png',1),
(2,'Goosebumps','Ardhito Pramono', '2000-10-11','sedih',164,'./assets/musics/song2.mp3','./assets/images/image2.png',1),
(3,'Semoga, Ya', 'Ardhito Pramono','2000-10-12','sedih',268,'./assets/musics/song3.mp3','./assets/images/image3.png',1),
(4,'cigarretes of ours','Ardhito Pramono', '2000-10-13','sedih',230,'./assets/musics/song4.mp3','./assets/images/image4.png',1),
(5,'Sesuatu Di Jogja','Badlife', '2000-10-14','sedih',287,'./assets/musics/song5.mp3','./assets/images/image5.png',2),
(6,'Rehat','Badlife','2000-10-15','sedih',361,'./assets/musics/song6.mp3','./assets/images/image6.png',2),
(7,'Untuk Perempuan Yang Sedang Di Pelukan', 'Badlife','2000-10-10','sedih',257,'./assets/musics/song7.mp3','./assets/images/image7.png',2),
(8,'Langit Abu-Abu','Badlife', '2000-10-11','sedih',218,'./assets/musics/song8.mp3','./assets/images/image8.png',2),
(9,'HAHA HIHI','Badlife', '2000-10-11','sedih',257,'./assets/musics/song9.mp3','./assets/images/image9.jpg',NULL),
(10, 'Tracing That Dream','Yoasobi','2020-10-18','Pop',239,'./assets/musics/song10.mp3','./assets/images/image10.jpeg',NULL),
(11, 'Fortune Cookie','AKB48','2020-10-19','Pop',322,'./assets/musics/song11.mp3','./assets/images/image11.jpeg', NULL),
(12, 'Stella-Rium','Kano','2020-10-20','Pop',244,'./assets/musics/song12.mp3','./assets/images/image12.jpeg', NULL);

DROP TABLE IF EXISTS `binotify`.`user`;
CREATE TABLE `binotify`.`user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
);

INSERT INTO `binotify`.`user` (`user_id`, `email`, `password`, `name`, `username`, `isAdmin`) VALUES
  (1, '1352002@std.stei.itb.ac.id', '$2y$10$KHFOPrd1l6EjwkNJEkyGx.Kn8tTukczRHFHwZK6eco6OsbAvknp9O', 'Ranjabi', '1352002', 0),
  (2, '1352080@std.stei.itb.ac.id', '$2y$10$KHFOPrd1l6EjwkNJEkyGx.Kn8tTukczRHFHwZK6eco6OsbAvknp9O', 'Jason', '1352080', 0),
  (3, '13520161@std.stei.itb.ac.id', '$2y$10$KHFOPrd1l6EjwkNJEkyGx.Kn8tTukczRHFHwZK6eco6OsbAvknp9O', 'SP', '13520161', 0),
  (4, 'user@gmail.com', '$2y$10$KHFOPrd1l6EjwkNJEkyGx.Kn8tTukczRHFHwZK6eco6OsbAvknp9O', 'halo', 'halohalo', 0),
  (5, 'admin@gmail.com', '$2y$10$KHFOPrd1l6EjwkNJEkyGx.Kn8tTukczRHFHwZK6eco6OsbAvknp9O', 'halo', 'halohalo', 1);

DROP TABLE IF EXISTS `binotify`.`subscription`;
CREATE TABLE `binotify`.`subscription` (
  `creator_id` int NOT NULL,
  `subscriber_id` int NOT NULL,
  `status` ENUM('PENDING','ACCEPTED','REJECTED') DEFAULT 'PENDING' NOT NULL,
  PRIMARY KEY (`creator_id`,`subscriber_id`),
  FOREIGN KEY (`subscriber_id`) REFERENCES `user` (`user_id`)
);

INSERT INTO `binotify`.`subscription` VALUES
('3','1','PENDING'),
('4','2','PENDING'),
('1','3','ACCEPTED'),
('1','4','PENDING'),
('3','1','ACCEPTED'),
('4','2','REJECTED'),
('1','3','PENDING');