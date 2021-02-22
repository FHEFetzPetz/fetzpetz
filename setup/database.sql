SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `address` (
                           `id` int(11) NOT NULL,
                           `firstname` varchar(150) NOT NULL,
                           `lastname` varchar(150) NOT NULL,
                           `street` varchar(150) NOT NULL,
                           `zip` varchar(15) NOT NULL,
                           `city` varchar(100) NOT NULL,
                           `state` varchar(100) DEFAULT NULL,
                           `country` varchar(2) NOT NULL,
                           `phone_number` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `administration_access` (
                                         `id` int(11) NOT NULL,
                                         `user_id` int(11) NOT NULL,
                                         `created_by` int(11) DEFAULT NULL,
                                         `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `category` (
                            `id` int(11) NOT NULL,
                            `created_by` int(11) NOT NULL,
                            `name` varchar(100) NOT NULL,
                            `description` text NOT NULL,
                            `active` tinyint(1) DEFAULT 1,
                            `parent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `order_item` (
                              `id` int(11) NOT NULL,
                              `order_id` int(11) NOT NULL,
                              `product_id` int(11) NOT NULL,
                              `amount` int(11) NOT NULL,
                              `cost_per_item` decimal(7,2) NOT NULL,
                              `item_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `order_object` (
                                `id` int(11) NOT NULL,
                                `user_id` int(11) NOT NULL,
                                `payment_reference_id` int(11) NOT NULL,
                                `shipping_address_id` int(11) NOT NULL,
                                `billing_address_id` int(11) DEFAULT NULL,
                                `order_status` varchar(100) DEFAULT 'pending_payment',
                                `shipment_data` text DEFAULT NULL,
                                `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `payment_reference` (
                                     `id` int(11) NOT NULL,
                                     `payment_method` varchar(100) NOT NULL,
                                     `payment_data` text DEFAULT NULL,
                                     `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `product` (
                           `id` int(11) NOT NULL,
                           `created_by` int(11) NOT NULL,
                           `name` varchar(100) NOT NULL,
                           `description` text NOT NULL,
                           `image` varchar(100) NOT NULL,
                           `cost_per_item` decimal(7,2) NOT NULL,
                           `active` tinyint(1) DEFAULT 1,
                           `search_tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `product_category` (
                                    `id` int(11) NOT NULL,
                                    `product_id` int(11) NOT NULL,
                                    `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user` (
                        `id` int(11) NOT NULL,
                        `firstname` varchar(150) NOT NULL,
                        `lastname` varchar(150) NOT NULL,
                        `password_hash` varchar(100) NOT NULL,
                        `email` varchar(100) NOT NULL,
                        `email_verified` tinyint(1) DEFAULT 0,
                        `email_verification_hash` varchar(20) DEFAULT NULL,
                        `active` tinyint(1) DEFAULT 1,
                        `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wishlist_item` (
                                 `id` int(11) NOT NULL,
                                 `product_id` int(11) NOT NULL,
                                 `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `address`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `administration_access`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_administration_access_user_id` (`user_id`),
    ADD KEY `fk_administration_access_user_created_by` (`created_by`);

ALTER TABLE `category`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_category_user_id` (`created_by`),
    ADD KEY `fk_category_parent` (`parent`);

ALTER TABLE `order_item`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_order_item_order_id` (`order_id`),
    ADD KEY `fk_order_item_product_id` (`product_id`);

ALTER TABLE `order_object`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_order_user_id` (`user_id`),
    ADD KEY `fk_order_payment_reference_id` (`payment_reference_id`),
    ADD KEY `fk_order_shipping_address_id` (`shipping_address_id`),
    ADD KEY `fk_order_billing_address_id` (`billing_address_id`);

ALTER TABLE `payment_reference`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `product`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_product_created_by` (`created_by`);

ALTER TABLE `product_category`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_product_category_product_id` (`product_id`),
    ADD KEY `fk_product_category_category_id` (`category_id`);

ALTER TABLE `user`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `wishlist_item`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_wishlist_product_id` (`product_id`),
    ADD KEY `fk_wishlist_user_id` (`user_id`);


ALTER TABLE `address`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `administration_access`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `category`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `order_item`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `order_object`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `payment_reference`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `product`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `product_category`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wishlist_item`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `administration_access`
    ADD CONSTRAINT `fk_administration_access_user_created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
    ADD CONSTRAINT `fk_administration_access_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `category`
    ADD CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent`) REFERENCES `category` (`id`),
    ADD CONSTRAINT `fk_category_user_id` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

ALTER TABLE `order_item`
    ADD CONSTRAINT `fk_order_item_order_id` FOREIGN KEY (`order_id`) REFERENCES `order_object` (`id`),
    ADD CONSTRAINT `fk_order_item_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `order_object`
    ADD CONSTRAINT `fk_order_billing_address_id` FOREIGN KEY (`billing_address_id`) REFERENCES `address` (`id`),
    ADD CONSTRAINT `fk_order_payment_reference_id` FOREIGN KEY (`payment_reference_id`) REFERENCES `payment_reference` (`id`),
    ADD CONSTRAINT `fk_order_shipping_address_id` FOREIGN KEY (`shipping_address_id`) REFERENCES `address` (`id`),
    ADD CONSTRAINT `fk_order_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `product`
    ADD CONSTRAINT `fk_product_created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

ALTER TABLE `product_category`
    ADD CONSTRAINT `fk_product_category_category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
    ADD CONSTRAINT `fk_product_category_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `wishlist_item`
    ADD CONSTRAINT `fk_wishlist_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
    ADD CONSTRAINT `fk_wishlist_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;