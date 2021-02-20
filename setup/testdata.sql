START TRANSACTION;
SET FOREIGN_KEY_CHECKS=0;

INSERT INTO `address` (`id`, `firstname`, `lastname`, `street`, `zip`, `city`, `state`, `country`, `phone_number`) VALUES
(1, 'Mike', 'Schroder', 'Waßmannsdorfer Chaussee 94', '20251', 'Hamburg Alsterdorf', '', 'DE', '1234567890'),
(2, 'Priamus', 'Puddifoot', '3351 Elmdrive', '10035', 'New York', '', 'US', '1234567890'),
(3, 'Ashley', 'Denton', 'Kieler Straße 14', '94108', 'Wegscheid', '', 'DE', '1234567890');

INSERT INTO `administration_access` (`id`, `user_id`, `created_by`, `created_at`) VALUES
(1, 1, NULL, '2021-02-20 16:02:08');

INSERT INTO `category` (`id`, `created_by`, `name`, `description`, `active`, `parent`) VALUES
(1, 1, 'Clothes', 'Clothes', 1, NULL),
(2, 1, 'Indoor & Outdoor', 'Indoor & Outdoor', 1, NULL),
(3, 1, 'Accessiores', 'Accessiores', 1, NULL),
(4, 1, 'T-Shirts', 'T-Shirts', 1, 1),
(5, 1, 'Jackets', 'Jackets', 1, 1),
(6, 1, 'Pants', 'Pants', 1, 1),
(7, 1, 'Shoes', 'Shoes', 1, 1),
(8, 1, 'Dresses', 'Dresses', 1, 1),
(9, 1, 'Capes', 'Capes', 1, 1),
(10, 1, 'Decoration', 'Decoration', 1, 2),
(11, 1, 'Games', 'Games', 1, 2),
(12, 1, 'Equipment', 'Equipment', 1, 2),
(13, 1, 'Glasses', 'Glasses', 1, 3),
(14, 1, 'Hats', 'Hats', 1, 3),
(15, 1, 'Body Paint', 'Body Paint', 1, 3),
(16, 1, 'Masks', 'Masks', 1, 3);

INSERT INTO `order_item` (`id`, `order_id`, `product_id`, `amount`, `cost_per_item`, `item_data`) VALUES
(1, 1, 1, 1, '129.99', NULL),
(2, 1, 2, 1, '199.99', NULL),
(3, 1, 4, 1, '49.99', NULL),
(4, 2, 12, 5, '25.99', NULL),
(5, 2, 21, 3, '9.99', NULL),
(6, 3, 19, 1, '149.99', NULL),
(7, 3, 23, 12, '99999.99', NULL);

INSERT INTO `order_object` (`id`, `user_id`, `payment_reference_id`, `shipping_address_id`, `billing_address_id`, `order_status`, `shipment_data`, `created_at`) VALUES
(1, 2, 1, 1, NULL, 'shipped', NULL, '2021-02-20 18:07:33'),
(2, 3, 2, 2, NULL, 'arrived', NULL, '2021-02-20 18:10:19'),
(3, 4, 3, 3, NULL, 'refunded', NULL, '2021-02-20 18:12:20');

INSERT INTO `payment_reference` (`id`, `payment_method`, `payment_data`, `created_at`) VALUES
(1, 'sofort', NULL, '2021-02-20 18:07:33'),
(2, 'creditcard', NULL, '2021-02-20 18:10:19'),
(3, 'paypal', NULL, '2021-02-20 18:12:20');

