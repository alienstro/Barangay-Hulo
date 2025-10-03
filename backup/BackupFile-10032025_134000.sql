CREATE DATABASE IF NOT EXISTS `barangay`;

USE `barangay`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `activity_log`;

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL DEFAULT 'none',
  `date` varchar(255) NOT NULL DEFAULT 'none',
  `status` varchar(255) NOT NULL DEFAULT 'none',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1331 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `activity_log` VALUES (1250,"ADMIN: UPDATED OFFICIAL POSITION -  0401202511295839347 |  FROM CHAIRMANS TO CHAIRMAN","1-4-2025 1:05 PM","update"),
(1251,"ADMIN: DELETED POSITION -  77311317124789201092022180612765 | chairmans","1-4-2025 7:05 AM","delete"),
(1252,"ADMIN: ADDED RESIDENT - 23388956417195 |  Alexandra Kane Non commodi saepe se","1-4-2025 1:06 PM","create"),
(1253,"ADMIN: ADDED RESIDENT - 3188696235402 |  Alexandra Mooney In rem voluptatem E","1-4-2025 1:06 PM","create"),
(1254,"ADMIN: Admin Admin | LOGOUT","1-4-2025 7:07 AM","logout"),
(1255,"ADMIN: Admin Admin | LOGIN","1-4-2025 1:09 PM","login"),
(1256,"ADMIN: ADDED BLOTTER RECORD  -  3647560271426891 | Complainant - Alexandra Kane | Incident - Incident | Date Incident 2025-04-19T13:10 | Location Incident Location of Incident | Complainant Statement - Complainant Statement | Respondent - Respondent","1-4-2025 1:10 PM","delete"),
(1257,"ADMIN: ADDED BLOTTER RECORD  -  3647560271426891 | Person Involved - Alexandra Kane | Incident - Incident | Date Incident 2025-04-19T13:10 | Location Incident Location of Incident | Complainant Statement - Complainant Statement | Respondent - Respondent","1-4-2025 1:10 PM","delete"),
(1258,"ADMIN: ADDED BLOTTER RECORD  -  3647560271426891 | Person Not Resident - wq | Incident - Incident | Date Incident 2025-04-19T13:10 | Location Incident Location of Incident | Complainant Statement - ewqewq | Respondent - Respondent","1-4-2025 1:10 PM","delete"),
(1259,"ADMIN: ADDED BLOTTER RECORD  -  3647560271426891 | Complainant Not Resident - Complainant Not Resident | Incident - Incident | Date Incident 2025-04-19T13:10 | Location Incident Location of Incident | Complainant Statement - Complainant Statement | Respon","1-4-2025 1:10 PM","delete"),
(1260,"ADMIN: ADDED RESIDENT - 37404238492438 |  Miriam Frost Harum sit ut provide","1-4-2025 1:10 PM","create"),
(1261,"ADMIN: ADDED RESIDENT - 1086692484891 |  Jelani Ellison Dolorum qui qui id v","1-4-2025 1:11 PM","create"),
(1262,"ADMIN: ADDED RESIDENT - 12435095932673 |  Darrel Kline Quas perferendis aut","1-4-2025 1:11 PM","create"),
(1263,"ADMIN: ADDED RESIDENT - 34151365970057 |  Moana Burt Dolorum fugiat nisi","1-4-2025 1:11 PM","create"),
(1264,"ADMIN: ADDED OFFICIAL - 0401202513120186625 | KAGAWAD Branden Whitney Minim dolores velit | START 2021-12-17 END 1971-04-06","1-4-2025 1:12 PM","create"),
(1265,"ADMIN: Admin Admin | LOGOUT","1-4-2025 7:12 AM","logout"),
(1266,"ADMIN: Admin Admin | LOGIN","1-4-2025 1:15 PM","login"),
(1267,"RESIDENT: REGISTER RESIDENT - 85162776572960 |  mark joshua pedro ","28-9-2025 8:56 PM","create"),
(1268,"RESIDENT: mark joshua pedro | LOGIN","28-9-2025 8:56 PM","login"),
(1269,"RESIDENT - 85162776572960: mark joshua pedro | REQUEST CERTIFICATE - INDIGENCY","28-9-2025 8:59 PM","create"),
(1270,"RESIDENT: mark joshua pedro | LOGOUT","28-9-2025 4:31 PM","logout"),
(1271,"ADMIN: Admin Admin | LOGIN","28-9-2025 10:31 PM","login"),
(1272,"ADMIN: ADDED RESIDENT - 68313917775145 |  krizia angela vergara suffix","28-9-2025 10:50 PM","create"),
(1273,"ADMIN: RESIDENT REQUEST CERTIFICATE ACCEPTED - 85162776572960 | PURPOSE INDIGENCY | MESSAGE none324 | DATE ISSUED 0234-03-31 | DATE EXPIRED 0432-03-24","28-9-2025 4:54 PM","updated"),
(1274,"ADMIN: ADDED OFFICIAL - 092820252256337200 | CHAIRMAN redd abanes suffix | START 4134-12-13 END 32025-03-24","28-9-2025 10:56 PM","create"),
(1275,"ADMIN: UPDATED OFFICIAL STATUS  -  092820252256337200 |  FROM ACTIVE TO INACTIVE","28-9-2025 4:58 PM","update"),
(1276,"ADMIN: UPDATED OFFICIAL STATUS  -  092820252256337200 |  FROM INACTIVE TO ACTIVE","28-9-2025 4:58 PM","update"),
(1277,"ADMIN: DELETED OFFICIAL -  092820252256337200 | CHAIRMAN - redd abanes","28-9-2025 4:59 PM","delete"),
(1278,"ADMIN: ADDED OFFICIAL - 0928202523004239825 | CHAIRMAN redd abanes  | START 2025-09-28 END 2026-09-28","28-9-2025 11:00 PM","create"),
(1279,"ADMIN: Admin Admin | LOGOUT","28-9-2025 5:05 PM","logout"),
(1280,"RESIDENT: krizia angela vergara | LOGIN","28-9-2025 11:06 PM","login"),
(1281,"RESIDENT - 68313917775145: krizia angela vergara | REQUEST CERTIFICATE - CERTIFICATE","28-9-2025 11:06 PM","create"),
(1282,"RESIDENT: krizia angela vergara | LOGOUT","28-9-2025 5:07 PM","logout"),
(1283,"ADMIN: Admin Admin | LOGIN","28-9-2025 11:07 PM","login"),
(1284,"ADMIN: RESIDENT REQUEST CERTIFICATE ACCEPTED - 68313917775145 | PURPOSE CERTIFICATE | MESSAGE none | DATE ISSUED 2025-09-28 | DATE EXPIRED 2025-10-15","28-9-2025 5:09 PM","updated"),
(1285,"ADMIN: Admin Admin | LOGOUT","28-9-2025 5:10 PM","logout"),
(1286,"RESIDENT: mark joshua pedro | LOGIN","28-9-2025 11:10 PM","login"),
(1287,"RESIDENT - 85162776572960: mark joshua pedro | REQUEST CERTIFICATE - CERTIFICATE","28-9-2025 11:11 PM","create"),
(1288,"RESIDENT: mark joshua pedro | LOGOUT","28-9-2025 5:11 PM","logout"),
(1289,"ADMIN: Admin Admin | LOGIN","28-9-2025 11:11 PM","login"),
(1290,"ADMIN: RESIDENT REQUEST CERTIFICATE ACCEPTED - 85162776572960 | PURPOSE CERTIFICATE | MESSAGE none | DATE ISSUED 2025-09-28 | DATE EXPIRED 2025-10-23","28-9-2025 5:11 PM","updated"),
(1291,"ADMIN: Admin Admin | LOGOUT","28-9-2025 5:30 PM","logout"),
(1292,"ADMIN: Admin Admin | LOGIN","28-9-2025 11:30 PM","login"),
(1293,"ADMIN: Admin Admin | LOGOUT","28-9-2025 5:42 PM","logout"),
(1294,"ADMIN: Admin Admin | LOGIN","28-9-2025 11:53 PM","login"),
(1295,"ADMIN: Admin Admin | LOGOUT","28-9-2025 5:58 PM","logout"),
(1296,"RESIDENT: mark joshua pedro | LOGIN","29-9-2025 12:53 AM","login"),
(1297,"RESIDENT: mark joshua pedro | LOGIN","29-9-2025 4:11 PM","login"),
(1298,"RESIDENT: mark joshua pedro | LOGOUT","29-9-2025 11:00 AM","logout"),
(1299,"ADMIN: Admin Admin | LOGIN","29-9-2025 7:32 PM","login"),
(1300,"ADMIN: Admin Admin | LOGOUT","29-9-2025 2:53 PM","logout"),
(1301,"RESIDENT: mark joshua pedro | LOGIN","29-9-2025 8:54 PM","login"),
(1302,"RESIDENT: mark joshua pedro | LOGIN","1-10-2025 5:15 PM","login"),
(1303,"RESIDENT: mark joshua pedro | LOGOUT","1-10-2025 11:44 AM","logout"),
(1304,"RESIDENT: mark joshua pedro | LOGIN","1-10-2025 5:44 PM","login"),
(1305,"RESIDENT: mark joshua pedro | LOGOUT","1-10-2025 12:07 PM","logout"),
(1306,"RESIDENT: mark joshua pedro | LOGIN","1-10-2025 6:07 PM","login"),
(1307,"RESIDENT: mark joshua pedro | LOGOUT","1-10-2025 12:07 PM","logout"),
(1308,"RESIDENT: mark joshua pedro | LOGIN","1-10-2025 6:19 PM","login"),
(1309,"RESIDENT: mark joshua pedro | LOGOUT","1-10-2025 12:28 PM","logout"),
(1310,"RESIDENT: mark joshua pedro | LOGIN","1-10-2025 6:29 PM","login"),
(1311,"RESIDENT: mark joshua pedro | LOGIN","2-10-2025 5:27 AM","login"),
(1312,"RESIDENT: mark joshua pedro | LOGOUT","1-10-2025 11:28 PM","logout"),
(1313,"RESIDENT: mark joshua pedro | LOGIN","2-10-2025 5:42 AM","login"),
(1314,"RESIDENT: mark joshua pedro | LOGOUT","2-10-2025 12:03 AM","logout"),
(1315,"ADMIN: Admin Admin | LOGIN","2-10-2025 6:07 AM","login"),
(1316,"ADMIN: Admin Admin | LOGOUT","2-10-2025 1:57 AM","logout"),
(1317,"RESIDENT: mark joshua pedro | LOGIN","2-10-2025 7:57 AM","login"),
(1318,"ADMIN: Admin Admin | LOGIN","3-10-2025 9:30 PM","login"),
(1319,"ADMIN: UPDATED OFFICIAL STATUS  -  0928202523004239825 |  FROM ACTIVE TO INACTIVE","3-10-2025 1:31 PM","update"),
(1320,"ADMIN: UPDATED OFFICIAL STATUS  -  0928202523004239825 |  FROM INACTIVE TO ACTIVE","3-10-2025 1:31 PM","update"),
(1321,"ADMIN: DELETED RESIDENT -  68313917775145 |  - krizia angela vergara","3-10-2025 1:32 PM","delete"),
(1322,"ADMIN: UNDELETED RESIDENT -  68313917775145 |  - krizia angela vergara","3-10-2025 1:36 PM","delete"),
(1323,"ADMIN: ADDED BLOTTER RECORD  -  7895825982133434 | Complainant - mark joshua pedro | Incident - Deserunt nulla dolor | Date Incident 2006-01-23T01:21 | Location Incident Consectetur sint p | Complainant Statement - Duis quis fuga Enim | Respondent - Omnis","3-10-2025 9:37 PM","delete"),
(1324,"ADMIN: ADDED BLOTTER RECORD  -  7895825982133434 | Person Involved - mark joshua pedro | Incident - Deserunt nulla dolor | Date Incident 2006-01-23T01:21 | Location Incident Consectetur sint p | Complainant Statement - Duis quis fuga Enim | Respondent - O","3-10-2025 9:37 PM","delete"),
(1325,"ADMIN: ADDED BLOTTER RECORD  -  7895825982133434 | Person Not Resident - Dolore reprehenderit | Incident - Deserunt nulla dolor | Date Incident 2006-01-23T01:21 | Location Incident Consectetur sint p | Complainant Statement - Veritatis tempor obc | Respon","3-10-2025 9:37 PM","delete"),
(1326,"ADMIN: ADDED BLOTTER RECORD  -  7895825982133434 | Complainant Not Resident - Officia quis sunt au | Incident - Deserunt nulla dolor | Date Incident 2006-01-23T01:21 | Location Incident Consectetur sint p | Complainant Statement - Duis quis fuga Enim | Re","3-10-2025 9:37 PM","delete"),
(1327,"ADMIN: ADDED BLOTTER RECORD  -  2535700933365660 | Complainant - mark joshua pedro | Incident - Ut nostrum pariatur | Date Incident 1989-02-20T21:58 | Location Incident Ullam anim aliquid i | Complainant Statement - Non consequatur nobi | Respondent - Asp","3-10-2025 9:38 PM","delete"),
(1328,"ADMIN: ADDED BLOTTER RECORD  -  2535700933365660 | Person Involved - mark joshua pedro | Incident - Ut nostrum pariatur | Date Incident 1989-02-20T21:58 | Location Incident Ullam anim aliquid i | Complainant Statement - Non consequatur nobi | Respondent -","3-10-2025 9:38 PM","delete"),
(1329,"ADMIN: ADDED BLOTTER RECORD  -  2535700933365660 | Person Not Resident - Consectetur placeat | Incident - Ut nostrum pariatur | Date Incident 1989-02-20T21:58 | Location Incident Ullam anim aliquid i | Complainant Statement - Qui ex vel ipsum no | Respond","3-10-2025 9:38 PM","delete"),
(1330,"ADMIN: ADDED BLOTTER RECORD  -  2535700933365660 | Complainant Not Resident -  | Incident - Ut nostrum pariatur | Date Incident 1989-02-20T21:58 | Location Incident Ullam anim aliquid i | Complainant Statement - Non consequatur nobi | Respondent - Asperio","3-10-2025 9:38 PM","delete");


