CREATE DATABASE `task-force` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

USE `task-force`;

CREATE TABLE `user` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `login` varchar(255),
  `email` varchar(255),
  `password` varchar(255),
  `date_registration` timestamp DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `user_data` (
  `user_id` int,
  `name` varchar(255),
  `description` text,
  `age` int,
  `address` text,
  `skype` varchar(255),
  `phone` varchar(255),
  `other_messenger` varchar(255),
  `avatar` varchar(255),
  `rating` int,
  `views` int DEFAULT 0,
  `order_count` int,
  `status` varchar(255)
);

CREATE TABLE `user_settings` (
  `user_id` int,
  `is_hidden_contacts` bool DEFAULT FALSE,
  `is_hidden_profile` bool DEFAULT FALSE
);

CREATE TABLE `user_photo` (
  `user_id` int,
  `photo` varchar(255)
);

CREATE TABLE `specialization` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255),
  `code` varchar(255)
);

CREATE TABLE `user_specialization` (
  `user_id` int,
  `specialization_id` int
);

CREATE TABLE `task` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `author_id` int,
  `title` varchar(255),
  `description` text,
  `category_id` int,
  `price` int,
  `date_start` timestamp DEFAULT CURRENT_TIMESTAMP,
  `date_end` timestamp,
  `executor_id` int,
  `status` varchar(255)
);

CREATE TABLE `task_file` (
  `task_id` int,
  `file` varchar(255)
);

CREATE TABLE `category` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255),
  `code` varchar(255)
);

CREATE TABLE `chat` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `task_id` int
);

CREATE TABLE `message` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `chat_id` int,
  `author_id` int,
  `public_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `text` text
);

CREATE TABLE `review` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `author_id` int,
  `executor_id` int,
  `text` text,
  `rating` int
);

ALTER TABLE `user_data` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_settings` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_photo` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_specialization` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_specialization` ADD FOREIGN KEY (`specialization_id`) REFERENCES `specialization` (`id`) ON DELETE CASCADE;

ALTER TABLE `task` ADD FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

ALTER TABLE `task` ADD FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE;

ALTER TABLE `task` ADD FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

ALTER TABLE `task_file` ADD FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE;

ALTER TABLE `chat` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

ALTER TABLE `chat` ADD FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE;

ALTER TABLE `message` ADD FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`) ON DELETE CASCADE;

ALTER TABLE `review` ADD FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

ALTER TABLE `review` ADD FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
