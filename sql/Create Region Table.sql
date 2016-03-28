/*DROP TABLE region;*/

CREATE TABLE region (
  id INT(11) NOT NULL PRIMARY KEY,
  region_name VARCHAR(50) NOT NULL

);

INSERT INTO region(id, region_name) VALUES (1, 'Geelong');
INSERT INTO region(id, region_name) VALUES (2, 'Colac');



ALTER TABLE league ADD COLUMN region_id INT(11);

UPDATE league
SET region_id = 1;

ALTER TABLE league ADD CONSTRAINT fk_league_regionid FOREIGN KEY (region_id) REFERENCES region(id);

