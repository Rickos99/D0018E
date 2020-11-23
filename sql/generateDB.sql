CREATE SCHEMA `STORE` DEFAULT CHARACTER SET utf8;
USE `STORE`;

CREATE TABLE `PRODUCTS` (
	`prod_id` int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`name` varchar(256) NOT NULL,
    `description` text,
    `price` float NOT NULL,
    `vat` float NOT NULL,
    `balance` int NOT NULL
);

CREATE TABLE `USERS` (
	`uid` int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`full_name` varchar(128),
    `hashed_pwd` varchar(255) NOT NULL,
    `address` varchar(256),
    `phone` varchar(20),
    `email` varchar(256)
);

CREATE TABLE `ORDERS` (
	`order_id` int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `uid` int UNSIGNED,
    `status` text,
    `created_at` datetime NOT NULL,
    FOREIGN KEY(uid) REFERENCES USERS(uid)
);
