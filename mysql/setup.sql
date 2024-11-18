\W
DROP DATABASE IF EXISTS `student_passwords`;
CREATE DATABASE `student_passwords` DEFAULT CHARACTER SET utf8mb4;
USE `student_passwords`;

CREATE TABLE IF NOT EXISTS user_information (
    database_id        SMALLINT        NOT NULL AUTO_INCREMENT,
    first_name      VARCHAR(20)        NOT NULL,
    last_name       VARCHAR(20)        NOT NULL,
    email           VARCHAR(50)        NOT NULL,
    notes           VARCHAR(500)               ,
    PRIMARY KEY (database_id)
);

CREATE TABLE IF NOT EXISTS website (
    database_id        SMALLINT        NOT NULL AUTO_INCREMENT,
    website_name       VARCHAR(100)    NOT NULL,
    website_url        VARCHAR(200)    NOT NULL,
    PRIMARY KEY (database_id)
);

CREATE TABLE IF NOT EXISTS login_information (
    database_id        SMALLINT            NOT NULL AUTO_INCREMENT,
    username           VARCHAR(80)         NOT NULL,
    password           VARBINARY(250)      NOT NULL,
    time_created       DATETIME            DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (database_id)
);

SET block_encryption_mode = 'aes-256-cbc';
SET @key_str = UNHEX(SHA2('encryption_key', 256));
SET @init_vector = RANDOM_BYTES(16);

INSERT INTO user_information (first_name, last_name, email, notes) VALUES
    ('Steven', 'Behrens', 'steven.behrens@example.com','Home email'),
    ('Bob', 'Behrens', 'bob.behrens@example.com', 'Work email'),
    ('Brian', 'Behrens', 'brian.behrens@example.com', 'school email'),
    ('Susan', 'Behrens', 'susan.behrens@example.com', 'personal email'),
    ('Steven', 'Behrens', 'steven.behrens@example.com', 'Home email'),
    ('Bob', 'Behrens', 'bob.behrens@example.com', 'Work email'),
    ('Brian', 'Behrens', 'brian.behrens@example.com', 'school email'),
    ('Susan', 'Behrens', 'susan.behrens@example.com', 'personal email'),
    ('Steven', 'Behrens', 'steven.behrens@example.com', 'Home email'),
    ('Bob', 'Behrens', 'bob.behrens@example.com', 'Work email');

INSERT INTO website (website_name, website_url) VALUES
    ('Amazon', 'https://www.amazon.com'),
    ('Netflix', 'https://www.netflix.com'),
    ('Facebook', 'https://www.facebook.com'),
    ('Instagram', 'https://www.instagram.com'),
    ('DisneyPlus', 'https://www.disneyplus.com'),
    ('Frontier', 'https://www.frontier.com'),
    ('Snapchat', 'https://www.snapchat.com'),
    ('Bing', 'https://www.bing.com'),
    ('Redbull', 'https://www.redbull.com'),
    ('Hulu', 'https://www.hulu.com');

INSERT INTO login_information (username, password, time_created) VALUES
    ('steveb', AES_ENCRYPT('fortnite$', @key_str, @init_vector), '2024-04-15 10:30:00'),
    ('bobbyb', AES_ENCRYPT('work123$', @key_str, @init_vector), '2022-12-12 09:30:00'),
    ('brainiac', AES_ENCRYPT('mathmaster!$', @key_str, @init_vector), '2023-08-18 17:45:00'),
    ('susanb', AES_ENCRYPT('technology@@', @key_str, @init_vector), '2023-09-05 12:00:00'),
    ('stevenbehrens', AES_ENCRYPT('disneyisgreat!$', @key_str, @init_vector), '2024-04-15 10:30:00'),
    ('bobbyb', AES_ENCRYPT('internetisexpensive$', @key_str, @init_vector), '2022-12-12 09:30:00'),
    ('brainiac', AES_ENCRYPT('idontusethis!$', @key_str, @init_vector), '2023-08-18 17:45:00'),
    ('susanb', AES_ENCRYPT('nogoogle@@', @key_str, @init_vector), '2023-09-05 12:00:00'),
    ('steveb', AES_ENCRYPT('redbull!!!!$', @key_str, @init_vector), '2024-04-15 10:30:00'),
    ('bobbyb', AES_ENCRYPT('thisisalotofmoney$', @key_str, @init_vector), '2022-12-12 09:30:00');
