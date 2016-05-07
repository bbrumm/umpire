ALTER TABLE match_import ADD COLUMN boundary_umpire_5 VARCHAR(200);
ALTER TABLE match_import ADD COLUMN boundary_umpire_6 VARCHAR(200);

ALTER TABLE match_staging ADD COLUMN appointments_boundary5_first VARCHAR(200);
ALTER TABLE match_staging ADD COLUMN appointments_boundary5_last VARCHAR(200);
ALTER TABLE match_staging ADD COLUMN appointments_boundary6_first VARCHAR(200);
ALTER TABLE match_staging ADD COLUMN appointments_boundary6_last VARCHAR(200);