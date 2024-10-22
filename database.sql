CREATE DATABASE furniking;

USE furniking;

/*
This table sets the types available for the furnitures (ex. chair, sofa etc.).
*/
CREATE TABLE furniture_type(
    type_id INT NOT NULL AUTO_INCREMENT,
    type_name VARCHAR(40) NOT NULL,
    PRIMARY KEY(type_id),
    UNIQUE(type_name)
)
;

INSERT INTO furniture_type (type_name) 
VALUES 
('chair'), -- 1
('table'), -- 2
('sofa'), -- 3
('bed'); -- 4
;
/*
This table provides adata about a particular furniture.
*/
CREATE TABLE furniture(
    furniture_id  INT NOT NULL AUTO_INCREMENT,
    furniture_name VARCHAR(60) NOT NULL,
    furniture_description VARCHAR(255) NOT NULL,
    furniture_type INT NOT NULL,
    FOREIGN KEY(furniture_type) REFERENCES furniture_type(type_id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY(furniture_id),
    UNIQUE (furniture_name)
)
;

INSERT INTO furniture (furniture_name, furniture_description, furniture_type) 
VALUES
('office chair', 'Ergonomic office chair with adjustable height', 1), -- 1
('dining table', 'Wooden dining table that seats six people', 2), -- 2
('leather sofa', 'Comfortable three-seater leather sofa', 3), -- 3
('king size bed', 'Spacious king size bed with memory foam mattress', 4), -- 4
('coffee table', 'Small glass coffee table for the living room', 2), -- 5
('recliner chair', 'Comfortable recliner chair with footrest', 1); -- 6
;
/*
This table stores the pictures of the furnitures.
A furniture may have more pictures, but one picture belongs only to one furniture.
*/
CREATE TABLE furniture_picture(
    picture_id INT NOT NULL AUTO_INCREMENT,
    furniture_id INT NOT NULL,
    FOREIGN KEY(furniture_id) REFERENCES furniture(furniture_id) ON UPDATE CASCADE ON DELETE CASCADE,
    picture_path VARCHAR(255) NOT NULL,
    PRIMARY KEY(picture_id),
    UNIQUE(picture_path)
)
;
INSERT INTO furniture_picture(furniture_id, picture_path)
VALUES 
(1, './resources/furniture_pictures/office-chair.jpg'),
(2, './resources/furniture_pictures/dining-table.jpg'),
(3,'./resources/furniture_pictures/leather-sofa.jpg'),
(4,'./resources/furniture_pictures/king-size-bed.jpg'),
(5,'./resources/furniture_pictures/coffee-table.jpg'),
(6,'./resources/furniture_pictures/recliner-chair.jpg')
;
/*
This table provides data about a specific unit of a particular furniture.
Among the data there is the import date of a unit, which could come in handy
when checking what are the new arrivals. 
*/
CREATE TABLE furniture_unit(
    unit_id INT NOT NULL AUTO_INCREMENT,
    furniture_data INT NOT NULL,
    FOREIGN KEY(furniture_data) REFERENCES furniture(furniture_id) ON UPDATE CASCADE ON DELETE CASCADE,
    unit_code CHAR(10) NOT NULL,
    import_date DATE NOT NULL,
    PRIMARY KEY(unit_id),
    UNIQUE(unit_code)
)
;

INSERT INTO furniture_unit (furniture_data, unit_code, import_date) 
VALUES
(1, 'UNIT000001', '2024-01-15'),
(1, 'UNIT000002', '2024-01-16'),
(1, 'UNIT000003', '2024-01-17'),
(1, 'UNIT000004', '2024-01-18'),
(2, 'UNIT000005', '2024-01-19'),
(2, 'UNIT000006', '2024-01-20'),
(2, 'UNIT000007', '2024-01-21'),
(2, 'UNIT000008', '2024-01-22'),
(3, 'UNIT000009', '2024-01-23'),
(3, 'UNIT000010', '2024-01-24'),
(3, 'UNIT000011', '2024-01-25'),
(3, 'UNIT000012', '2024-01-26'),
(4, 'UNIT000013', '2024-01-27'),
(4, 'UNIT000014', '2024-01-28'),
(4, 'UNIT000015', '2024-01-29'),
(4, 'UNIT000016', '2024-01-30'),
(2, 'UNIT000017', '2024-02-01'),
(2, 'UNIT000018', '2024-02-02'),
(3, 'UNIT000019', '2024-02-03'),
(3, 'UNIT000020', '2024-02-04'),
(1, 'UNIT000021', '2024-02-05'),
(1, 'UNIT000022', '2024-02-06'),
(1, 'UNIT000023', '2024-02-07'),
(1, 'UNIT000024', '2024-02-08'),
(4, 'UNIT000025', '2024-02-09'),
(4, 'UNIT000026', '2024-02-10'),
(3, 'UNIT000027', '2024-02-11'),
(2, 'UNIT000028', '2024-02-12'),
(4, 'UNIT000029', '2024-02-13'),
(4, 'UNIT000030', '2024-02-14');
/*
This table stores the orders. 
If the arrival date of the order is not null and the returned status is null, 
the order is deemed succesful.
*/
CREATE TABLE furniture_order(
    unit_id INT NOT NULL,
    FOREIGN KEY(unit_id) REFERENCES furniture_unit(unit_id) ON UPDATE CASCADE ON DELETE CASCADE,
    user_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES user(user_id) ON UPDATE CASCADE ON DELETE CASCADE,
    departure_date DATE NOT NULL, 
    arrival_date  DATE NULL,
    returned BIT NULL,
    PRIMARY KEY(unit_id, user_id)
)
;
INSERT INTO `furniture_order` (unit_id, user_id, departure_date, arrival_date, returned) 
VALUES
(3, 201, '2024-02-01', '2024-02-10', 1),
(7, 202, '2024-02-03', NULL, NULL),
(15, 203, '2024-02-05', '2024-02-15', 0),
(9, 204, '2024-02-08', '2024-02-18', 1),
(12, 205, '2024-02-10', NULL, NULL),
(20, 206, '2024-02-12', '2024-02-22', 0),
(2, 207, '2024-02-14', NULL, NULL),
(25, 208, '2024-02-17', '2024-02-27', 1),
(5, 209, '2024-02-20', NULL, NULL),
(30, 210, '2024-02-22', '2024-03-01', 1)
;
/*
This table stores the prices of the orders througout time.
This table may be useful when checking the discounts.
*/
CREATE TABLE furniture_price(
    furniture_id INT NOT NULL,
    FOREIGN KEY(furniture_id) REFERENCES furniture(furniture_id) ON UPDATE CASCADE ON DELETE CASCADE, 
    begin_date DATE NOT NULL,
    end_date DATE NULL,
    price FLOAT(5,2) NOT NULL,
    PRIMARY KEY(furniture_id, begin_date)
)
;
INSERT INTO furniture_price (furniture_id, begin_date, end_date, price) 
VALUES 
    (1, '2023-01-01', '2023-05-31', 299.99),
    (1, '2023-06-01', NULL, 319.99),
    (2, '2023-01-01', NULL, 159.99),
    (3, '2023-01-01', '2023-03-31', 499.99),
    (3, '2023-04-01', '2023-08-31', 479.99), 
    (3, '2023-09-01', NULL, 529.99),
    (4, '2023-01-01', NULL, 199.99),
    (5, '2023-01-01', '2023-07-31', 649.99),
    (5, '2023-08-01', NULL, 699.99),
    (6, '2023-01-01', NULL, 89.99)
;
/*
This table stores data about a user.
*/
CREATE TABLE website_user(
    user_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(80) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_gender ENUM('female', 'male', 'other') NOT NULL, 
    user_email VARCHAR(255) NOT NULL,
    user_birthdate DATE NOT NULL,
    admin_status BIT NULL,
    PRIMARY KEY(user_id),
    UNIQUE(username, user_email)
)
;
INSERT INTO website_user (username, user_password, user_gender, user_email, user_birthdate, admin_status) 
VALUES 
('admin1', 'Admin@123', 'male', 'admin1@example.com', '1980-05-10', 1),
('alice_w', 'Alice@2023', 'female', 'alice_w@example.com', '1995-11-15', 0),
('bob_m', 'BobPassword', 'male', 'bob_m@example.com', '1992-07-21', 0),
('charlie_f', 'Charlie@456', 'male', 'charlie_f@example.com', '1987-03-04', 0),
('diana_g', 'Diana123', 'female', 'diana_g@example.com', '1999-01-12', 0),
('eva_q', 'EvaPass789', 'female', 'eva_q@example.com', '1994-09-18', 0),
('frank_h', 'Frankie@123', 'male', 'frank_h@example.com', '1985-06-23', 0),
('gina_p', 'GinaBest123', 'female', 'gina_p@example.com', '2000-12-05', 0),
('henry_t', 'HenryTheMan', 'male', 'henry_t@example.com', '1990-10-30', 0),
('isla_k', 'IslaPassword', 'other', 'isla_k@example.com', '1996-04-22', 0)
;
/*
This table stores the ratings given by the users to a furniture.
A furniture may recieve multiple ratings by a user, according to 
how well the orders went.
*/
CREATE TABLE furniture_rating(
    rating_id INT NOT NULL AUTO_INCREMENT
    user_id  INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES user(user_id) ON UPDATE CASCADE ON DELETE CASCADE, 
    furniture INT NOT NULL,
    FOREIGN KEY(furniture) REFERENCES furniture(furniture_id) ON UPDATE CASCADE ON DELETE CASCADE, 
    rating_date DATE NOT NULL,
    vote INT NOT NULL,
    PRIMARY KEY (rating_id)
)
;
INSERT INTO furniture_rating (user_id, furniture, rating_date, vote)
VALUES
(1, 1, '2024-02-01', 4),
(2, 1, '2024-02-02', 5),
(3, 1, '2024-02-03', 3),
(4, 2, '2024-02-04', 2),
(5, 3, '2024-02-05', 5),
(6, 2, '2024-02-06', 1),
(7, 5, '2024-02-07', 4),
(8, 6, '2024-02-08', 3),
(9, 6, '2024-02-09', 4),
(10, 2, '2024-02-10', 5),
(1, 2, '2024-02-11', 2),
(2, 4, '2024-02-12', 3),
(3, 1, '2024-02-13', 1),
(4, 1, '2024-02-14', 5),
(5, 1, '2024-02-15', 2),
(6, 5, '2024-02-16', 4),
(7, 3, '2024-02-17', 3),
(8, 2, '2024-02-18', 5),
(9, 5, '2024-02-19', 2),
(10, 6, '2024-02-20', 1)
;
-- Queries --
/*
This query returns the number of product units registred that are NOT included in the orders 
*/
--FUNZIONA!
SELECT COUNT(furniture.furniture_name)
FROM furniture_unit
INNER JOIN furniture ON furniture.furniture_id = furniture_unit.furniture_data
WHERE furniture.furniture_name = 'Dining Table' AND furniture_unit.unit_id NOT IN
    ( SELECT unit_id 
    FROM furniture_order)
;
/*
or

SELECT name 
FROM table2 
WHERE NOT EXISTS 
    (SELECT * 
    FROM table1 
    WHERE table1.name = table2.name)
*/

/*
This query returns the average rating of a furniture
*/
SELECT AVG(rating.vote)
FROM rating 
INNER JOIN furniture ON rating.furniture = furniture.furniture_id
WHERE furniture.furniture_name = 'Dining Table';
;
/*
This query returns the most recent and second most recent price of a furniture
This may be useful for calculating discounts
*/
SELECT furniture_price.price
FROM furniture_price
INNER JOIN furniture ON furniture_price.furniture_id = furniture.furniture_id
WHERE furniture.furniture_name = 'Dining Table';
ORDER BY furniture_price.begin_date DESC
LIMIT 2
;


