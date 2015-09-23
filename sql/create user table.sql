CREATE TABLE IF NOT EXISTS umpire_users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);


INSERT INTO umpire_users (id, user_name, user_email, user_password) VALUES (1, 'bb', 'bb@bb.com', MD5('aa'));

