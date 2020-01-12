CREATE TABLE `user` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_registration` timestamp DEFAULT (now())
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
  `views` int,
  `order_count` int,
  `status` varchar(255)
);

CREATE TABLE `user_settings` (
  `user_id` int,
  `is_hidden_contacts` boolean,
  `is_hidden_profile` boolean
);

CREATE TABLE `user_photo` (
  `user_id` int,
  `photo` varchar(255)
);

CREATE TABLE `user_specialization` (
  `user_id` int,
  `category_id` int
);

CREATE TABLE `task` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `author_id` int,
  `title` varchar(255),
  `description` text,
  `category_id` int,
  `price` int NOT NULL,
  `date_start` timestamp,
  `date_end` timestamp,
  `executor_id` int,
  `is_telework` boolean DEFAULT FALSE,
  `status` boolean DEFAULT TRUE
);

CREATE TABLE `task_file` (
  `task_id` int,
  `file` varchar(255)
);

CREATE TABLE `task_respond` (
  `task_id` int,
  `user_id` int,
  `text` text,
  `price` int NOT NULL,
  `public_date` timestamp
);

CREATE TABLE `category` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL
);

CREATE TABLE `chat` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `task_id` int
);

CREATE TABLE `message` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `chat_id` int,
  `author_id` int NOT NULL,
  `public_date` timestamp,
  `text` text
);

CREATE TABLE `review` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `task_id` int,
  `author_id` int,
  `executor_id` int,
  `text` text
);

CREATE TABLE `city` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lat` float,
  `long` float
);

-- ALTER TABLE `user_data` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
--
-- ALTER TABLE `user_settings` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
--
-- ALTER TABLE `user_photo` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
--
-- ALTER TABLE `user_specialization` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
--
-- ALTER TABLE `user_specialization` ADD FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
--
-- ALTER TABLE `task` ADD FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);
--
-- ALTER TABLE `task` ADD FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
--
-- ALTER TABLE `task` ADD FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`);
--
-- ALTER TABLE `task_file` ADD FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
--
-- ALTER TABLE `task_respond` ADD FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
--
-- ALTER TABLE `task_respond` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
--
-- ALTER TABLE `chat` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
--
-- ALTER TABLE `chat` ADD FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
--
-- ALTER TABLE `message` ADD FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`);
--
-- ALTER TABLE `review` ADD FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
--
-- ALTER TABLE `review` ADD FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);
--
-- ALTER TABLE `review` ADD FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`);
