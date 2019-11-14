DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`(
                       id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                       username VARCHAR(30),
                       password VARCHAR(64),
                       session VARCHAR(64),
                       balance DECIMAL(10,2) DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO `user` (`username`, `password`, `balance`) VALUES ('user', '3d06bd7358988db34fd83db5d850934a69f63f5c', 1999.20);

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction`(
                              id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                              user_id int,
                              datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
                              value DECIMAL(10,2),
                              hash VARCHAR(64),
                              status int(1) NOT NULL DEFAULT 1,
                              FOREIGN KEY fk_cat(user_id)
                                  REFERENCES user(id)
                                  ON UPDATE CASCADE
                                  ON DELETE RESTRICT
) ENGINE=InnoDB;