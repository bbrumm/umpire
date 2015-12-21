CREATE TABLE imported_files (
	imported_file_id INT(11) NOT NULL AUTO_INCREMENT,
	filename VARCHAR(500) NOT NULL,
	imported_datetime datetime NOT NULL,
	imported_user_id VARCHAR(200) DEFAULT NULL,
	PRIMARY KEY (imported_file_id)
);