-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema umpire
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema umpire
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `umpire` DEFAULT CHARACTER SET latin1 ;
USE `umpire` ;

-- -----------------------------------------------------
-- Table `umpire`.`test_report`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`test_report` ;

CREATE TABLE IF NOT EXISTS `umpire`.`test_report` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NULL DEFAULT NULL,
  `last_name` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`age_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`age_group` ;

CREATE TABLE IF NOT EXISTS `umpire`.`age_group` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `age_group` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`division`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`division` ;

CREATE TABLE IF NOT EXISTS `umpire`.`division` (
  `ID` INT(11) NOT NULL,
  `division_name` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`age_group_division`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`age_group_division` ;

CREATE TABLE IF NOT EXISTS `umpire`.`age_group_division` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `age_group_id` INT(11) NULL DEFAULT NULL,
  `division_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_age_group_division`
    FOREIGN KEY (`division_id`)
    REFERENCES `umpire`.`division` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_age_group_id`
    FOREIGN KEY (`age_group_id`)
    REFERENCES `umpire`.`age_group` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 24
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_age_group_id_idx` ON `umpire`.`age_group_division` (`age_group_id` ASC);

CREATE INDEX `fk_age_group_division_idx` ON `umpire`.`age_group_division` (`division_id` ASC);


-- -----------------------------------------------------
-- Table `umpire`.`club`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`club` ;

CREATE TABLE IF NOT EXISTS `umpire`.`club` (
  `ID` INT(11) NOT NULL,
  `club_name` VARCHAR(100) NULL DEFAULT NULL,
  `abbreviation` VARCHAR(10) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`league`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`league` ;

CREATE TABLE IF NOT EXISTS `umpire`.`league` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `league_name` VARCHAR(100) NULL DEFAULT NULL COMMENT 'The name of a league of competition.',
  `sponsored_league_name` VARCHAR(100) NULL DEFAULT NULL COMMENT 'The full name of the league, including the sponsors name.',
  `short_league_name` VARCHAR(200) NULL DEFAULT NULL COMMENT 'The shorter name of the league, used for reports',
  `age_group_division_id` INT(11) NULL DEFAULT NULL COMMENT 'The division for an age group that this league belongs to.',
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_league_agd`
    FOREIGN KEY (`age_group_division_id`)
    REFERENCES `umpire`.`age_group_division` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 30
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_leage_agd_idx` ON `umpire`.`league` (`age_group_division_id` ASC);


-- -----------------------------------------------------
-- Table `umpire`.`season`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`season` ;

CREATE TABLE IF NOT EXISTS `umpire`.`season` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `season_year` INT(11) NULL DEFAULT NULL COMMENT 'The year that this season belongs to.',
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`competition_lookup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`competition_lookup` ;

