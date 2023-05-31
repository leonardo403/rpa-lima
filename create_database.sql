CREATE SCHEMA `rpa_lima` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;


CREATE TABLE `rpa_lima`.`table_rpa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `amount` FLOAT NOT NULL,
  PRIMARY KEY (`id`));

