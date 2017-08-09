-- Lisää INSERT INTO lauseet tähän tiedostoon

INSERT INTO Käyttäjä (nimi, puh, sähköposti, liittymispvm, salasana) VALUES ('Matti', '1234567', 'asdf@asdf', '2017-08-02' , 'salis1234');
INSERT INTO Käyttäjä (nimi, puh, sähköposti, liittymispvm, salasana) VALUES ('Pekka', '7654321', 'qwer@qwer', '2017-08-01' , 'silas4321');





INSERT INTO Tuote (myyjä_id, kuvaus, hinta, lisätietoja, lisäyspäivä) VALUES ('1', 'Tuoli' , '20', 'hieno tuoli ihan uusi', '2017-08-02');
INSERT INTO Tuote (myyjä_id, kuvaus, hinta, lisätietoja, lisäyspäivä) VALUES ('2', 'Pöytä' , '80', 'hieno', '2017-08-07');






INSERT INTO Sopimus (myyjä_id, ostaja_id, tuote_id) VALUES ('1', '2', '1');