DROP TABLE IF EXISTS `backup`;

CREATE TABLE `backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `backup` VALUES (170,"BackupFile-04012025_071908.sql"),
(171,"BackupFile-10032025_134000.sql");


DROP TABLE IF EXISTS `barangay_information`;

CREATE TABLE `barangay_information` (
  `id` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL DEFAULT 'none',
  `zone` varchar(255) NOT NULL DEFAULT 'none',
  `district` varchar(255) NOT NULL DEFAULT 'none',
  `address` varchar(69) NOT NULL DEFAULT 'none',
  `postal_address` varchar(255) NOT NULL DEFAULT 'none',
  `image` varchar(255) NOT NULL DEFAULT 'none',
  `image_path` varchar(255) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `barangay_information` VALUES (32432432432432432,"Barnagay","Zone","District","Manila","Postal Address","165897181867eb5acf2e8c4.jpg","../assets/dist/img/165897181867eb5acf2e8c4.jpg");


DROP TABLE IF EXISTS `blotter_complainant`;

CREATE TABLE `blotter_complainant` (
  `id` varchar(255) NOT NULL,
  `blotter_main` varchar(255) NOT NULL,
  `complainant_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `blotter_complainant` VALUES (48506839485307,7895825982133434,85162776572960),
(891703310301573,2535700933365660,85162776572960);


