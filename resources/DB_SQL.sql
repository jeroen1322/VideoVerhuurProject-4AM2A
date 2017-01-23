create database tempovideo;
use tempovideo;
create table Rol(id int primary key, omschr varchar(45));
create table Medewerker(id int primary key, rolid int, achternaam varchar(45), wachtwoordid int);
create table Wachtwoord(id int primary key, wachtwoord varchar(255));
create table Klant(id int auto_increment primary key, naam varchar(50), adres varchar(50), postcode varchar(7), woonplaats varchar(25), telefoonnummer varchar(10), email varchar(45), wachtwoordid int);
ALTER TABLE `Medewerker`
ADD FOREIGN KEY (rolid)
REFERENCES Rol(id);
ALTER TABLE `Medewerker`
ADD FOREIGN KEY (wachtwoordid)
REFERENCES Wachtwoord(id);

create table `Order`(id int primary key, klantid int, 
afleverdatum DATETIME DEFAULT CURRENT_TIMESTAMP, ophaaldatum DATETIME DEFAULT CURRENT_TIMESTAMP, bedrag float);

create table Orderregel(exemplaarid int, orderid int, primary key(exemplaarid, orderid));
ALTER TABLE `Order`
ADD FOREIGN KEY (klantid)
REFERENCES Klant(id);
create table Exemplaar(id int auto_increment primary key, filmid int, statusid int, aantalVerhuur int);
ALTER TABLE `Orderregel`
ADD FOREIGN KEY (exemplaarid)
REFERENCES Exemplaar(id);
create table Film(id int auto_increment primary key, titel varchar(50), acteur varchar(100), omschr varchar(200));
ALTER TABLE `Exemplaar`
ADD FOREIGN KEY (filmid)
REFERENCES Film(id);

ALTER TABLE Film
ADD genre varchar(50);
ALTER TABLE Film
ADD img varchar(50);

drop table Medewerker;
rename table Klant to Persoon;
ALTER TABLE Persoon
ADD rolid int;
ALTER TABLE `Persoon`
ADD FOREIGN KEY (rolid)
REFERENCES Rol(id);
ALTER TABLE Film
ADD afbeelding varchar(50);

create table Status(id int primary key, omschr varchar(50));
ALTER TABLE `Exemplaar`
ADD FOREIGN KEY (statusid)
REFERENCES Status(id);

INSERT INTO Rol (id, omschr) VALUES (1, "Klant");
INSERT INTO Rol (id, omschr) VALUES (2, "Bezorger");
INSERT INTO Rol (id, omschr) VALUES (3, "baliemedewerker");
INSERT INTO Rol (id, omschr) VALUES (4, "eigenaar");
INSERT INTO Rol (id, omschr) VALUES (5, "Geblokkeerd");

INSERT INTO `Status`(id, omschr) VALUES(1, "Beschikbaar");
INSERT INTO `Status`(id, omschr) VALUES(2, "NIET Beschikbaar");

ALTER TABLE `Order`
ADD `Afhandeling` bool;
ALTER TABLE `Order`
ADD `besteld` bool;

-- SELECT * FROM `Rol`;
SELECT * FROM `Order`;
SELECT * FROM `Orderregel`;
SELECT * FROM `Exemplaar`;
SELECT * FROM `Film`;
-- SELECT * FROM `Persoon`;


INSERT INTO Wachtwoord(id, wachtwoord) VALUES (1, '$2y$10$GjFXmwAmtSTX5f7WR3IIpebLaNCCv0ehFZCE1lEttXhcYGgCp9EB.');
INSERT INTO Persoon (naam, adres, postcode, woonplaats, telefoonnummer, email, wachtwoordid, rolid) VALUES ('Hans Odijk', 'columbuslaan 540', '3526 EP', 'Utrecht', '0302815100', 'eigenaar@jeroengrooten.nl', 1, 4);

INSERT INTO Wachtwoord(id, wachtwoord) VALUES (2, '$2y$10$GjFXmwAmtSTX5f7WR3IIpebLaNCCv0ehFZCE1lEttXhcYGgCp9EB.');
INSERT INTO Persoon (naam, adres, postcode, woonplaats, telefoonnummer, email, wachtwoordid, rolid) VALUES ('Hans Odijk', 'columbuslaan 540', '3526 EP', 'Utrecht', '0302815100', 'balie@jeroengrooten.nl', 2, 3);

INSERT INTO Wachtwoord(id, wachtwoord) VALUES (3, '$2y$10$GjFXmwAmtSTX5f7WR3IIpebLaNCCv0ehFZCE1lEttXhcYGgCp9EB.');
INSERT INTO Persoon (naam, adres, postcode, woonplaats, telefoonnummer, email, wachtwoordid, rolid) VALUES ('Hans Odijk', 'columbuslaan 540', '3526 EP', 'Utrecht', '0302815100', 'bezorger@jeroengrooten.nl', 2, 2);