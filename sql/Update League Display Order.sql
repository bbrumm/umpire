SELECT *
from report_column_lookup_display;

SELECT *
FROM report_column;

ALTER TABLE report_column ADD COLUMN display_order INT(11) DEFAULT 1;

/*Order should be 154, 152, 153, CDFL, None */
UPDATE report_column
SET display_order = 2
WHERE report_column_id = 152;

UPDATE report_column
SET display_order = 3
WHERE report_column_id = 153;

UPDATE report_column
SET display_order = 5
WHERE report_column_id = 155;