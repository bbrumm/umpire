ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Birregurra` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Lorne` INT(11) DEFAULT NULL;

ALTER TABLE mv_report_02 ADD COLUMN `Seniors|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_02 ADD COLUMN `Reserves|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_02 ADD COLUMN `Under 17.5|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_02 ADD COLUMN `Under 14.5|CDFNL` INT(11) DEFAULT NULL;

ALTER TABLE mv_report_03 ADD COLUMN `No Senior Boundary|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_03 ADD COLUMN `No Senior Goal|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_03 ADD COLUMN `No Reserve Goal|CDFNL` INT(11) DEFAULT NULL;

ALTER TABLE mv_report_04 ADD COLUMN `Boundary|Colts|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Boundary|Reserves|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Boundary|Under 17.5|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Boundary|Under 14.5|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Field|Seniors|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Field|Reserves|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Field|Under 17.5|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Field|Under 14.5|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Goal|Seniors|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Goal|Reserves|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Goal|Under 17.5|CDFNL` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_04 ADD COLUMN `Goal|Under 14.5|CDFNL` INT(11) DEFAULT NULL;


ALTER TABLE mv_report_05 ADD COLUMN CDFNL varchar(200);