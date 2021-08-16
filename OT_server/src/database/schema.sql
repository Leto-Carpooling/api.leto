CREATE TABLE `user` (
  `id` bigint unsigned not null primary key auto_increment,
  `firstname` varchar(255) ,
  `lastname` varchar(255) ,
  `email` varchar(255) not null,
  `phone` varchar(20),
  `account_status` enum("enabled", "disabled") not null,
  `profile_image` varchar(255),
  `user_type` enum ("rider", "driver", "admin"),
  `ev_code` int default 0,
  `user_password` varchar(256) not null,
  `email_verified` tinyint(1) default 0,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp   
);

CREATE TABLE `reset_password` (
  `id` bigint unsigned not null primary key auto_increment,
  `userId` bigint unsigned not null,
  `token` varchar(256) ,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp  ,
  FOREIGN KEY (`userId`) REFERENCES `user`(`id`) on delete cascade
);

CREATE TABLE `session` (
  `session_id` bigint unsigned not null primary key auto_increment,
  `userId` bigint unsigned not null,
  `session_token` varchar(1000) ,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp  ,
  FOREIGN KEY (`userId`) REFERENCES `user`(`id`) on delete cascade
);

CREATE TABLE `temporary_phone_number` (
  `id` bigint unsigned not null primary key auto_increment,
  `userId` bigint unsigned not null,
  `phone` varchar(256) ,
  `pv_code` int default 0,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp  ,
  FOREIGN KEY (`userId`) REFERENCES `user`(`id`) on delete cascade
);

CREATE TABLE `temporary_email` (
  `id` bigint unsigned not null primary key auto_increment,
  `userId` bigint unsigned not null,
  `email` varchar(256) ,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp  ,
  FOREIGN KEY (`userId`) REFERENCES `user`(`id`) on delete cascade
);

CREATE TABLE `driver_information` (
  `driverId` bigint unsigned not null primary key,
  `national_id` varchar(100) not null,
  `regular_license` varchar(100) not null,
  `approval_status` enum("declined", "pending", "approved") not null,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp   ,
  FOREIGN KEY (`driverId`) REFERENCES `user`(`id`) on delete cascade
);

CREATE TABLE `driver_document` (
  `driverId` bigint unsigned not null primary key,
  `national_id_image` varchar(255) ,
  `regular_license_image` varchar(255) ,
  `psv_license_image` varchar(255),
  `good_conduct_cert_image` varchar(255),
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp   ,
  FOREIGN KEY (`driverId`) REFERENCES `driver_information`(`driverId`) on delete cascade
);

CREATE TABLE `vehicle` (
  `vehicle_id` bigint unsigned not null primary key auto_increment,
  `driverId` bigint unsigned not null,
  `manufacturer` varchar(255) not null ,
  `model` varchar(255) not null,
  `capacity` int unsigned not null default 2,
  `license_plate` varchar(255) not null,
  `vehicle_color` varchar(100) not null,
  `created_on` datetime default current_timestamp,
  `updated_on` datetime default current_timestamp on update current_timestamp   ,
  FOREIGN KEY (`driverId`) REFERENCES `driver_information`(`driverId`) on delete CASCADE
);

CREATE TABLE `vehicle_document` (
  `vehicleId` bigint unsigned not null primary key  ,
`v_insurance_image` varchar(255),
  `v_registration_image` varchar(255),
  `v_inspection_report_image` varchar(255) ,
  `created_on` datetime default current_timestamp ,
  `updated_on` datetime default current_timestamp on update current_timestamp    ,
  FOREIGN KEY (`vehicleId`) REFERENCES `vehicle`(`vehicle_id`) on delete CASCADE
);







