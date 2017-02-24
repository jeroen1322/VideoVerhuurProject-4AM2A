use tempovideo_sara;
create table TussenGenre(filmid int, genreid int, primary key (filmid, genreid));
create table Genre(genreid int primary key, omschr varchar(50));
alter table Film drop column Genre;
alter table Film add column uploaddatum varchar(25);
alter table TussenGenre
add foreign key (filmid)
references Film(id);

alter table TussenGenre
add foreign key (genreid)
references Genre(id);

select * from Film;
select omschr from Genre;

insert into Genre values (1, "Actie");

insert into Genre values (2, "Avontuur");

insert into Genre values (3, "Drama");

insert into Genre values (4, "Fantasy");
insert into Genre values (5, "Gangster");
insert into Genre values (6, "Historisch drama");
insert into Genre values (7, "Horror");
insert into Genre values (8, "Komedie");
insert into Genre values (9, "Kostuumdrama");
insert into Genre values (10, "Melodrama");
insert into Genre values (11, "Misdaad");
insert into Genre values (12, "Musical");
insert into Genre values (13, "Oorlog");
insert into Genre values (14, "Psychologische thriller");
insert into Genre values (15, "Rampen");
insert into Genre values (16, "Roadmovie");
insert into Genre values (17, "Romantisch");
insert into Genre values (18, "Romantische komedie");
insert into Genre values (19, "Sciencefiction");
insert into Genre values (20, "Sport");
insert into Genre values (21, "Thriller");
insert into Genre values (22, "Western");