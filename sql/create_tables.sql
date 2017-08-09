-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Käyttäjä(
	id SERIAL PRIMARY KEY,
	nimi varchar(40) NOT NULL,
	puh varchar(15) NOT NULL,
	sähköposti varchar(50) NOT NULL,
	liittymispvm DATE,
	salasana varchar(40) NOT NULL
);

CREATE TABLE Tuote(
	id SERIAL PRIMARY KEY,
	myyjä_id INTEGER REFERENCES Käyttäjä(id),
	kuvaus varchar(30) NOT NULL,
	hinta INTEGER NOT NULL,
	lisätietoja varchar(300),
	lisäyspäivä DATE
);

CREATE TABLE Sopimus(
	myyjä_id INTEGER REFERENCES Käyttäjä(id),
	ostaja_id INTEGER REFERENCES Käyttäjä(id),
	tuote_id INTEGER REFERENCES Tuote(id)
);