CREATE TABLE IF NOT EXISTS `umpire`.`competition_lookup` (
  `ID` INT(11) NOT NULL,
  `competition_name` VARCHAR(100) NULL DEFAULT NULL COMMENT 'The competition name from the imported spreadsheet.',
  `season_id` INT(11) NULL DEFAULT NULL,
  `league_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_comp_league`
    FOREIGN KEY (`league_id`)
    REFERENCES `umpire`.`league` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comp_season`
    FOREIGN KEY (`season_id`)
    REFERENCES `umpire`.`season` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_comp_league_idx` ON `umpire`.`competition_lookup` (`league_id` ASC);

CREATE INDEX `fk_comp_season_idx` ON `umpire`.`competition_lookup` (`season_id` ASC);


-- -----------------------------------------------------
-- Table `umpire`.`dates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`dates` ;

CREATE TABLE IF NOT EXISTS `umpire`.`dates` (
  `ID` INT(11) NOT NULL,
  `saturday_date` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`ground`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`ground` ;

CREATE TABLE IF NOT EXISTS `umpire`.`ground` (
  `ID` INT(11) NOT NULL,
  `main_name` VARCHAR(100) NULL DEFAULT NULL COMMENT 'The common name for a ground.',
  `alternative_name` VARCHAR(100) NULL DEFAULT NULL COMMENT 'An alternative name for a ground, as there are multiple names for the same ground.',
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`round`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`round` ;

CREATE TABLE IF NOT EXISTS `umpire`.`round` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `round_number` INT(11) NULL DEFAULT NULL COMMENT 'The round number of the season.',
  `round_date` DATETIME NULL DEFAULT NULL COMMENT 'The date this round starts on.',
  `season_id` INT(11) NULL DEFAULT NULL COMMENT 'The season this round belongs to.',
  `league_id` INT(11) NULL DEFAULT NULL COMMENT 'The league this round belonds to.',
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_round_league`
    FOREIGN KEY (`league_id`)
    REFERENCES `umpire`.`league` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_season`
    FOREIGN KEY (`season_id`)
    REFERENCES `umpire`.`season` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2670
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_round_season_idx` ON `umpire`.`round` (`season_id` ASC);

CREATE INDEX `fk_round_league_idx` ON `umpire`.`round` (`league_id` ASC);


-- -----------------------------------------------------
-- Table `umpire`.`team`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`team` ;

CREATE TABLE IF NOT EXISTS `umpire`.`team` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `team_name` VARCHAR(100) NULL DEFAULT NULL COMMENT 'The team name within a club.',
  `club_id` INT(11) NULL DEFAULT NULL COMMENT 'The club that this team belongs to.',
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_team_club`
    FOREIGN KEY (`club_id`)
    REFERENCES `umpire`.`club` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 94
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_team_club_idx` ON `umpire`.`team` (`club_id` ASC);


-- -----------------------------------------------------
-- Table `umpire`.`match`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`match` ;

CREATE TABLE IF NOT EXISTS `umpire`.`match` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `round_id` INT(11) NULL DEFAULT NULL,
  `ground_id` INT(11) NULL DEFAULT NULL,
  `match_time` TIME NULL DEFAULT NULL,
  `home_team_id` INT(11) NULL DEFAULT NULL,
  `away_team_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_match_ground`
    FOREIGN KEY (`ground_id`)
    REFERENCES `umpire`.`ground` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_match_round`
    FOREIGN KEY (`round_id`)
    REFERENCES `umpire`.`round` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_match_team`
    FOREIGN KEY (`home_team_id`)
    REFERENCES `umpire`.`team` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_match_round_idx` ON `umpire`.`match` (`round_id` ASC);

CREATE INDEX `fk_match_ground_idx` ON `umpire`.`match` (`ground_id` ASC);

CREATE INDEX `fk_match_team_idx` ON `umpire`.`match` (`home_team_id` ASC);


-- -----------------------------------------------------
-- Table `umpire`.`match_import`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`match_import` ;

CREATE TABLE IF NOT EXISTS `umpire`.`match_import` (
  `ID` INT(11) NOT NULL,
  `season` INT(11) NULL DEFAULT NULL,
  `round` INT(11) NULL DEFAULT NULL,
  `date` VARCHAR(45) NULL DEFAULT NULL,
  `competition_name` VARCHAR(200) NULL DEFAULT NULL,
  `ground` VARCHAR(200) NULL DEFAULT NULL,
  `time` VARCHAR(200) NULL DEFAULT NULL,
  `home_team` VARCHAR(200) NULL DEFAULT NULL,
  `away_team` VARCHAR(200) NULL DEFAULT NULL,
  `field_umpire_1` VARCHAR(200) NULL DEFAULT NULL,
  `field_umpire_2` VARCHAR(200) NULL DEFAULT NULL,
  `field_umpire_3` VARCHAR(200) NULL DEFAULT NULL,
  `boundary_umpire_1` VARCHAR(200) NULL DEFAULT NULL,
  `boundary_umpire_2` VARCHAR(200) NULL DEFAULT NULL,
  `boundary_umpire_3` VARCHAR(200) NULL DEFAULT NULL,
  `boundary_umpire_4` VARCHAR(200) NULL DEFAULT NULL,
  `goal_umpire_1` VARCHAR(200) NULL DEFAULT NULL,
  `goal_umpire_2` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`match_staging`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`match_staging` ;

CREATE TABLE IF NOT EXISTS `umpire`.`match_staging` (
  `appointments_id` INT(11) NOT NULL,
  `appointments_season` INT(11) NULL DEFAULT NULL,
  `appointments_round` INT(11) NULL DEFAULT NULL,
  `appointments_date` DATETIME NULL DEFAULT NULL,
  `appointments_compname` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_ground` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_time` DATETIME NULL DEFAULT NULL,
  `appointments_hometeam` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_awayteam` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_field1_first` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_field1_last` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_field2_first` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_field2_last` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_field3_first` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_field3_last` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_boundary1_first` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_boundary1_last` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_boundary2_first` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_boundary2_last` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_boundary3_first` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_boundary3_last` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_boundary4_first` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_boundary4_last` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_goal1_first` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_goal1_last` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_goal2_first` VARCHAR(100) NULL DEFAULT NULL,
  `appointments_goal2_last` VARCHAR(100) NULL DEFAULT NULL,
  `season_id` INT(11) NULL DEFAULT NULL,
  `round_ID` INT(11) NULL DEFAULT NULL,
  `round_date` DATETIME NULL DEFAULT NULL,
  `round_leagueid` INT(11) NULL DEFAULT NULL,
  `league_leaguename` VARCHAR(100) NULL DEFAULT NULL,
  `league_sponsored_league_name` VARCHAR(100) NULL DEFAULT NULL,
  `agd_agegroupid` INT(11) NULL DEFAULT NULL,
  `ag_agegroup` VARCHAR(100) NULL DEFAULT NULL,
  `agd_divisionid` INT(11) NULL DEFAULT NULL,
  `division_divisionname` VARCHAR(100) NULL DEFAULT NULL,
  `ground_id` INT(11) NULL DEFAULT NULL,
  `ground_mainname` VARCHAR(100) NULL DEFAULT NULL,
  `home_team_id` INT(11) NULL DEFAULT NULL,
  `away_team_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`appointments_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`news`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`news` ;

CREATE TABLE IF NOT EXISTS `umpire`.`news` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(128) NOT NULL,
  `slug` VARCHAR(128) NOT NULL,
  `text` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `slug` ON `umpire`.`news` (`slug` ASC);


-- -----------------------------------------------------
-- Table `umpire`.`umpire`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`umpire` ;

CREATE TABLE IF NOT EXISTS `umpire`.`umpire` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NULL DEFAULT NULL,
  `last_name` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
AUTO_INCREMENT = 5025
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`umpire_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`umpire_type` ;

CREATE TABLE IF NOT EXISTS `umpire`.`umpire_type` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `umpire_type_name` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `umpire`.`umpire_name_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`umpire_name_type` ;

CREATE TABLE IF NOT EXISTS `umpire`.`umpire_name_type` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `umpire_id` INT(11) NULL DEFAULT NULL,
  `umpire_type_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_unt_umpire`
    FOREIGN KEY (`umpire_id`)
    REFERENCES `umpire`.`umpire` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_unt_ut`
    FOREIGN KEY (`umpire_type_id`)
    REFERENCES `umpire`.`umpire_type` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2828
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_unt_umpire_idx` ON `umpire`.`umpire_name_type` (`umpire_id` ASC);

CREATE INDEX `fk_unt_ut_idx` ON `umpire`.`umpire_name_type` (`umpire_type_id` ASC);


-- -----------------------------------------------------
-- Table `umpire`.`umpire_name_type_match`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `umpire`.`umpire_name_type_match` ;

CREATE TABLE IF NOT EXISTS `umpire`.`umpire_name_type_match` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `umpire_name_type_id` INT(11) NULL DEFAULT NULL,
  `match_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_untm_match`
    FOREIGN KEY (`match_id`)
    REFERENCES `umpire`.`match` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_untm_unt`
    FOREIGN KEY (`umpire_name_type_id`)
    REFERENCES `umpire`.`umpire_name_type` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_untm_match_idx` ON `umpire`.`umpire_name_type_match` (`match_id` ASC);

CREATE INDEX `fk_untm_unt_idx` ON `umpire`.`umpire_name_type_match` (`umpire_name_type_id` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
