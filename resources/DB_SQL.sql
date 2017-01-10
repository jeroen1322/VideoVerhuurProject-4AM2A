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
create table `Order`(id int auto_increment primary key, klantid int, afleverdatum datetime, ophaaldatum datetime, bedrag float);
create table Orderregel(exemplaarid int, orderid int, primary key(exemplaarid, orderid));
ALTER TABLE `Order`
ADD FOREIGN KEY (klantid)
REFERENCES Klant(id);
create table Exemplaar(id int primary key, filmid int, statusid int, aantalVerhuur int);
ALTER TABLE `Orderregel`
ADD FOREIGN KEY (exemplaarid)
REFERENCES Exemplaar(id);
create table Film(id int primary key, titel varchar(50), acteur varchar(100), omschr varchar(200));
ALTER TABLE `Exemplaar`
ADD FOREIGN KEY (filmid)
REFERENCES Film(id);

SELECT * FROM Klant;
