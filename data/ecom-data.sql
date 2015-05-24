SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

INSERT INTO `addresses` VALUES(1, 1, '124 Main Street', '', 'Ourtown', 'NY', '12345', '', '', 1);
INSERT INTO `addresses` VALUES(2, 1, '9873 Faraway Ave', '', 'Oakland', 'CA', '94708', '', '', 0);
INSERT INTO `addresses` VALUES(5, 1, '567 Over Street', 'Apt. 3', 'Brooklyn', 'NY', '11215', '', '', 0);
INSERT INTO `addresses` VALUES(4, 4, '456 Park Ave', '', 'New York', 'New York', '10008', '', '', 0);
INSERT INTO `addresses` VALUES(6, 1, '5023 Generic Street', '', 'San Francisco', 'CA', '94608', '', '', 0);

INSERT INTO `cart` VALUES(1, 4, 0);
INSERT INTO `cart` VALUES(1, 2, 0);

INSERT INTO `customers` VALUES(1, 'Robyn', 'Overstreet', 'robynover@gmail.com', '$2a$08$M6IjVVfu3KpAKN8QbaEKV.D1lvme8jquRpKrMLEpSDU23ZOxF6NqS', 0);
INSERT INTO `customers` VALUES(2, 'Opal', 'Overstreet', 'robyn@nyu.edu', '$2a$08$DQVqaaYS5DT54EWVqkG68.4xUR8CMXH4DEm4IpuuLl8ovv65s5yFO', 0);
INSERT INTO `customers` VALUES(3, 'Ma', 'Donna', 'madonna@madonna.com', '$2a$08$nPvdWhoymG6bmkOZ2DqR6equfNlYv1HDFSiDJOK/0U7ortYK6b0JC', 0);
INSERT INTO `customers` VALUES(4, 'Funny', 'Face', 'face@gmail.com', '$2a$08$dBP/PwFDNBiSiog01lY/6ukOBJRpxFxxvA/6BfjeZtthe3XzPCdne', 0);
INSERT INTO `customers` VALUES(5, 'James', 'Dean', 'jd@yahoo.com', '$2a$08$lmbvjL3QeyBosVzk.CzfL.5oQxMs8DGZl4WSgPik.ZO5dq7C8Cm0u', 0);

INSERT INTO `orders` VALUES(5, 1, 1334290866, 0, 0, 2);
INSERT INTO `orders` VALUES(6, 4, 1334609729, 0, 0, 1);
INSERT INTO `orders` VALUES(7, 4, 1334609901, 4, 0, 1);
INSERT INTO `orders` VALUES(8, 1, 1335125172, 1, 179.97, 3);
INSERT INTO `orders` VALUES(9, 1, 1335467095, 1, 33.96, 4);
INSERT INTO `orders` VALUES(10, 1, 1335467144, 1, 0, 0);
INSERT INTO `orders` VALUES(11, 1, 1335467543, 5, 547.99, 1);
INSERT INTO `orders` VALUES(12, 1, 1335468584, 2, 59.99, 1);
INSERT INTO `orders` VALUES(13, 1, 1335991701, 5, 24.99, 1);
INSERT INTO `orders` VALUES(14, 1, 1335993160, 6, 128.95, 5);

INSERT INTO `order_product` VALUES(6, 4, 0);
INSERT INTO `order_product` VALUES(5, 2, 0);
INSERT INTO `order_product` VALUES(5, 4, 0);
INSERT INTO `order_product` VALUES(7, 4, 0);
INSERT INTO `order_product` VALUES(8, 2, 3);
INSERT INTO `order_product` VALUES(9, 5, 1);
INSERT INTO `order_product` VALUES(9, 8, 3);
INSERT INTO `order_product` VALUES(11, 4, 1);
INSERT INTO `order_product` VALUES(12, 2, 1);
INSERT INTO `order_product` VALUES(13, 5, 1);
INSERT INTO `order_product` VALUES(14, 2, 2);
INSERT INTO `order_product` VALUES(14, 8, 3);

INSERT INTO `products` VALUES(2, 'VH3456', 'Cool Shoes', '', 59.99, 0);
INSERT INTO `products` VALUES(5, 'WXZ345', 'Super Widget', 'This is a really super widget!', 24.99, 9);
INSERT INTO `products` VALUES(4, 'WD564', 'Washer', '', 547.99, 0);
INSERT INTO `products` VALUES(7, 'DSA0987', 'Bottle', '', 4, 54);
INSERT INTO `products` VALUES(8, 'DSA0753', 'Box', '', 2.99, 2);
INSERT INTO `products` VALUES(9, 'CAT9483', 'Cat food', 'Yummy for cats.', 7.59, 45);
INSERT INTO `products` VALUES(10, 'DOG9483', 'Dog food', 'Dogs love it. Then again, dogs will eat anything.', 7.59, 45);
