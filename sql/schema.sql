CREATE TABLE `user` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `city_id` int DEFAULT NULL,
  `date_registration` timestamp DEFAULT now()
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `user_data` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `description` text,
  `age` int,
  `address` text,
  `skype` varchar(255),
  `phone` varchar(255),
  `other_messenger` varchar(255),
  `avatar` varchar(255),
  `rating` int,
  `views` int,
  `order_count` int,
  `status` varchar(255)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `user_settings` (
  `user_id` int,
  `is_hidden_contacts` boolean,
  `is_hidden_profile` boolean
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `user_photo` (
  `user_id` int,
  `photo` varchar(255)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `user_specialization` (
  `user_id` int,
  `category_id` int
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `task` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `author_id` int,
  `title` varchar(255),
  `description` text,
  `category_id` int,
  `price` int,
  `location` varchar(255),
  `date_start` timestamp,
  `date_end` timestamp,
  `executor_id` int,
  `is_telework` boolean DEFAULT FALSE,
  `status` varchar(255)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `task_file` (
  `task_id` int,
  `file` varchar(255)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `task_respond` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `task_id` int,
  `user_id` int,
  `text` text,
  `price` int NOT NULL,
  `status` varchar(255) NOT NULL,
  `public_date` timestamp
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `category` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `chat` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `task_id` int
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `message` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `chat_id` int,
  `author_id` int NOT NULL,
  `public_date` timestamp,
  `text` text
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `review` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `task_id` int,
  `author_id` int,
  `executor_id` int,
  `text` text,
  `rating` int
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;

CREATE TABLE `city` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lat` float,
  `long` float
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB;
