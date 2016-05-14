SELECT * FROM umpire.imported_files
order by imported_file_id;

delete from imported_files where imported_file_id < 60;

ALTER TABLE imported_files ADD PRIMARY KEY (imported_file_id);

ALTER TABLE imported_files MODIFY COLUMN imported_file_id INT(11) AUTO_INCREMENT;