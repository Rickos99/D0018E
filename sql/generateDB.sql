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

CREATE TABLE `ORDER_ITEMS`(
    `order_id` int UNSIGNED,
    `product_id` int UNSIGNED,
    `quantity` int UNSIGNED NOT NULL,
    `price` int UNSIGNED NOT NULL,
    `vat` int UNSIGNED NOT NULL,
    `discount` int UNSIGNED NOT NULL,
    FOREIGN KEY(order_id) REFERENCES ORDERS(order_id),
    FOREIGN KEY(product_id) REFERENCES PRODUCTS(prod_id)
);

CREATE TABLE `SHOPPING_CARTS`(
    `cart_id` int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `uid` int UNSIGNED UNIQUE,
    `changed_at` datetime NOT NULL,
    FOREIGN KEY(uid) REFERENCES USERS(uid)
);

CREATE TABLE `CART_ITEMS`(
    `product_id` int UNSIGNED NOT NULL,
    `cart_id` int UNSIGNED NOT NULL,
    `quantity` int UNSIGNED NOT NULL,
    FOREIGN KEY(cart_id) REFERENCES SHOPPING_CARTS(cart_id),
    FOREIGN KEY(product_id) REFERENCES PRODUCTS(prod_id),
    CONSTRAINT PK_CartItem PRIMARY KEY (product_id, cart_id)
);

CREATE TABLE `REVIEWS`(
    `uid` int unsigned NOT NULL,
    `product_id` int unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `comment` varchar(8000),
    `rating` int UNSIGNED NOT NULL,
    `recommends` bool NOT NULL,
    `created_at` datetime DEFAULT NOW(),
    FOREIGN KEY(uid) REFERENCES USERS(uid),
    FOREIGN KEY(product_id) REFERENCES PRODUCTS(prod_id),
    CONSTRAINT PK_Reviews PRIMARY KEY (uid, product_id)
);

CREATE TABLE `SUPPORT_TICKETS` (
    `ticket_id` int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `order_id` int UNSIGNED NOT NULL,
    `subject` varchar(255) NOT NULL,
    `body` varchar(2000) NOT NULL,
    `isReturn` boolean NOT NULL,
    `isResolved` boolean NOT NULL,
    `created_at` datetime NOT NULL,
    `last_updated` datetime NOT NULL,
    FOREIGN KEY (order_id) REFERENCES ORDERS(order_id)
);

CREATE TABLE `TICKET_RESPONSES` (
    `ticket_id` int UNSIGNED NOT NULL,
    `user_id` int UNSIGNED NOT NULL,
    `body` varchar(1000) NOT NULL,
    `created_at` datetime NOT NULL DEFAULT NOW(),
    FOREIGN KEY (ticket_id) REFERENCES SUPPORT_TICKETS(ticket_id),
    FOREIGN KEY (user_id) REFERENCES USERS(uid),
    INDEX (ticket_id)
);

CREATE TABLE `WISHLIST_ITEMS` (
    `uid` int UNSIGNED NOT NULL,
    `pid` int UNSIGNED NOT NULL,
    FOREIGN KEY (uid) REFERENCES USERS(uid),
    FOREIGN KEY (pid) REFERENCES PRODUCTS(prod_id),
    PRIMARY KEY (uid, pid)
);