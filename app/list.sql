CREATE TABLE Register_user(
   Id_user INT AUTO_INCREMENT,
   firstname VARCHAR(50) NOT NULL,
   pseudo VARCHAR(50) NOT NULL,
   lastname VARCHAR(50) NOT NULL,
   email VARCHAR(50) NOT NULL,
   password VARCHAR(50) NOT NULL,
   avatarurl VARCHAR(50) NOT NULL,
   description VARCHAR(200) NOT NULL,
   PRIMARY KEY(Id_user),
   UNIQUE(pseudo),
   UNIQUE(email)
);

CREATE TABLE Game(
   Id_game INT AUTO_INCREMENT,
   title VARCHAR(50) NOT NULL,
   scenario VARCHAR(200) NOT NULL,
   Id_user_creator INT NOT NULL,
   PRIMARY KEY(Id_game),
   FOREIGN KEY(Id_user_creator) REFERENCES Register_user(Id_user)
);

CREATE TABLE Trophy(
   Id_trophy INT AUTO_INCREMENT,
   name VARCHAR(50) NOT NULL,
   description VARCHAR(200) NOT NULL,
   type VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_trophy),
   UNIQUE(name),
   UNIQUE(description)
);

CREATE TABLE review(
   Id_evaluation INT AUTO_INCREMENT,
   rating SMALLINT NOT NULL,
   review VARCHAR(200) NOT NULL,
   Id_game INT NOT NULL,
   Id_user INT NOT NULL,
   PRIMARY KEY(Id_evaluation),
   FOREIGN KEY(Id_game) REFERENCES Game(Id_game),
   FOREIGN KEY(Id_user) REFERENCES Register_user(Id_user)
);

CREATE TABLE game_step(
   Id_game_step INT AUTO_INCREMENT,
   clue VARCHAR(50) NOT NULL,
   question VARCHAR(50),
   funfact VARCHAR(200) NOT NULL,
   step_order SMALLINT NOT NULL,
   Id_game INT NOT NULL,
   PRIMARY KEY(Id_game_step),
   FOREIGN KEY(Id_game) REFERENCES Game(Id_game)
);

CREATE TABLE answer(
   Id_answer INT AUTO_INCREMENT,
   answer VARCHAR(50) NOT NULL,
   GoodFalse BOOLEAN,
   Id_game_step INT NOT NULL,
   PRIMARY KEY(Id_answer),
   FOREIGN KEY(Id_game_step) REFERENCES game_step(Id_game_step)
);

CREATE TABLE Session(
   Id_session INT AUTO_INCREMENT,
   starDate DATE NOT NULL,
   status SMALLINT NOT NULL,
   Id_game_step INT NOT NULL,
   PRIMARY KEY(Id_session),
   FOREIGN KEY(Id_game_step) REFERENCES game_step(Id_game_step)
);

CREATE TABLE identify(
   Id_user INT,
   Id_session INT,
   PRIMARY KEY(Id_user, Id_session),
   FOREIGN KEY(Id_user) REFERENCES Register_user(Id_user),
   FOREIGN KEY(Id_session) REFERENCES Session(Id_session)
);

CREATE TABLE earn(
   Id_session INT,
   Id_trophy INT,
   PRIMARY KEY(Id_session, Id_trophy),
   FOREIGN KEY(Id_session) REFERENCES Session(Id_session),
   FOREIGN KEY(Id_trophy) REFERENCES Trophy(Id_trophy)
);
