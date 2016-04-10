ALTER TABLE mv_report_05 ADD COLUMN region VARCHAR(100);

ALTER TABLE mv_summary_staging ADD COLUMN region VARCHAR(100);

ALTER TABLE mv_report_06_staging ADD COLUMN region VARCHAR(100);

ALTER TABLE mv_report_04 ADD COLUMN `Boundary|Seniors|CDFNL` INT(11);