INSERT INTO `product` (`id`, `created_by`, `name`, `description`, `image`, `cost_per_item`, `active`, `search_tags`) VALUES
(1, 1, 'Hyperion Jacket', 'Feel like a fucking godness in this glorious jacket!\r\nBased on the greek titan of the light \"Hyperion\". ', '/assets/upload/20022021-603127a991f98.jpg', '129.99', 1, 'a:0:{}'),
(2, 1, 'Aphrodite Jacket', 'Attract everyone like a light a moth. ', '/assets/upload/20022021-603128e04dc99.jpg', '199.99', 1, 'a:0:{}'),
(3, 1, 'Medusa Dress', 'Turn everybody into stone while they look at you in amazement.', '/assets/upload/20022021-6031296948fff.jpg', '199.99', 1, 'a:0:{}'),
(4, 1, 'Artemis Dress', 'Everybody will choose you in this wonderful piece of dress.', '/assets/upload/20022021-603129f3783e3.jpg', '49.99', 1, 'a:0:{}'),
(5, 1, 'Apollon Pants', 'Awake your youth and energy. Not only for the spring!', '/assets/upload/20022021-60312a82a31bc.jpg', '99.99', 1, 'a:0:{}'),
(6, 1, 'Themis Pants', 'Bring justice to the dancefloor with these bad boys on.', '/assets/upload/20022021-60312b2e5d87d.jpg', '59.99', 1, 'a:0:{}'),
(7, 1, 'Dionysus T-Shirt', 'Get high on ecstasy without the drugs part. Stay safe.', '/assets/upload/20022021-60312bc69c92a.jpg', '149.99', 1, 'a:0:{}'),
(8, 1, 'Phorkys T-Shirt', 'Flow with the rhythm.', '/assets/upload/20022021-60312cf88b4e0.gif', '29.99', 1, 'a:0:{}'),
(9, 1, 'Hermes Shoes', 'Fly high and fast.', '/assets/upload/20022021-60312d789b241.jpg', '149.99', 1, 'a:0:{}'),
(10, 1, 'Athene Shoes', 'Avoid all your problems with these sick shoes.', '/assets/upload/20022021-60312e038e6e0.jpg', '79.99', 1, 'a:0:{}'),
(11, 1, 'Anemoi Cape', 'Feel like Superman, flying around the world with this cape.', '/assets/upload/20022021-60312e9eb1c5d.jpg', '249.99', 1, 'a:0:{}'),
(12, 1, 'Glühwürfel', 'Things are getting heat up around these bad boys.', '/assets/upload/20022021-6031305572880.jpg', '25.99', 1, 'a:0:{}'),
(13, 1, 'LED Balloons', 'Pump up your environment. ', '/assets/upload/20022021-603130ea68f1a.jpg', '15.99', 1, 'a:0:{}'),
(14, 1, 'Glasses', 'Drink fancy stuff in fancy glasses. You fancy b*tch.', '/assets/upload/20022021-60313177ad57d.jpg', '15.99', 1, 'a:0:{}'),
(15, 1, 'LED Dart', 'Hole-In-One, but with style.', '/assets/upload/20022021-60313242df9b1.jpg', '49.99', 1, 'a:0:{}'),
(16, 1, 'Pin-Ball', 'Travel to a far beyond planet and... PLAY PIN-BALL!', '/assets/upload/20022021-603132af3e09e.jpg', '499.99', 1, 'a:0:{}'),
(17, 1, 'Disco Circum3000fx HDXD', '\"You spin my head right round, right round - ...\"', '/assets/upload/20022021-6031332c6fc0f.jpg', '89.99', 1, 'a:0:{}'),
(18, 1, 'Disco Flex X900 new gen.', 'Crystallize your eyes! ', '/assets/upload/20022021-603133a521a8a.jpg', '89.99', 1, 'a:0:{}'),
(19, 1, 'Devils Mask', 'Awake your inner demon! ', '/assets/upload/20022021-603133ebd80b2.jpg', '149.99', 1, 'a:0:{}'),
(20, 1, 'Elon Mask', 'Go stonks, like Doge, skyrocket to Mars!', '/assets/upload/20022021-6031344f3cc85.jpeg', '39.99', 1, 'a:0:{}'),
(21, 1, 'Fluorescent Bodypaint', '\"Paint me like one of your french girls, Jake..\"', '/assets/upload/20022021-603134b94e6f1.jpg', '9.99', 1, 'a:0:{}'),
(22, 1, 'Neon Paint', 'Draw your world like you want!', '/assets/upload/20022021-6031351fe8e3f.jpg', '9.99', 1, 'a:0:{}'),
(23, 1, 'Hugo', 'Steal the show and the girls ;)', '/assets/upload/20022021-6031368317d22.jpg', '99999.99', 1, 'a:0:{}');

INSERT INTO `product_category` (`id`, `product_id`, `category_id`) VALUES
(1, 1, 1),
(2, 1, 5),
(3, 2, 1),
(4, 2, 5),
(5, 3, 1),
(6, 3, 8),
(7, 4, 1),
(8, 4, 8),
(9, 5, 1),
(10, 5, 6),
(11, 6, 1),
(12, 6, 6),
(13, 7, 1),
(14, 7, 4),
(15, 8, 1),
(16, 8, 4),
(17, 9, 1),
(18, 9, 7),
(19, 11, 1),
(20, 11, 9),
(21, 12, 2),
(22, 12, 12),
(23, 13, 2),
(24, 13, 10),
(25, 13, 12),
(26, 10, 1),
(27, 10, 7),
(28, 14, 2),
(29, 14, 10),
(30, 14, 12),
(31, 15, 2),
(32, 15, 11),
(33, 16, 2),
(34, 16, 11),
(35, 17, 3),
(36, 17, 13),
(37, 18, 3),
(38, 18, 13),
(39, 19, 3),
(40, 19, 16),
(41, 20, 3),
(42, 20, 16),
(43, 21, 3),
(44, 21, 15),
(45, 22, 3),
(46, 22, 15),
(47, 23, 3),
(48, 23, 14);

INSERT INTO `user` (`id`, `firstname`, `lastname`, `password_hash`, `email`, `email_verified`, `email_verification_hash`, `active`, `created_at`) VALUES
(1, 'Admin', 'Istrator', '$2y$10$VoNaok9XYT7M/vDfgtACOO9APBO9HkblqslKhOd7RiYhEa/3aSwYu', 'info@fh-erfurt.de', 1, NULL, 1, '2021-02-20 16:02:08'),
(2, 'Mike', 'Schroder', '$2y$10$XM6LydCXw8WwQ1PY2xq3HehQ9H07cW5EDsbcJ8XOfj3RRUZyMQ2o2', 'mikeschroder@gmail.com', 0, NULL, 1, '2021-02-20 18:05:43'),
(3, 'Priamus', 'Puddifoot', '$2y$10$ZP5d.ObJjPLi5ctYxEPuW.lt1ByT583oxjpjUwoZkBR31G/xWTFD6', 'priamuspuddifoot@gmail.com', 0, NULL, 1, '2021-02-20 18:08:53'),
(4, 'Ashley', 'Denton', '$2y$10$bJW6.9zBsMMe3h1lHP6upOTPFhATqlfJ8JBuCd9TZs51g93wZjAJ.', 'ashleydenton@gmail.com', 0, NULL, 0, '2021-02-20 18:11:15');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;