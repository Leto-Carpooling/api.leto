DROP TABLE IF EXISTS `user` CASCADE;

CREATE TABLE `user` (
  `id` bigint unsigned not null primary key auto_increment,
  `firstname` varchar(255) ,
  `lastname` varchar(255) ,
  `email` varchar(255) not null,
  `dob` date,
  `profile_image` varchar(255),
  `user_type` enum ("rider", "driver", "admin"),
  `ev_code` int(6) default 0,
  `user_password` varchar(256) not null,
  `email_verified` tinyint(1) default 0,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp  
);

DROP TABLE IF EXISTS `reset_password` CASCADE;

CREATE TABLE `reset_password` (
  `id` bigint unsigned not null primary key auto_increment,
  `userId` bigint unsigned not null,
  `token` varchar(256) ,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp  ,
  FOREIGN KEY (`userId`) REFERENCES `user`(`id`)
);

DROP TABLE IF EXISTS `session` CASCADE;

CREATE TABLE `session` (
  `session_id` bigint unsigned not null primary key auto_increment,
  `userId` bigint unsigned not null,
  `session_token` varchar(300) ,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp  ,
  FOREIGN KEY (`userId`) REFERENCES `user`(`id`)
);





