CREATE TABLE utilisateur(
idutilisateur int(11) AUTO_INCREMENT,
email varchar(255) NOT NULL,
password varchar(255) NOT NULL,
nom varchar(255) NOT NULL,
prenom varchar(255) NOT NULL,
adresse varchar(255) NOT NULL,
ville varchar(255) NOT NULL,
cp varchar(255) NOT NULL,
pays varchar(255) NOT NULL,
droit int(1) NOT NULL,
nomsociete varchar(255) NOT NULL,
biographie varchar(255) NOT NULL,
siteweb varchar(255),
tel varchar(255) NOT NULL,
datecreation DATE NOT NULL,
mentor int(1) NOT NULL,
photo varchar(255) NOT NULL,
idfb bigint(18) NOT NULL,
fb varchar(255),
PRIMARY KEY (idutilisateur)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO utilisateur(email, password, nom, prenom, adresse, ville, cp, pays, droit, nomsociete, biographie, siteweb, tel, datecreation, mentor, photo, idfb)
VALUES('admin@admin.fr', 'f126629ba2d9ae7bca6cbe3fc61aeff2', 'Patrick', 'Robert', '2 Rue de gnégné','Poil', '34000', 'France', 1, 'Terminator & Co.', 'Je m''appelle Robert Patrick et j''ai toujours voulu atomiser le petit John Connor', 'http://www.terminator2-film.fr/', '0000000666', '2014-02-12', 0, '/img/profildef.png', 0);
INSERT INTO utilisateur(email, password, nom, prenom, adresse, ville, cp, pays, droit, nomsociete, biographie, siteweb, tel, datecreation, mentor, photo, idfb)
VALUES('iut@nancy.fr', 'f126629ba2d9ae7bca6cbe3fc61aeff2', 'Nancy Charlemagne', 'IUT', '2 ter Boulevard Charlemagne','Nancy', '54000', 'France', 0, 'Université de Lorraine', 'L''IUT Nancy Charlemagne vous accueille pour des études sur 2 ou 3 ans, au sein de l’Université de Lorraine, en relation avec les autres composantes, en particulier les 7 autres IUT du Collegium Technologie. Des équipes pédagogiques interdisciplinaires vous permettront de réussir vos parcours, dans une ambiance de travail et des conditions matérielles favorables. La proximité des enseignants et la compétence des services techniques et administratifs, l''implication de professionnels dans les programmes, les projets et les stages sont des points forts des DUT et des Licences Professionnelles. Ces diplômes, en formation initiale, continue, en alternance,  vous donnent accès au monde du travail ou vous donneront la possibilité de poursuivre des études, en France et à l''étranger.','http://iut-charlemagne.univ-lorraine.fr/' ,'0354503800', '2014-03-06', 1, '/img/IUTNancy.png', 0);


CREATE TABLE categorie(
idcateg int(11) AUTO_INCREMENT,
libcateg VARCHAR(255) NOT NULL,
PRIMARY KEY(idcateg)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO categorie(libcateg) VALUES('Art');
INSERT INTO categorie(libcateg) VALUES('Aventure et Sport');
INSERT INTO categorie(libcateg) VALUES('Culinaire');
INSERT INTO categorie(libcateg) VALUES('Design et Invention');
INSERT INTO categorie(libcateg) VALUES('Ecologie');
INSERT INTO categorie(libcateg) VALUES('Education');
INSERT INTO categorie(libcateg) VALUES('Film et Video');
INSERT INTO categorie(libcateg) VALUES('Jeu');
INSERT INTO categorie(libcateg) VALUES('Journalisme');
INSERT INTO categorie(libcateg) VALUES('Livre et Edition');
INSERT INTO categorie(libcateg) VALUES('Mode');
INSERT INTO categorie(libcateg) VALUES('Musique');
INSERT INTO categorie(libcateg) VALUES('Photographie');
INSERT INTO categorie(libcateg) VALUES('Spectacle');
INSERT INTO categorie(libcateg) VALUES('Web et Technos');


CREATE TABLE projet(
idprojet int(11) AUTO_INCREMENT,
titre VARCHAR(255) NOT NULL,
resume VARCHAR(255) NOT NULL,
descr LONGBLOB NOT NULL,
but int(11) NOT NULL,
echeance DATE NOT NULL,
argentrecu int(11) NOT NULL,
nbbackers int(11) NOT NULL,
datecreation DATE NOT NULL,
pagefacebook varchar(255),
idcateg int(11) NOT NULL,
idutilisateur int(11) NOT NULL,
PRIMARY KEY(idprojet),
FOREIGN KEY (idcateg) REFERENCES categorie(idcateg),
FOREIGN KEY (idutilisateur) REFERENCES utilisateur(idutilisateur)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, pagefacebook, idcateg, idutilisateur)
VALUES ('projet test', 'test d''un projet', 12, '2014-05-13', 2, 1, '2014-02-12', 'https://www.facebook.com/FacebookDevelopers', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation,pagefacebook, idcateg, idutilisateur)
VALUES ('projet test2', 'test2 d''un projet', 12, '2014-05-26', 2, 1, '2014-02-25','https://www.facebook.com/fundmp', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test3', 'test3 d''un projet', 12, '2014-05-26', 2, 1, '2014-02-25', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test4', 'test4 d''un projet', 12, '2014-05-26', 2, 1, '2014-02-25', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test5', 'test5 d''un projet', 12, '2014-05-30', 2, 1, '2014-03-01', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test6', 'test6 d''un projet', 1200, '2014-04-01', 266, 1, '2014-01-01', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test7', 'test7 d''un projet', 1400, '2014-04-02', 466, 1, '2014-01-02', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test8', 'test8 d''un projet', 1600, '2014-04-02', 666, 1, '2014-01-02', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test9', 'test9 d''un projet', 1800, '2014-04-02', 866, 1, '2014-01-02', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test10', 'test10 d''un projet', 1800, '2014-04-04', 1866, 1, '2014-01-04', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test11', 'test11 d''un projet', 1800, '2014-04-05', 1866, 1, '2014-01-05', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test12', 'test12 d''un projet', 1800, '2014-04-05', 1866, 1, '2014-01-05', 1, 1);
INSERT INTO projet(titre, descr, but, echeance, argentrecu, nbbackers, datecreation, idcateg, idutilisateur)
VALUES ('projet test13', 'test13 d''un projet', 1800, '2014-04-05', 1866, 1, '2014-01-05', 1, 1);


CREATE TABLE commentaire(
idcommentaire int(11) AUTO_INCREMENT,
idutilisateur int(11) NOT NULL,
idprojet int(11) NOT NULL, 
commentaire VARCHAR(255) NOT NULL,
PRIMARY KEY(idcommentaire),
FOREIGN KEY (idutilisateur) REFERENCES utilisateur(idutilisateur),
FOREIGN KEY (idprojet) REFERENCES projet(idprojet)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE tag(
idtag int(11) AUTO_INCREMENT,
libtag VARCHAR(255) NOT NULL,
PRIMARY KEY(idtag)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO tag(libtag) VALUES ('informatique');
INSERT INTO tag(libtag) VALUES ('jeux');
INSERT INTO tag(libtag) VALUES ('jeuxvideo');
INSERT INTO tag(libtag) VALUES ('cinema');
INSERT INTO tag(libtag) VALUES ('film');
INSERT INTO tag(libtag) VALUES ('serie');
INSERT INTO tag(libtag) VALUES ('programme');
INSERT INTO tag(libtag) VALUES ('video');
INSERT INTO tag(libtag) VALUES ('musique');
INSERT INTO tag(libtag) VALUES ('enregistrement');
INSERT INTO tag(libtag) VALUES ('entreprise');
INSERT INTO tag(libtag) VALUES ('musique');


CREATE TABLE possedetag(
idpossedetag int(11) AUTO_INCREMENT,
idprojet int(11) NOT NULL,
idtag int(11) NOT NULL,
PRIMARY KEY(idpossedetag),
FOREIGN KEY(idprojet) REFERENCES projet(idprojet),
FOREIGN KEY(idtag) REFERENCES tag(idtag)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO possedetag(idprojet, idtag) VALUES(1, 2);
INSERT INTO possedetag(idprojet, idtag) VALUES(1, 3);
INSERT INTO possedetag(idprojet, idtag) VALUES(2, 4);
INSERT INTO possedetag(idprojet, idtag) VALUES(2, 5);
INSERT INTO possedetag(idprojet, idtag) VALUES(2, 6);
INSERT INTO possedetag(idprojet, idtag) VALUES(9, 6);

CREATE TABLE recompense(
idrecompense int(11) AUTO_INCREMENT,
idprojet int(11) NOT NULL,
butatteint int(11) NOT NULL,
descrec VARCHAR(255) NOT NULL,
tpslivraison int(11) NOT NULL,
nbbackers int(11) NOT NULL,
limite int(11) NOT NULL,
PRIMARY KEY(idrecompense),
FOREIGN KEY(idprojet) REFERENCES projet(idprojet)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO recompense(idprojet, butatteint, descrec, tpslivraison, nbbackers, limite) VALUES (1, 1, 'un merci', 2, 1, 0);
INSERT INTO recompense(idprojet, butatteint, descrec, tpslivraison, nbbackers, limite) VALUES (1, 2, 'un porte clés', 2, 0, 10);
INSERT INTO recompense(idprojet, butatteint, descrec, tpslivraison, nbbackers, limite) VALUES (1, 5, 'un dvd', 5, 0, 5);

CREATE TABLE media(
idmedia int(11) AUTO_INCREMENT,
idprojet int(11) NOT NULL,
url VARCHAR(255) NOT NULL,
type VARCHAR(255) NOT NULL,
PRIMARY KEY(idmedia),
FOREIGN KEY(idprojet) REFERENCES projet(idprojet)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO media(idprojet, url, type) VALUES(1, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(1, "/medias/product_1.jpg", 'couverture');
INSERT INTO media(idprojet, url, type) VALUES(2, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(2, "/medias/product_1.jpg", 'couverture');
INSERT INTO media(idprojet, url, type) VALUES(3, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(3, "/medias/product_1.jpg", 'couverture');
INSERT INTO media(idprojet, url, type) VALUES(4, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(4, "/medias/product_1.jpg", 'couverture');
INSERT INTO media(idprojet, url, type) VALUES(5, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(5, "/medias/product_1.jpg", 'couverture');
INSERT INTO media(idprojet, url, type) VALUES(6, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(6, "/medias/product_1.jpg", 'couverture');
INSERT INTO media(idprojet, url, type) VALUES(7, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(7, "/medias/product_1.jpg", 'couverture');
INSERT INTO media(idprojet, url, type) VALUES(8, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(8, "/medias/product_1.jpg", 'couverture');
INSERT INTO media(idprojet, url, type) VALUES(9, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(9, "/medias/product_1.jpg", 'couverture');
INSERT INTO media(idprojet, url, type) VALUES(10, "/medias/test2", 'video');
INSERT INTO media(idprojet, url, type) VALUES(10, "/medias/product_1.jpg", 'couverture');


CREATE TABLE bankers(
idbanker int(11) AUTO_INCREMENT, 
idprojet int(11) NOT NULL,
idutilisateur int(11) NOT NULL,
montant int(11) NOT NULL,
datebank DATE NOT NULL,
PRIMARY KEY(idbanker),
FOREIGN KEY(idprojet) REFERENCES projet(idprojet),
FOREIGN KEY(idutilisateur) REFERENCES utilisateur(idutilisateur)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(1, 1, 2, '2014-03-01');
INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(1, 1, 3, '2014-01-01');
INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(6, 1, 36, '2014-03-01');
INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(6, 1, 40, '2014-03-01');
INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(7, 1, 56, '2014-03-02');
INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(7, 1, 60, '2014-03-02');
INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(8, 1, 76, '2014-03-02');
INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(8, 1, 80, '2014-03-02');
INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(9, 1, 96, '2014-03-02');
INSERT INTO bankers(idprojet, idutilisateur, montant, datebank) VALUES(9, 1, 100, '2014-03-02');


CREATE TABLE suivis(
idsuivi int(11) AUTO_INCREMENT,
idprojet int(11) NOT NULL,
idutilisateur int(11) NOT NULL,
PRIMARY KEY(idsuivi),
FOREIGN KEY(idprojet) REFERENCES projet(idprojet),
FOREIGN KEY(idutilisateur) REFERENCES utilisateur(idutilisateur)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