DROP TABLE IF EXISTS `blotter_info`;

CREATE TABLE `blotter_info` (
  `id` varchar(255) NOT NULL,
  `blotter_main_id` varchar(255) NOT NULL,
  `blotter_person_id` varchar(255) NOT NULL,
  `blotter_complainant_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `blotter_record`;

CREATE TABLE `blotter_record` (
  `blotter_id` varchar(255) NOT NULL,
  `complainant_not_residence` varchar(255) NOT NULL DEFAULT 'none',
  `statement` varchar(255) NOT NULL DEFAULT 'none',
  `respodent` varchar(255) NOT NULL DEFAULT 'none',
  `involved_not_resident` varchar(255) NOT NULL DEFAULT 'none',
  `statement_person` varchar(255) NOT NULL DEFAULT 'none',
  `date_incident` varchar(255) NOT NULL DEFAULT 'none',
  `date_reported` varchar(255) NOT NULL DEFAULT 'none',
  `type_of_incident` varchar(255) NOT NULL DEFAULT 'none',
  `location_incident` varchar(255) NOT NULL DEFAULT 'none',
  `status` varchar(69) NOT NULL DEFAULT 'none',
  `remarks` varchar(69) NOT NULL DEFAULT 'none',
  `date_added` varchar(255) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`blotter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `blotter_record` VALUES (2535700933365660,"","Non consequatur nobi","Asperiores sed volup","Consectetur placeat","Qui ex vel ipsum no","1989-02-20T21:58","2019-03-12T11:11","Ut nostrum pariatur","Ullam anim aliquid i","NEW","CLOSED",2025),
(7895825982133434,"Officia quis sunt au","Duis quis fuga Enim","Omnis voluptatem Am","Dolore reprehenderit","Veritatis tempor obc","2006-01-23T01:21","2009-03-04T22:25","Deserunt nulla dolor","Consectetur sint p","NEW","OPEN",2025);


DROP TABLE IF EXISTS `blotter_status`;

CREATE TABLE `blotter_status` (
  `blotter_id` varchar(255) NOT NULL,
  `blotter_main` varchar(255) NOT NULL,
  `person_id` varchar(255) NOT NULL,
  PRIMARY KEY (`blotter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `blotter_status` VALUES (574431775408384,2535700933365660,68313917775145),
(701007193144942,7895825982133434,68313917775145);


DROP TABLE IF EXISTS `carousel`;

CREATE TABLE `carousel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_title` varchar(255) NOT NULL,
  `banner_image` varchar(255) NOT NULL,
  `banner_image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `certificate`;

CREATE TABLE `certificate` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_id` varchar(255) NOT NULL,
  `residence_id` varchar(255) NOT NULL,
  `certificate` varchar(255) NOT NULL,
  `ctc` varchar(255) NOT NULL,
  `issued_at` varchar(255) NOT NULL,
  `or_no` varchar(255) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `control_no` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `expired_at` varchar(255) NOT NULL,
  PRIMARY KEY (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `certificate_request`;

CREATE TABLE `certificate_request` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(255) NOT NULL,
  `residence_id` varchar(255) NOT NULL,
  `certificate_type` varchar(255) NOT NULL DEFAULT 'none',
  `purpose` varchar(255) NOT NULL DEFAULT 'none',
  `message` varchar(255) NOT NULL DEFAULT 'none',
  `date_issued` varchar(255) NOT NULL DEFAULT 'none',
  `date_request` varchar(255) NOT NULL DEFAULT 'none',
  `date_expired` varchar(255) NOT NULL DEFAULT 'none',
  `status` varchar(255) NOT NULL DEFAULT 'none',
  UNIQUE KEY `a_i` (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `certificate_request` VALUES (65,"131079786909282025230655540108684146068d94f0f83e87",68313917775145,"none","CERTIFICATE","none","2025-09-28","09/28/2025","2025-10-15","ACCEPTED"),
(66,"18564123430928202523110254539164263068d9500685109",85162776572960,"none","CERTIFICATE","none","2025-09-28","09/28/2025","2025-10-23","ACCEPTED");


DROP TABLE IF EXISTS `house_holds`;

CREATE TABLE `house_holds` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `house_hold_id` varchar(255) NOT NULL DEFAULT 'none',
  `hold_unique` varchar(255) NOT NULL,
  `purok_id` varchar(255) NOT NULL,
  `residence_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `educ_attainment` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `nawasa` varchar(255) NOT NULL,
  `water_pump` varchar(255) NOT NULL,
  `water_sealed` varchar(255) NOT NULL,
  `flush` varchar(255) NOT NULL,
  `religion` varchar(255) NOT NULL,
  `ethnicity` varchar(255) NOT NULL,
  `sangkap_seal` varchar(255) NOT NULL,
  `is_approved` varchar(255) NOT NULL,
  `is_resident` varchar(255) NOT NULL,
  PRIMARY KEY (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `house_holds` VALUES (119,765720104206,90635228440419,916259339179300507242022155033612,16455182440138," First name","Middle name","Last name","2022-10-08","Female","educ","Occupation","YES","YES","YES","YES","Religion","Ethnicity","YES","APPROVED","NO"),
(120,377220153950,90635228440419,916259339179300507242022155033612,16455182440138,"qe","qwe","qweqwe","2022-10-12","Male","qweqw","wqe","YES","YES","YES","YES","qwe","eqwe","YES","APPROVED","NO"),
(121,377220153950,90635228440419,916259339179300507242022155033612,16455182440138,"First Name","Middle Name","Last Name","2022-10-27","Male","qwewqe","Occupation","YES","YES","YES","NO","Religion","qwe","YES","APPROVED","YES"),
(122,530011579769,70838125253292,916259339179300507242022155033612,54278971251733," First name","Middle name","Last name","2022-10-08","Male","Educational Attainment","Occupation","YES","YES","YES","YES","Religion","Ethnicity","YES","PENDING","NO"),
(123,85351248693,70838125253292,916259339179300507242022155033612,54278971251733,"qwe","wqe","wqewqe","2022-10-15","Female","wqe","wqe","YES","YES","YES","YES","qwe","qwe","YES","PENDING","NO"),
(124,85351248693,70838125253292,916259339179300507242022155033612,54278971251733,"Eugine","Palce","ROsillon","1997-09-06","Male","Educational Attainment","Wala","YES","YES","YES","YES","Catholic","Ethnicity","YES","PENDING","YES");


DROP TABLE IF EXISTS `official_end_information`;

CREATE TABLE `official_end_information` (
  `official_id` varchar(255) NOT NULL,
  `first_name` varchar(69) NOT NULL DEFAULT 'none',
  `middle_name` varchar(69) NOT NULL DEFAULT 'none',
  `last_name` varchar(69) NOT NULL DEFAULT 'none',
  `suffix` varchar(69) NOT NULL DEFAULT 'none',
  `birth_date` varchar(69) NOT NULL DEFAULT 'none',
  `birth_place` varchar(69) NOT NULL DEFAULT 'none',
  `gender` varchar(69) NOT NULL DEFAULT 'none',
  `age` varchar(69) NOT NULL DEFAULT 'none',
  `civil_status` varchar(69) NOT NULL DEFAULT 'none',
  `religion` varchar(69) NOT NULL DEFAULT 'none',
  `nationality` varchar(69) NOT NULL DEFAULT 'none',
  `municipality` varchar(69) NOT NULL DEFAULT 'none',
  `zip` varchar(69) NOT NULL DEFAULT 'none',
  `barangay` varchar(69) NOT NULL DEFAULT 'none',
  `house_number` varchar(69) NOT NULL DEFAULT 'none',
  `street` varchar(69) NOT NULL DEFAULT 'none',
  `address` varchar(69) NOT NULL DEFAULT 'none',
  `email_address` varchar(69) NOT NULL DEFAULT 'none',
  `contact_number` varchar(69) NOT NULL DEFAULT 'none',
  `fathers_name` varchar(69) NOT NULL DEFAULT 'none',
  `mothers_name` varchar(69) NOT NULL DEFAULT 'none',
  `guardian` varchar(69) NOT NULL DEFAULT 'none',
  `guardian_contact` varchar(69) NOT NULL DEFAULT 'none',
  `image` varchar(255) NOT NULL DEFAULT 'none',
  `image_path` varchar(255) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`official_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `official_end_information` VALUES (092820252256337200,"redd","Middle Name","abanes","suffix","2025-12-31",124234,"Male",0,"Single","adfasd","manda","","","","","","sdfdsfaf","",14314341341,"","","","","","");


DROP TABLE IF EXISTS `official_end_status`;

CREATE TABLE `official_end_status` (
  `official_id` varchar(255) NOT NULL,
  `position` varchar(69) NOT NULL DEFAULT 'none',
  `purok_id` varchar(255) NOT NULL,
  `senior` varchar(69) NOT NULL DEFAULT 'none',
  `term_from` varchar(69) NOT NULL DEFAULT 'none',
  `term_to` varchar(69) NOT NULL DEFAULT 'none',
  `pwd` varchar(69) NOT NULL DEFAULT 'none',
  `pwd_info` varchar(255) NOT NULL DEFAULT 'none',
  `single_parent` varchar(69) NOT NULL DEFAULT 'none',
  `status` varchar(69) NOT NULL DEFAULT 'none',
  `voters` varchar(69) NOT NULL DEFAULT 'none',
  `date_deleted` varchar(69) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`official_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `official_information`;

CREATE TABLE `official_information` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `official_id` varchar(255) NOT NULL,
  `first_name` varchar(69) NOT NULL DEFAULT 'none',
  `middle_name` varchar(69) NOT NULL DEFAULT 'none',
  `last_name` varchar(69) NOT NULL DEFAULT 'none',
  `suffix` varchar(69) NOT NULL DEFAULT 'none',
  `birth_date` varchar(69) NOT NULL DEFAULT 'none',
  `birth_place` varchar(69) NOT NULL DEFAULT 'none',
  `gender` varchar(69) NOT NULL DEFAULT 'none',
  `age` varchar(69) NOT NULL DEFAULT 'none',
  `civil_status` varchar(69) NOT NULL DEFAULT 'none',
  `religion` varchar(69) NOT NULL DEFAULT 'none',
  `nationality` varchar(69) NOT NULL DEFAULT 'none',
  `municipality` varchar(69) NOT NULL DEFAULT 'none',
  `zip` varchar(69) NOT NULL DEFAULT 'none',
  `barangay` varchar(69) NOT NULL DEFAULT 'none',
  `house_number` varchar(69) NOT NULL DEFAULT 'none',
  `street` varchar(69) NOT NULL DEFAULT 'none',
  `address` varchar(69) NOT NULL DEFAULT 'none',
  `email_address` varchar(69) NOT NULL DEFAULT 'none',
  `contact_number` varchar(69) NOT NULL DEFAULT 'none',
  `fathers_name` varchar(69) NOT NULL DEFAULT 'none',
  `mothers_name` varchar(69) NOT NULL DEFAULT 'none',
  `guardian` varchar(69) NOT NULL DEFAULT 'none',
  `guardian_contact` varchar(69) NOT NULL DEFAULT 'none',
  `image` varchar(255) NOT NULL DEFAULT 'none',
  `image_path` varchar(255) NOT NULL DEFAULT 'none',
  UNIQUE KEY `a_i` (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `official_information` VALUES (66,0928202523004239825,"redd","dacaymat","abanes","","2000-03-12","manda","Male",25,"Single","","","","","","","","dfadfasd","",13241413414,"","","","","","");


DROP TABLE IF EXISTS `official_status`;

CREATE TABLE `official_status` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `official_id` varchar(255) NOT NULL,
  `position` varchar(69) NOT NULL DEFAULT 'none',
  `purok_id` varchar(255) NOT NULL,
  `senior` varchar(69) NOT NULL DEFAULT 'none',
  `term_from` varchar(69) NOT NULL DEFAULT 'none',
  `term_to` varchar(69) NOT NULL DEFAULT 'none',
  `pwd` varchar(69) NOT NULL DEFAULT 'none',
  `pwd_info` varchar(255) NOT NULL DEFAULT 'none',
  `status` varchar(69) NOT NULL DEFAULT 'none',
  `voters` varchar(69) NOT NULL DEFAULT 'none',
  `single_parent` varchar(255) NOT NULL DEFAULT 'none',
  `date_added` varchar(69) NOT NULL DEFAULT 'none',
  `date_undeleted` varchar(255) NOT NULL DEFAULT 'none',
  UNIQUE KEY `a_i` (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `official_status` VALUES (60,0928202523004239825,619131249471207208162022141229307,"","NO","2025-09-28","2026-09-28","NO","","ACTIVE","YES","YES","09/28/2025 11:00 PM","none");


DROP TABLE IF EXISTS `position`;

CREATE TABLE `position` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `position_id` varchar(255) NOT NULL,
  `position` varchar(69) NOT NULL DEFAULT 'none',
  `position_limit` varchar(69) NOT NULL DEFAULT 'none',
  `position_description` varchar(255) NOT NULL DEFAULT 'none',
  `color` varchar(255) NOT NULL DEFAULT 'none',
  UNIQUE KEY `a_i` (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `position` VALUES (20,268778674891281501142022025704271,"kagawad",7,"","#50d425"),
(21,811981911875128801142022163118246,"sk kagawad",7,"testt","#3bc173"),
(22,619131249471207208162022141229307,"chairman",1,"","#4fb42e");


DROP TABLE IF EXISTS `precint`;

CREATE TABLE `precint` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `precint_id` varchar(255) NOT NULL,
  `precint` varchar(255) NOT NULL,
  PRIMARY KEY (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `precint` VALUES (1,112430277815139107242022164651634,12313200),
(5,834679331034411909122022012433363,"Test 123");


DROP TABLE IF EXISTS `purok`;

CREATE TABLE `purok` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `purok_id` varchar(255) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `leader` varchar(255) NOT NULL,
  PRIMARY KEY (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `purok` VALUES (2,916259339179300507242022155033612,"puirok","qweqwe"),
(5,74710938236700907272022172121040,"ewqe","wqewqeq");


DROP TABLE IF EXISTS `residence_information`;

CREATE TABLE `residence_information` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `residence_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL DEFAULT 'none',
  `middle_name` varchar(255) NOT NULL DEFAULT 'none',
  `last_name` varchar(255) NOT NULL DEFAULT 'none',
  `age` varchar(11) NOT NULL,
  `suffix` varchar(255) NOT NULL DEFAULT 'none',
  `alias` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL DEFAULT 'none',
  `civil_status` varchar(36) NOT NULL DEFAULT 'none',
  `religion` varchar(36) NOT NULL DEFAULT 'none',
  `nationality` varchar(255) NOT NULL DEFAULT 'none',
  `contact_number` varchar(69) NOT NULL DEFAULT 'none',
  `email_address` varchar(255) NOT NULL DEFAULT 'none',
  `address` varchar(255) NOT NULL DEFAULT 'none',
  `birth_date` varchar(255) NOT NULL DEFAULT 'none',
  `birth_place` varchar(255) NOT NULL DEFAULT 'none',
  `municipality` varchar(69) NOT NULL DEFAULT 'none',
  `zip` varchar(69) NOT NULL DEFAULT 'none',
  `barangay` varchar(69) NOT NULL DEFAULT 'none',
  `house_number` varchar(69) NOT NULL DEFAULT 'none',
  `street` varchar(69) NOT NULL DEFAULT 'none',
  `fathers_name` varchar(255) NOT NULL DEFAULT 'none',
  `mothers_name` varchar(255) NOT NULL DEFAULT 'none',
  `guardian` varchar(69) NOT NULL DEFAULT 'none',
  `guardian_contact` varchar(69) NOT NULL DEFAULT 'none',
  `occupation` varchar(255) NOT NULL,
  `employer_name` varchar(255) NOT NULL,
  `family_relation` varchar(255) NOT NULL,
  `national_number` varchar(255) NOT NULL,
  `sss_number` varchar(255) NOT NULL,
  `tin_number` varchar(255) NOT NULL,
  `gsis_number` varchar(255) NOT NULL,
  `pagibig_number` varchar(255) NOT NULL,
  `philhealth_number` varchar(255) NOT NULL,
  `bloodtype` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'none',
  `image_path` varchar(255) NOT NULL DEFAULT 'none',
  UNIQUE KEY `a_` (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `residence_information` VALUES (182,85162776572960,"mark joshua","orejas","pedro",21,"","","Male","Single","catholic","filipino",09545454547,"jsgjsd@gmail","ada","2004-05-02","  Mandaluyong City","manda",1550,"hulo",42,"21 b street","asdfasdf","asdasdas","dasdasdas",09456546456,"","","","","","","","","","","170558109168dd076b5f198.jpg","../assets/dist/img/170558109168dd076b5f198.jpg"),
(183,68313917775145,"krizia angela","baliw","vergara",1890,"suffix","","Male","Single","catholic","mandaluyong",11111111111,"","adfafadfadsfadsfadfa","0134-12-24","manda","","","","","","","","","","","","","","","","","","","","","");


DROP TABLE IF EXISTS `residence_status`;

CREATE TABLE `residence_status` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `residence_id` varchar(255) NOT NULL,
  `status` varchar(69) NOT NULL DEFAULT 'none',
  `is_approved` varchar(255) NOT NULL,
  `voters` varchar(69) NOT NULL DEFAULT 'none',
  `pwd` varchar(69) NOT NULL DEFAULT 'none',
  `pwd_info` varchar(255) NOT NULL DEFAULT 'none',
  `senior` varchar(69) NOT NULL DEFAULT 'none',
  `single_parent` varchar(69) NOT NULL DEFAULT 'none',
  `wra` varchar(255) NOT NULL,
  `4ps` varchar(255) NOT NULL,
  `purok_id` varchar(255) NOT NULL,
  `precint_id` varchar(255) NOT NULL,
  `archive` varchar(69) NOT NULL DEFAULT 'none',
  `date_added` varchar(69) NOT NULL DEFAULT 'none',
  `date_archive` varchar(69) NOT NULL DEFAULT 'none',
  `date_unarchive` varchar(69) NOT NULL DEFAULT 'none',
  UNIQUE KEY `a_i` (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `residence_status` VALUES (182,85162776572960,"ACTIVE","","YES","NO","","NO","YES","","","","","NO","09/28/2025 08:56 PM","none","none"),
(183,68313917775145,"ACTIVE","","YES","NO","","YES","NO","","","","","NO","09/28/2025 10:50 PM","10/03/2025 01:32 PM","10/03/2025 01:36 PM");


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL DEFAULT 'none',
  `middle_name` varchar(255) NOT NULL DEFAULT 'none',
  `last_name` varchar(255) NOT NULL DEFAULT 'none',
  `username` varchar(255) NOT NULL DEFAULT 'none',
  `password` varchar(255) NOT NULL DEFAULT 'none',
  `user_type` varchar(255) NOT NULL DEFAULT 'none',
  `contact_number` varchar(255) NOT NULL DEFAULT 'none',
  `image` varchar(255) NOT NULL DEFAULT 'none',
  `image_path` varchar(255) NOT NULL DEFAULT 'none',
  UNIQUE KEY `a_i` (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=207 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES (52,1506135735699,"Admin","Admin","Admin","admin123","admin123","admin",11111111111,"182708071361a0f053c94fb.png","../assets/dist/img/182708071361a0f053c94fb.png"),
(195,174668789044820710152022021619941,"Secretary","Secretary","Secretary","secretary123","secretary123","secretary",99999999999,"",""),
(205,85162776572960,"mark joshua","orejas","pedro","markjoshuapedro","mark12345","resident",09545454547,"170558109168dd076b5f198.jpg","../assets/dist/img/170558109168dd076b5f198.jpg"),
(206,68313917775145,"krizia angela","baliw","vergara",68313917775145,09282025225011475,"resident",11111111111,"","");


DROP TABLE IF EXISTS `vaccine`;

CREATE TABLE `vaccine` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `vaccine_id` varchar(255) NOT NULL,
  `residence_id` varchar(255) NOT NULL,
  `vaccine` varchar(255) NOT NULL,
  `second_vaccine` varchar(255) NOT NULL,
  `first_dose_date` varchar(255) NOT NULL,
  `second_dose_date` varchar(255) NOT NULL,
  `booster` varchar(255) NOT NULL,
  `booster_date` varchar(255) NOT NULL,
  `second_booster` varchar(255) NOT NULL,
  `second_booster_date` varchar(255) NOT NULL,
  PRIMARY KEY (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `vaccine` VALUES (35,3267818051106726,16455182440138,"first","second","2022-10-01","2022-10-02","first b","2022-10-03","second b","2022-10-04"),
(36,9517807083807772,54278971251733,"first","second","2022-10-01","2022-10-02","first b","2022-10-03","second b","2022-10-04");


DROP TABLE IF EXISTS `wra`;

CREATE TABLE `wra` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `resident_id` varchar(255) NOT NULL,
  `nhts` varchar(255) NOT NULL,
  `pregnant` varchar(255) NOT NULL,
  `menopause` varchar(255) NOT NULL,
  `achieving` varchar(255) NOT NULL,
  `ofw` varchar(255) NOT NULL,
  `fp_method` varchar(255) NOT NULL,
  `desire_limit` varchar(255) NOT NULL,
  `desire_space` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  PRIMARY KEY (`a_i`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wra` VALUES (62,16455182440138,"NTHS","YES","YES","YES","YES","FP Method","YES","YES",""),
(63,54278971251733,"NTHS","YES","YES","YES","YES","FP Method","YES","YES","");


SET foreign_key_checks = 1;
