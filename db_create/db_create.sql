drop database if exists neptun;


create database neptun
    DEFAULT CHARACTER SET utf8
   	DEFAULT COLLATE utf8_general_ci;

use neptun;


create table felhasznalok(
    /*id int primary key auto_increment,*/
    neptun nvarchar(6) primary key not null,
    felhasznalonev nvarchar(15) not null,
    jelszo nvarchar (70) not null,
    priv ENUM('admin','hallgato','oktato', 'targyadmin'),
    stilus ENUM('vilagos','sotet') default 'vilagos' not null,
    UNIQUE (felhasznalonev)
);

create table hallgatok
(
    id int primary key auto_increment,
    neptun nvarchar(6) not null,
    vezeteknev nvarchar(50) not null,
    keresztnev nvarchar(50) not null,
    kepzes nvarchar(50) not null,
    felvetel_ev int not null,
    foreign key (neptun) references felhasznalok(neptun)
);

create table oktatok(
    id int primary key auto_increment,
    neptun nvarchar(6) not null,
    vezeteknev nvarchar(50) not null,
    keresztnev nvarchar(50) not null,
    tanszek nvarchar(50) not null,
    foreign key (neptun) references felhasznalok(neptun)

);



insert into felhasznalok (felhasznalonev, jelszo, neptun, priv) values ('admin', SHA2('admin', 256), '111111', 'admin');
insert into felhasznalok (felhasznalonev, jelszo, neptun, priv) values ('targyadmin', SHA2('targyadmin', 256), '222222', 'targyadmin');

insert into felhasznalok (felhasznalonev, jelszo, neptun, priv) values ('veghpeter', SHA2('asdasd', 256), 'OVM01K', 'hallgato');
insert into hallgatok (neptun, vezeteknev, keresztnev, kepzes, felvetel_ev) values ('OVM01K', 'Végh', 'Péter', 'villamosmérnöki', 2020);

insert into felhasznalok (felhasznalonev, jelszo, neptun, priv) values ('baloghjanos', SHA2('asdasd', 256), 'UBX261', 'hallgato');
insert into hallgatok (neptun, vezeteknev, keresztnev, kepzes, felvetel_ev) values ('UBX261', 'Balogh', 'János', 'mérnökinformatikus', 2017);

insert into felhasznalok (felhasznalonev, jelszo, neptun, priv) values ('siposbenedek', SHA2('asdasd', 256), 'JGF7JA', 'hallgato');
insert into hallgatok (neptun, vezeteknev, keresztnev, kepzes, felvetel_ev) values ('JGF7JA', 'Sipos', 'Benedek', 'villamosmérnöki', 2022);


insert into felhasznalok (felhasznalonev, jelszo, neptun, priv) values ('hegeduszsolt', SHA2('asdasd', 256), 'XT3V0B', 'oktato');
insert into oktatok (neptun, vezeteknev, keresztnev, tanszek) values ('XT3V0B', 'Hegedűs', 'Zsolt', 'AUT');

insert into felhasznalok (felhasznalonev, jelszo, neptun, priv) values ('orsosmira', SHA2('asdasd', 256), 'HNEZCG', 'oktato');
insert into oktatok (neptun, vezeteknev, keresztnev, tanszek) values ('HNEZCG', 'Orsós', 'Míra', 'HVT');


create table targy(
    id int primary key auto_increment,
    kod nvarchar(8) not null,
    megnevezes nvarchar(50) not null,
    tanszek nvarchar(50) not null,
    UNIQUE (kod)
);

insert into targy (kod, megnevezes, tanszek) values ('VIAUAB01', 'Informatika 2', 'AUT');
insert into targy (kod, megnevezes, tanszek) values ('VIIIAA02', 'Digitális technika 2', 'IIT');
insert into targy (kod, megnevezes, tanszek) values ('VIHVAA00', 'Jelek és rendszerek 1', 'HVT');
insert into targy (kod, megnevezes, tanszek) values ('VIMIAB01', 'Méréstechnika', 'MIT');



create table jegy(
    id int primary key auto_increment,
    hallgato_id int not null,
    oktato_id int not null,
    targy_id int not null,
    jegy int not null,
    datum date not null,

    foreign key (hallgato_id) references hallgatok(id),
    foreign key (oktato_id) references oktatok(id),
    foreign key (targy_id) references targy(id)
);

insert into jegy (hallgato_id, oktato_id, targy_id, jegy, datum) values (1, 1, 1, 3, curdate());
