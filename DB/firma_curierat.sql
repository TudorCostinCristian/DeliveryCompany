-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 24, 2022 at 10:45 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `firma_curierat`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `ActiuniAngajati`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActiuniAngajati` (IN `a_email` VARCHAR(64), IN `nume_dep` VARCHAR(64), IN `act` INT)  NO SQL
BEGIN
	DECLARE dep_id INT;
    DECLARE ang_id INT;
    
    SELECT AngajatID
    INTO ang_id
    FROM angajati
    WHERE Email = a_email;
    
    SELECT DepartamentID
    INTO dep_id
    FROM departamente
    WHERE NumeDepartament = nume_dep;
    
    
	IF act = 1 THEN
    	UPDATE angajati SET DepartamentID = dep_id WHERE AngajatID = ang_id;
        UPDATE departamente SET ManagerID = NULL WHERE ManagerID = ang_id;
        UPDATE departamente SET ManagerID = ang_id WHERE DepartamentID = dep_id;
    END IF;
    
    IF act = 2 THEN
    	UPDATE departamente SET ManagerID = NULL WHERE ManagerID = ang_id;
    END IF;
    
    IF act = 3 THEN
    	UPDATE angajati SET DepartamentID = dep_id WHERE AngajatID = ang_id;
        UPDATE departamente SET ManagerID = NULL WHERE ManagerID = ang_id;
    END IF;
    
    IF act = 4 THEN
    	UPDATE departamente SET ManagerID = NULL WHERE ManagerID = ang_id;
        UPDATE angajati SET DepartamentID = NULL WHERE AngajatID = ang_id;
    END IF;
    
    IF act = 5 THEN
    	UPDATE departamente SET ManagerID = NULL WHERE ManagerID = ang_id;
        DELETE FROM angajati WHERE AngajatID = ang_id;
    END IF;

END$$

DROP PROCEDURE IF EXISTS `ActiuniClienti`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActiuniClienti` (IN `client_id` INT, IN `act` INT)  BEGIN
	IF act = 1 THEN
    	UPDATE clienti SET ContActivat = 1 WHERE ClientID = client_id;
    END IF;
    
    IF act = 2 THEN
    	UPDATE clienti SET ContActivat = 0 WHERE ClientID = client_id;
    END IF;
    
    IF act = 3 THEN
    	DELETE FROM clienti WHERE ClientID = client_id;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `ActiuniOrase`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActiuniOrase` (IN `a_email` VARCHAR(64), IN `nume_oras` VARCHAR(64), IN `act` INT)  NO SQL
BEGIN
DECLARE id_angajat INT;
DECLARE id_oras INT;

SELECT AngajatID
INTO id_angajat
FROM angajati
WHERE Email = a_email;

SELECT OrasID
INTO id_oras
FROM orase
WHERE NumeOras = nume_oras;

IF act = 1 THEN
	INSERT INTO angajati_orase(OrasID, AngajatID) 
    VALUES (id_oras, id_angajat);
END IF;

IF act = 2 THEN
	DELETE FROM angajati_orase
    WHERE OrasID = id_oras AND AngajatID = id_angajat;
END IF;

END$$

DROP PROCEDURE IF EXISTS `ActiuniPromotii`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActiuniPromotii` (IN `p_action` INT, IN `id_promotie` INT)  BEGIN
	IF p_action = 1 THEN
    	DELETE FROM promotii_clienti WHERE PromotieID = id_promotie;
        DELETE FROM promotii WHERE PromotieID = id_promotie;
    END IF;
    
    IF p_action = 2 THEN
    	UPDATE promotii SET Valabilitate = 0 
        WHERE PromotieID = id_promotie;
    END IF;
    
    IF p_action = 3 THEN
    	UPDATE promotii SET Valabilitate = Valabilitate + 8640000
        WHERE PromotieID = id_promotie;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `AdaugaColet`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AdaugaColet` (IN `client_id` INT, IN `email_destinatar` VARCHAR(64), IN `vol` INT, IN `mesaj` VARCHAR(512), IN `timest` INT, IN `pret` FLOAT)  BEGIN
DECLARE id_destinatar INT;

SELECT ClientID
INTO id_destinatar
FROM clienti
WHERE Email = email_destinatar;

INSERT INTO colete(ExpeditorID, DestinatarID, Volum, Status, Mesaj, Timestamp, CostLivrare) 
VALUES (client_id, id_destinatar, vol, 1, mesaj, timest, pret);
END$$

DROP PROCEDURE IF EXISTS `AdaugaObiect_Colet`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AdaugaObiect_Colet` (IN `client_id` INT, IN `timest` INT, IN `nume_obj` VARCHAR(30), IN `fragil` CHAR)  NO SQL
BEGIN
DECLARE id_colet INT;

SELECT ColetID
INTO id_colet
FROM colete
WHERE ExpeditorID = client_id AND Timestamp = timest;

INSERT INTO obiecte(ColetID, NumeObiect, Fragil)
VALUES(id_colet, nume_obj, fragil);
                        
END$$

DROP PROCEDURE IF EXISTS `Autentificare`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Autentificare` (IN `user_email` VARCHAR(64), IN `pass` VARCHAR(256))  BEGIN
	SELECT *
    FROM clienti
    WHERE Email = user_email AND Parola = pass;
END$$

DROP PROCEDURE IF EXISTS `ColectorColet`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ColectorColet` (IN `colet_id` INT, IN `angajat_id` INT)  BEGIN
UPDATE colete
SET ColectorID = angajat_id
WHERE ColetID = colet_id;
END$$

DROP PROCEDURE IF EXISTS `ColeteClient`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ColeteClient` (IN `client_id` INT, IN `type` VARCHAR(20))  BEGIN
IF type = "TRIMISE" THEN 
	SELECT C.ColetID AS IDColet, D.Email AS EmailDestinatar, C.Status AS 		StatusColet, C.Volum AS VolumColet, C.Timestamp AS TimestampColet
	FROM clienti D, clienti E, colete C
	WHERE C.ExpeditorID = E.ClientID
	AND C.DestinatarID = D.ClientID
	AND C.ExpeditorID = client_id
	ORDER BY C.Timestamp DESC;
END IF;
IF type = "PRIMITE" THEN
	SELECT C.ColetID AS IDColet, E.Email AS EmailExpeditor, C.Status AS 	StatusColet, C.Volum AS VolumColet, C.Timestamp AS TimestampColet
	FROM clienti D, clienti E, colete C
	WHERE C.ExpeditorID = E.ClientID
	AND C.DestinatarID = D.ClientID
	AND C.DestinatarID = client_id
	ORDER BY C.Timestamp DESC;
END IF;

END$$

DROP PROCEDURE IF EXISTS `ComenziFinalizate_Colectori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ComenziFinalizate_Colectori` (IN `angajat_id` INT)  NO SQL
BEGIN
SELECT COL.ColetID, COL.Volum, EXP.Email AS EmailExpeditor, EXP.Nume, EXP.Prenume, EXP.Telefon, EXP.Oras, EXP.Judet, EXP.Strada, EXP.Numar, EXP.Bloc, EXP.Scara, EXP.Etaj, EXP.Apartament, DEST.Email AS EmailDestinatar, (SELECT COUNT(*) FROM obiecte OB WHERE OB.ColetID = COL.ColetID AND OB.Fragil = "D") AS Fragil
FROM colete COL, clienti EXP, clienti DEST
WHERE COL.Status > 4 AND
COL.ExpeditorID = EXP.ClientID AND
COL.DestinatarID = DEST.ClientID AND
COL.ColectorID = angajat_id;
END$$

DROP PROCEDURE IF EXISTS `ComenziFinalizate_Livratori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ComenziFinalizate_Livratori` (IN `angajat_id` INT)  BEGIN
SELECT COL.ColetID, COL.Volum, EXP.Email AS EmailExpeditor, DEST.Nume, DEST.Prenume, DEST.Telefon, DEST.Oras AS OrasDEST, EXP.Oras AS OrasEXP, DEST.Judet, DEST.Strada, DEST.Numar, DEST.Bloc, DEST.Scara, DEST.Etaj,
DEST.Apartament, DEST.Email AS EmailDestinatar, (SELECT COUNT(*) FROM obiecte OB WHERE OB.ColetID = COL.ColetID AND OB.Fragil = "D") AS Fragil
	FROM colete COL, clienti EXP, clienti DEST
	WHERE COL.Status = 7 AND
	COL.ExpeditorID = EXP.ClientID AND
	COL.DestinatarID = DEST.ClientID AND
	COL.LivratorID = angajat_id AND
	EXP.Oras IN(SELECT O.NumeOras FROM orase O, angajati_orase AO WHERE 	AO.OrasID = O.OrasID AND AO.AngajatID = angajat_id)
	AND DEST.Oras IN(SELECT O.NumeOras FROM orase O, angajati_orase AO 		WHERE AO.OrasID = O.OrasID AND AO.AngajatID = angajat_id);
END$$

DROP PROCEDURE IF EXISTS `ComenziPreluate_Colectori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ComenziPreluate_Colectori` (IN `angajat_id` INT)  BEGIN
SELECT COL.ColetID, COL.Status, COL.Volum, EXP.Email AS EmailExpeditor, EXP.Nume, EXP.Prenume, EXP.Telefon, EXP.Oras, EXP.Judet, EXP.Strada, EXP.Numar, EXP.Bloc, EXP.Scara, EXP.Etaj, EXP.Apartament, DEST.Email AS EmailDestinatar, (SELECT COUNT(*) FROM obiecte OB WHERE OB.ColetID = COL.ColetID AND OB.Fragil = "D") AS Fragil
FROM colete COL, clienti EXP, clienti DEST
WHERE (COL.Status = 3 OR COL.Status = 4) AND
COL.ExpeditorID = EXP.ClientID AND
COL.DestinatarID = DEST.ClientID AND
COL.ColectorID = angajat_id;
END$$

DROP PROCEDURE IF EXISTS `ComenziRidicate_Livratori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ComenziRidicate_Livratori` (IN `angajat_id` INT)  BEGIN
SELECT COL.ColetID, COL.Volum, EXP.Email AS EmailExpeditor, DEST.Nume, DEST.Prenume, DEST.Telefon, DEST.Oras AS OrasDEST, EXP.Oras AS OrasEXP, DEST.Judet, DEST.Strada, DEST.Numar, DEST.Bloc, DEST.Scara, DEST.Etaj, DEST.Apartament, DEST.Email AS EmailDestinatar, (SELECT COUNT(*) FROM obiecte OB WHERE OB.ColetID = COL.ColetID AND OB.Fragil = "D") AS Fragil
	FROM colete COL, clienti EXP, clienti DEST
	WHERE COL.Status = 6 AND
	COL.ExpeditorID = EXP.ClientID AND
	COL.DestinatarID = DEST.ClientID AND
	COL.LivratorID = angajat_id AND
	EXP.Oras IN(SELECT O.NumeOras FROM orase O, angajati_orase AO WHERE 	AO.OrasID = O.OrasID AND AO.AngajatID = angajat_id)
	AND DEST.Oras IN(SELECT O.NumeOras FROM orase O, angajati_orase AO
    WHERE AO.OrasID = O.OrasID AND AO.AngajatID = angajat_id);
END$$

DROP PROCEDURE IF EXISTS `ContinutColet`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ContinutColet` (IN `colet_id` INT)  BEGIN
	SELECT NumeObiect, Fragil
    FROM obiecte
    WHERE ColetID = colet_id;

END$$

DROP PROCEDURE IF EXISTS `DestinatarID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DestinatarID` (IN `u_email` VARCHAR(64))  BEGIN
SELECT ClientID
FROM clienti
WHERE email = u_email;
END$$

DROP PROCEDURE IF EXISTS `GetAngajatData`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAngajatData` (IN `angajat_id` INT)  NO SQL
BEGIN
	SELECT *
    FROM angajati
    WHERE AngajatID = angajat_id;
END$$

DROP PROCEDURE IF EXISTS `GetClientData`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetClientData` (IN `client_id` INT)  BEGIN

SELECT *
FROM clienti
WHERE ClientID = client_id;

END$$

DROP PROCEDURE IF EXISTS `ID_Angajat`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ID_Angajat` (IN `email_ang` VARCHAR(64))  BEGIN
	SELECT AngajatID
    FROM angajati
    WHERE Email = email_ang;
END$$

DROP PROCEDURE IF EXISTS `ID_Client`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ID_Client` (IN `c_email` VARCHAR(64))  BEGIN
SELECT ClientID FROM clienti WHERE Email = c_email;
END$$

DROP PROCEDURE IF EXISTS `ID_Departament`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ID_Departament` (IN `nume_departament` VARCHAR(64))  BEGIN
	SELECT DepartamentID
    FROM departamente
    WHERE NumeDepartament = nume_departament;
END$$

DROP PROCEDURE IF EXISTS `ID_Oras`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ID_Oras` (IN `nume_oras` VARCHAR(64))  BEGIN
SELECT OrasID
FROM orase
WHERE NumeOras = nume_oras;
END$$

DROP PROCEDURE IF EXISTS `Inregistrare`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Inregistrare` (IN `email` VARCHAR(64), IN `parola` VARCHAR(256), IN `telefon` VARCHAR(12), IN `nume` VARCHAR(20), IN `prenume` VARCHAR(20), IN `sex` CHAR, IN `datanasterii` DATETIME, IN `oras` VARCHAR(20), IN `judet` VARCHAR(20), IN `strada` VARCHAR(40), IN `numar` INT, IN `bloc` VARCHAR(6), IN `scara` VARCHAR(6), IN `etaj` INT, IN `apartament` INT, IN `codpostal` VARCHAR(10))  BEGIN
INSERT INTO clienti(Email, Parola, Telefon, Nume, Prenume, Sex, DataNasterii, Oras, Judet, Strada, Numar, Bloc, Scara, Etaj, Apartament, CodPostal, ContActivat) 
VALUES(email, parola, telefon, nume, prenume, sex, datanasterii, oras, judet, strada, numar, bloc, scara, etaj, apartament, codpostal, 1);

END$$

DROP PROCEDURE IF EXISTS `LivratorColet`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `LivratorColet` (IN `colet_id` INT, IN `angajat_id` INT)  BEGIN
UPDATE colete
SET LivratorID = angajat_id
WHERE ColetID = colet_id;
END$$

DROP PROCEDURE IF EXISTS `LoginAngajati`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `LoginAngajati` (IN `a_email` VARCHAR(64), IN `pass` VARCHAR(256))  BEGIN
	SELECT AngajatID, Email, Parola 
	FROM angajati 
	WHERE Email = a_email AND Parola = pass;
END$$

DROP PROCEDURE IF EXISTS `NumeDepartament`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `NumeDepartament` (IN `dep_id` INT)  BEGIN
	SELECT NumeDepartament FROM departamente
    WHERE DepartamentID = dep_id;
END$$

DROP PROCEDURE IF EXISTS `PreluareComenzi_Colectori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `PreluareComenzi_Colectori` (IN `angajat_id` INT)  BEGIN
SELECT COL.ColetID, COL.Volum, EXP.Email AS EmailExpeditor, EXP.Nume, EXP.Prenume, EXP.Telefon, EXP.Oras, EXP.Judet, EXP.Strada, EXP.Numar, EXP.Bloc, EXP.Scara, EXP.Etaj, EXP.Apartament, DEST.Email AS EmailDestinatar, (SELECT COUNT(*) FROM obiecte OB WHERE OB.ColetID = COL.ColetID AND OB.Fragil = "D") AS Fragil 
FROM colete COL, clienti EXP, clienti DEST
WHERE COL.Status = 2 AND
COL.ExpeditorID = EXP.ClientID AND
COL.DestinatarID = DEST.ClientID AND
EXP.Oras IN(SELECT O.NumeOras FROM orase O, angajati_orase AO WHERE AO.OrasID = O.OrasID AND AO.AngajatID = angajat_id);
END$$

DROP PROCEDURE IF EXISTS `PromotieNoua`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `PromotieNoua` (IN `nume_promotie` VARCHAR(30), IN `discount_promotie` INT, IN `valabilitate_promotie` INT)  BEGIN
INSERT INTO promotii(NumePromotie, DiscountLei, Valabilitate) 
VALUES(nume_promotie, discount_promotie, valabilitate_promotie);
END$$

DROP PROCEDURE IF EXISTS `PromotiiClient`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `PromotiiClient` (IN `client_id` INT)  BEGIN
	SELECT P.PromotieID, P.NumePromotie, P.DiscountLei, P.Valabilitate
	FROM promotii P, promotii_clienti PC
	WHERE P.PromotieID = PC.PromotieID
	AND PC.ClientID = client_id;
END$$

DROP PROCEDURE IF EXISTS `RidicareColete_Livratori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RidicareColete_Livratori` (IN `angajat_id` INT)  BEGIN
SELECT COL.ColetID, COL.Volum, EXP.Email AS EmailExpeditor, DEST.Nume, 		DEST.Prenume, DEST.Telefon, DEST.Oras AS OrasDEST, EXP.Oras AS OrasEXP, 	DEST.Judet, DEST.Strada, DEST.Numar, DEST.Bloc, DEST.Scara, DEST.Etaj,
	DEST.Apartament, DEST.Email AS EmailDestinatar, (SELECT COUNT(*) FROM
    obiecte OB WHERE OB.ColetID = COL.ColetID AND OB.Fragil = "D") AS Fragil
FROM colete COL, clienti EXP, clienti DEST
WHERE COL.Status = 5 AND
COL.ExpeditorID = EXP.ClientID AND
COL.DestinatarID = DEST.ClientID AND
EXP.Oras IN(SELECT O.NumeOras FROM orase O, angajati_orase AO WHERE AO.OrasID = O.OrasID AND AO.AngajatID = angajat_id)
AND DEST.Oras IN(SELECT O.NumeOras FROM orase O, angajati_orase AO WHERE AO.OrasID = O.OrasID AND AO.AngajatID = angajat_id);

END$$

DROP PROCEDURE IF EXISTS `SelectareAngajatiCuDep`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectareAngajatiCuDep` ()  BEGIN
SELECT A.Nume, A.Prenume, A.Email, A.Telefon, A.CNP, A.Sex, D.NumeDepartament, (SELECT COUNT(*) FROM Departamente D2 WHERE D2.ManagerID = A.AngajatID) AS Manager 
FROM Angajati A, Departamente D
WHERE D.DepartamentID = A.DepartamentID;
END$$

DROP PROCEDURE IF EXISTS `SelectareAngajatiDepartament`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectareAngajatiDepartament` (IN `dep_id` INT)  BEGIN
SELECT A.Nume, A.Prenume, A.Email, A.Telefon, A.CNP, A.Sex, D.NumeDepartament, (SELECT COUNT(*) FROM Departamente D2 WHERE D2.ManagerID = A.AngajatID) AS Manager
FROM Angajati A, Departamente D
WHERE D.DepartamentID = A.DepartamentID AND A.DepartamentID = dep_id;
END$$

DROP PROCEDURE IF EXISTS `SelectareAngajatiFaraDep`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectareAngajatiFaraDep` ()  BEGIN
SELECT A.Nume, A.Prenume, A.Email, A.Telefon, A.CNP, A.Sex, "-" AS NumeDepartament, "NU" AS Manager
FROM Angajati A WHERE A.DepartamentID IS NULL;
END$$

DROP PROCEDURE IF EXISTS `SelectareClienti`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectareClienti` ()  BEGIN
SELECT C.ClientID, C.Nume, C.Prenume, C.Sex, C.Email, C.Telefon, C.Oras, C.ContActivat, C.DataNasterii,
(SELECT COUNT(*) FROM colete WHERE ExpeditorID = C.ClientID) AS NrColeteTrimise,
(SELECT COUNT(*) FROM colete WHERE DestinatarID = C.ClientID) AS NrColetePrimite
FROM clienti C;
END$$

DROP PROCEDURE IF EXISTS `SelectareOrase`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectareOrase` ()  BEGIN
	SELECT O.NumeOras, (SELECT COUNT(*) FROM angajati_orase AO WHERE AO.OrasID = O.OrasID) AS NrAngajati
	FROM orase O;
END$$

DROP PROCEDURE IF EXISTS `SelectareOraseAngajati`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectareOraseAngajati` ()  BEGIN
	SELECT O.NumeOras, A.Nume, A.Prenume
	FROM orase O, angajati A, angajati_orase AO
	WHERE O.OrasID = AO.OrasID AND A.AngajatID = AO.AngajatID;
END$$

DROP PROCEDURE IF EXISTS `SelectarePromotii`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectarePromotii` ()  BEGIN
SELECT P.PromotieID, P.NumePromotie, P.DiscountLei, P.Valabilitate, (SELECT COUNT(*) FROM promotii_clienti PC WHERE PC.PromotieID = P.PromotieID) AS NrClienti FROM Promotii P;
END$$

DROP PROCEDURE IF EXISTS `SelectarePromotiiClienti`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectarePromotiiClienti` ()  BEGIN
SELECT C.Email, C.Oras, C.Sex, P.NumePromotie
FROM clienti C, promotii_clienti PC, promotii P
WHERE C.ClientID = PC.ClientID AND P.PromotieID = PC.PromotieID;
END$$

DROP PROCEDURE IF EXISTS `Statistici`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Statistici` (IN `stat_id` INT)  BEGIN

IF stat_id = 0 THEN
	SELECT CONCAT(A.Nume, ' ', A.Prenume) AS Name, COUNT(*) AS Val, 
    'Colectorul cu cele mai multe comenzi procesate' AS StatName
	FROM Angajati A, Colete C
	WHERE A.DepartamentID = 3 
    AND C.ColectorID = A.AngajatID
	GROUP BY A.AngajatID, A.Nume, A.Prenume
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

IF stat_id = 1 THEN
	SELECT CONCAT(A.Nume, ' ', A.Prenume) AS Name, COUNT(*) AS Val,
    'Livratorul cu cele mai multe comenzi procesate' AS StatName
	FROM Angajati A, Colete C
	WHERE A.DepartamentID = 4 
    AND C.LivratorID = A.AngajatID
	GROUP BY A.AngajatID, A.Nume, A.Prenume
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

IF stat_id = 2 THEN
	SELECT CONCAT(A.Nume, ' ', A.Prenume) AS Name, COUNT(*) AS Val,
    'Angajatul care lucreaza in cele mai multe orase' AS StatName
	FROM Angajati A, angajati_orase AO
	WHERE A.AngajatID = AO.AngajatID
	GROUP BY A.AngajatID, A.Nume, A.Prenume
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

IF stat_id = 3 THEN
	SELECT O.NumeOras AS Name, COUNT(*) AS Val,
    'Orasul cu cei mai multi angajati' AS StatName
	FROM orase O, angajati_orase AO
	WHERE O.OrasID = AO.OrasID
	GROUP BY O.OrasID, O.NumeOras
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

IF stat_id = 4 THEN
	SELECT C.Oras AS Name, COUNT(*) AS Val,
    'Orasul cu cei mai multi clienti' AS StatName
	FROM clienti C
	GROUP BY C.Oras
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

IF stat_id = 5 THEN
	SELECT CONCAT(C.Nume, ' ', C.Prenume) AS Name, COUNT(*) AS Val,
    'Clientul cu cele mai multe colete trimise' AS StatName
	FROM clienti C, colete COL
	WHERE C.ClientID = COL.ExpeditorID
	GROUP BY C.ClientID, C.Nume, C.Prenume
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

IF stat_id = 6 THEN
	SELECT CONCAT(C.Nume, ' ', C.Prenume) AS Name, COUNT(*) AS Val,
    'Clientul cu cele mai multe colete primite' AS StatName
	FROM clienti C, colete COL
	WHERE C.ClientID = COL.DestinatarID
	GROUP BY C.ClientID, C.Nume, C.Prenume
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

IF stat_id = 7 THEN
	SELECT CONCAT(C.Nume, ' ', C.Prenume) AS Name, COUNT(*) AS Val,
    'Clientul cu cei mai multi afiliati' AS StatName
	FROM clienti C, clienti AF
	WHERE C.ClientID = AF.AfiliatID
	GROUP BY C.ClientID, C.Nume, C.Prenume
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

IF stat_id = 8 THEN
	SELECT CONCAT(C.Nume, ' ', C.Prenume) AS Name, COUNT(*) AS Val,
    'Clientul cu cei mai multi afiliati care au trimis cel putin un colet' AS StatName
	FROM clienti C, clienti AF
	WHERE C.ClientID = AF.AfiliatID AND 
    (SELECT COUNT(*) FROM colete COL WHERE COL.ExpeditorID = AF.ClientID) > 0
	GROUP BY C.ClientID, C.Nume, C.Prenume
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

IF stat_id = 9 THEN
	SELECT D.NumeDepartament AS Name, COUNT(*) AS Val,
    'Departamentul cu cei mai multi angajati care lucreaza in cel putin 2 orase' AS StatName
	FROM departamente D, angajati A
	WHERE D.DepartamentID = A.DepartamentID AND
	(SELECT COUNT(*) FROM angajati_orase AO WHERE AO.AngajatID = A.AngajatID) > 1
	GROUP BY D.DepartamentID, D.NumeDepartament
	ORDER BY COUNT(*) DESC
	LIMIT 1;
END IF;

END$$

DROP PROCEDURE IF EXISTS `StatusColet`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `StatusColet` (IN `colet_id` INT, IN `c_status` INT)  BEGIN
	UPDATE colete 
    SET Status = c_status
    WHERE ColetID = colet_id;
END$$

DROP PROCEDURE IF EXISTS `StergePromotieClient`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `StergePromotieClient` (IN `client_id` INT, IN `promotie_id` INT)  BEGIN
DELETE FROM promotii_clienti
WHERE ClientID = client_id AND PromotieID = promotie_id;
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `ActiuniManager`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `ActiuniManager` (`a_email` VARCHAR(64), `dep_id` INT, `act` INT) RETURNS VARCHAR(128) CHARSET latin1 BEGIN 
	DECLARE verif_email INT;
    DECLARE id_angajat INT;
    
	SELECT COUNT(*)
    INTO verif_email
    FROM angajati
    WHERE Email = a_email
    AND (DepartamentID IS NULL OR DepartamentID = dep_id)
    AND AngajatID != (SELECT ManagerID FROM departamente WHERE DepartamentID = dep_id);
    
    SELECT AngajatID
    INTO id_angajat
    FROM angajati
    WHERE Email = a_email;
    
    IF verif_email < 1 THEN
    	RETURN 'Adresa de email nu a fost gasita, angajatul este manager sau face parte din alt departament!';
    END IF;
    
    IF act = 1 THEN
    	UPDATE angajati SET DepartamentID = dep_id WHERE AngajatID = id_angajat;
        RETURN 'OK';
    END IF;
    
    IF act = 2 THEN
    	UPDATE angajati SET DepartamentID = NULL WHERE AngajatID = id_angajat;
        RETURN 'OK';
    END IF;
    
END$$

DROP FUNCTION IF EXISTS `ActiveazaCodAfiliat`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `ActiveazaCodAfiliat` (`client_id` INT, `friendcode` VARCHAR(20)) RETURNS INT(11) BEGIN
	DECLARE nr_users INT;
    DECLARE codeOwnerID INT;
    DECLARE clientAfiliatID INT;
    DECLARE ID_Promotie INT;
    
    
    /* verific daca exista un utilizator cu acest cod de afiliat */
    SELECT COUNT(*)
    INTO nr_users
    FROM clienti
    WHERE CodAfiliat = friendcode;
    
    IF nr_users != 1 THEN
    	RETURN 1; /* codul introdus nu a fost gasit */
    END IF;
    
    /* verific cui ii apartine acest cod */
    SELECT ClientID
    INTO codeOwnerID
    FROM clienti
    WHERE CodAfiliat = friendcode;
    
    IF client_id = codeOwnerID THEN
    	RETURN 2; /* nu poti sa-ti folosesti propriul cod */
    END IF;
    
    SELECT AfiliatID
    INTO clientAfiliatID
    FROM clienti
    WHERE ClientID = client_id;
    
    IF clientAfiliatID IS NOT NULL THEN
    	RETURN 3; /* ai folosit deja un cod de afiliat */
    END IF;
    
    /* totul este OK, activez codul */
    
    UPDATE clienti
    SET AfiliatID = codeOwnerID
    WHERE ClientID = client_id;
    
    SELECT PromotieID
    INTO ID_Promotie
    FROM promotii
    WHERE NumePromotie = "15 RON LIVRARE GRATUITA";
    
    INSERT INTO promotii_clienti(PromotieID, ClientID)
    VALUES (ID_Promotie, client_id), (ID_Promotie, codeOwnerID);
    
	RETURN 0;
END$$

DROP FUNCTION IF EXISTS `AdaugaAngajat`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `AdaugaAngajat` (`a_email` VARCHAR(64), `a_telefon` VARCHAR(12), `a_CNP` VARCHAR(20), `a_pass` VARCHAR(256), `a_nume` VARCHAR(30), `a_prenume` VARCHAR(30), `a_sex` CHAR(1)) RETURNS VARCHAR(64) CHARSET latin1 BEGIN
	DECLARE verif_email INT;
    DECLARE verif_telefon INT;
    DECLARE verif_cnp INT;
    
    SELECT COUNT(*) INTO verif_email FROM angajati WHERE Email = a_email;
    SELECT COUNT(*) INTO verif_telefon FROM angajati WHERE Telefon = a_telefon;
    SELECT COUNT(*) INTO verif_cnp FROM angajati WHERE CNP = a_CNP;
    
    IF verif_email > 0 THEN
    	RETURN 'Adresa de email a fost deja folosita!';
    END IF;
    
    IF verif_telefon > 0 THEN
    	RETURN 'Numarul de telefon a fost deja folosit!';
    END IF;
    
    IF verif_cnp > 0 THEN
    	RETURN 'Acest CNP a fost deja folosit!';
    END IF;
    
	INSERT INTO angajati(Email, Parola, Nume, Prenume, CNP, Telefon, Sex) 
    VALUES(a_email, a_pass, a_nume, a_prenume, a_CNP, a_telefon, a_sex);
    
    RETURN 'OK';
END$$

DROP FUNCTION IF EXISTS `AtribuirePromotie`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `AtribuirePromotie` (`nume_promotie` VARCHAR(30), `p_email` VARCHAR(64), `p_sex` CHAR(1), `p_oras` VARCHAR(20), `p_time` INT) RETURNS VARCHAR(64) CHARSET latin1 BEGIN
    DECLARE verif INT;
    DECLARE id_client INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE cursor1 CURSOR 
    FOR (SELECT C.ClientID 
         FROM clienti C
         WHERE C.Sex = p_sex AND C.Oras = p_oras
         AND (SELECT PromotieID FROM Promotii WHERE NumePromotie =
         nume_promotie) NOT IN (SELECT PC.PromotieID FROM promotii_clienti
         PC WHERE PC.ClientID = C.ClientID));
    
    DECLARE cursor2 CURSOR 
    FOR (SELECT C.ClientID 
         FROM clienti C
         WHERE C.Sex = p_sex
         AND (SELECT PromotieID FROM Promotii WHERE NumePromotie =
         nume_promotie) NOT IN (SELECT PC.PromotieID FROM promotii_clienti
         PC WHERE PC.ClientID = C.ClientID));
    DECLARE cursor3 CURSOR
    FOR (SELECT C.ClientID
         FROM clienti C 
         WHERE C.Oras = p_oras
         AND (SELECT PromotieID FROM Promotii WHERE NumePromotie =
         nume_promotie) NOT IN (SELECT PC.PromotieID FROM promotii_clienti
         PC WHERE PC.ClientID = C.ClientID));
    
    DECLARE cursor4 CURSOR
    FOR (SELECT C.ClientID 
         FROM clienti C
         WHERE (SELECT PromotieID FROM Promotii WHERE NumePromotie =
         nume_promotie) NOT IN (SELECT PC.PromotieID FROM promotii_clienti
         PC WHERE PC.ClientID = C.ClientID));
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    SELECT COUNT(*)
    INTO verif
    FROM promotii
    WHERE NumePromotie = nume_promotie AND Valabilitate > p_time;
    
    IF verif < 1 THEN
    	RETURN 'Promotia nu a fost gasita in baza de date sau a expirat!';
    END IF;
    
    IF p_email != '' THEN
		INSERT INTO promotii_clienti(ClientID, PromotieID) 
		VALUES((SELECT ClientID FROM clienti WHERE Email = p_email), 
		(SELECT PromotieID FROM Promotii WHERE NumePromotie = nume_promotie));
        RETURN 'OK';
    END IF;

    IF p_oras != '' AND (p_sex = 'M' OR p_sex = 'F') THEN
    	OPEN cursor1;
        read_loop: LOOP
        	FETCH cursor1 INTO id_client;
            IF done THEN
            	LEAVE read_loop;
            END IF;
            INSERT INTO promotii_clienti(ClientID, PromotieID)
            VALUES (id_client, (SELECT PromotieID FROM Promotii WHERE NumePromotie = nume_promotie));
        END LOOP;
        CLOSE cursor1;
    	RETURN 'OK';
    END IF;
    
    IF p_oras = '' AND (p_sex = 'M' OR p_sex = 'F') THEN
    	OPEN cursor2;
        read_loop: LOOP
        	FETCH cursor2 INTO id_client;
            IF done THEN
            	LEAVE read_loop;
            END IF;
            INSERT INTO promotii_clienti(ClientID, PromotieID)
            VALUES (id_client, (SELECT PromotieID FROM Promotii WHERE NumePromotie = nume_promotie));
        END LOOP;
        CLOSE cursor2;
    	RETURN 'OK';
    END IF;
    
    IF p_oras != '' AND p_sex != 'M' AND p_sex != 'F' THEN
    	OPEN cursor3;
        read_loop: LOOP
        	FETCH cursor3 INTO id_client;
            IF done THEN
            	LEAVE read_loop;
            END IF;
            INSERT INTO promotii_clienti(ClientID, PromotieID)
            VALUES (id_client, (SELECT PromotieID FROM Promotii WHERE NumePromotie = nume_promotie));
        END LOOP;
        CLOSE cursor3;
    	RETURN 'OK';
    END IF;

    OPEN cursor4;
    read_loop: LOOP
    	FETCH cursor4 INTO id_client;
    	IF done THEN
            LEAVE read_loop;
        END IF;
        INSERT INTO promotii_clienti(ClientID, PromotieID)
        VALUES (id_client, (SELECT PromotieID FROM Promotii WHERE
                           NumePromotie = nume_promotie));
    END LOOP;
    CLOSE cursor4;
    RETURN 'OK';    
END$$

DROP FUNCTION IF EXISTS `EsteManager`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `EsteManager` (`angajat_id` INT) RETURNS VARCHAR(10) CHARSET latin1 NO SQL
BEGIN
	DECLARE mng INT;
    
	SELECT COUNT(*)
    INTO mng
    FROM departamente
    WHERE ManagerID = angajat_id;
    
    IF mng > 0 THEN
    	RETURN 'DA';
    END IF;
    RETURN 'NU';
END$$

DROP FUNCTION IF EXISTS `SeteazaCodAfiliat`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `SeteazaCodAfiliat` (`client_id` INT, `codetoset` VARCHAR(20)) RETURNS INT(11) BEGIN
	DECLARE nr_clienti INT;
    
    /* Aflu daca mai are cineva acest cod de afiliat */
    SELECT COUNT(*)
    INTO nr_clienti
    FROM clienti
    WHERE CodAfiliat = codetoset;
    
    IF nr_clienti > 0 THEN
    	RETURN 4;
    END IF;
    
    /* E OK, setez codul */
    UPDATE clienti
    SET CodAfiliat = codetoset
    WHERE ClientID = client_id;
    
    RETURN 0;
END$$

DROP FUNCTION IF EXISTS `VerificareCont`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `VerificareCont` (`u_email` VARCHAR(64), `u_telefon` VARCHAR(12)) RETURNS VARCHAR(64) CHARSET latin1 BEGIN
	DECLARE nr_users_email INT;
    DECLARE nr_users_telefon INT;
    
	SELECT COUNT(*) AS nr_users
    INTO nr_users_email
    FROM clienti
    WHERE Email = u_email;
    
    SELECT COUNT(*) AS nr_users
    INTO nr_users_telefon
    FROM clienti
    WHERE Telefon = u_telefon;
    
    IF nr_users_email > 0 THEN
    	RETURN 'Adresa de email a fost deja folosita!';
    END IF;
    
    IF nr_users_telefon > 0 THEN
    	RETURN 'Numarul de telefon a fost deja folosit!';
    END IF;
    
    RETURN 'OK';
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `angajati`
--

DROP TABLE IF EXISTS `angajati`;
CREATE TABLE IF NOT EXISTS `angajati` (
  `AngajatID` int(11) NOT NULL AUTO_INCREMENT,
  `DepartamentID` int(11) DEFAULT NULL,
  `Email` varchar(64) NOT NULL,
  `Parola` varchar(256) NOT NULL,
  `Nume` varchar(20) NOT NULL,
  `Prenume` varchar(20) NOT NULL,
  `CNP` varchar(13) NOT NULL,
  `Telefon` varchar(12) NOT NULL,
  `Sex` char(1) NOT NULL,
  PRIMARY KEY (`AngajatID`),
  KEY `FK_DepartamentAngajat` (`DepartamentID`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `angajati`
--

INSERT INTO `angajati` (`AngajatID`, `DepartamentID`, `Email`, `Parola`, `Nume`, `Prenume`, `CNP`, `Telefon`, `Sex`) VALUES
(1, 1, 'tehnic@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Tudor', 'Costin-Cristian', '392193219383', '0772222222', 'M'),
(9, 2, 'marketing@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Tudor', 'Daniela', '1901010101010', '0743024512', 'F'),
(10, 4, 'comanflorin@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Coman', 'Florin', '199309239829', '0771932819', 'M'),
(11, 1, 'stanciunicu@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Stanciu', 'Nicu', '167493289432', '0732937210', 'M'),
(12, 3, 'colectori@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Tanase', 'Florin', '199309234323', '0777123184', 'M'),
(13, 4, 'livratori@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Stan', 'George', '1793278311093', '0731932954', 'M'),
(14, 3, 'andreicosmin@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Andrei', 'Cosmin', '18932678213', '0772187233', 'M'),
(16, NULL, 'mircea_dragomir@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Mircea', 'Dragomir', '123321321', '0772132131', 'M'),
(17, 4, 'cornel@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Cornel', 'Dan', '321321321321', '098432904', 'M'),
(18, NULL, 'andreea@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', 'Dan', 'Andreea', '432421123312', '05943885', 'F');

-- --------------------------------------------------------

--
-- Table structure for table `angajati_orase`
--

DROP TABLE IF EXISTS `angajati_orase`;
CREATE TABLE IF NOT EXISTS `angajati_orase` (
  `AngajatID` int(11) NOT NULL,
  `OrasID` int(11) NOT NULL,
  PRIMARY KEY (`AngajatID`,`OrasID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `angajati_orase`
--

INSERT INTO `angajati_orase` (`AngajatID`, `OrasID`) VALUES
(1, 1),
(9, 1),
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(12, 5),
(12, 7),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(13, 5),
(13, 7);

-- --------------------------------------------------------

--
-- Table structure for table `clienti`
--

DROP TABLE IF EXISTS `clienti`;
CREATE TABLE IF NOT EXISTS `clienti` (
  `ClientID` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(64) NOT NULL,
  `Parola` varchar(256) DEFAULT NULL,
  `Telefon` varchar(12) DEFAULT NULL,
  `Nume` varchar(20) DEFAULT NULL,
  `Prenume` varchar(20) DEFAULT NULL,
  `Sex` char(1) DEFAULT NULL,
  `DataNasterii` datetime DEFAULT NULL,
  `Oras` varchar(20) DEFAULT NULL,
  `Judet` varchar(20) DEFAULT NULL,
  `Strada` varchar(40) DEFAULT NULL,
  `Numar` int(11) DEFAULT NULL,
  `Bloc` varchar(6) DEFAULT NULL,
  `Scara` varchar(6) DEFAULT NULL,
  `Etaj` int(11) DEFAULT NULL,
  `Apartament` int(11) DEFAULT NULL,
  `CodPostal` varchar(10) DEFAULT NULL,
  `CodAfiliat` varchar(20) DEFAULT NULL,
  `AfiliatID` int(11) DEFAULT NULL,
  `ContActivat` char(1) NOT NULL,
  PRIMARY KEY (`ClientID`),
  UNIQUE KEY `Email` (`Email`) USING BTREE,
  UNIQUE KEY `Telefon` (`Telefon`) USING BTREE,
  KEY `FK_AfiliatID` (`AfiliatID`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clienti`
--

INSERT INTO `clienti` (`ClientID`, `Email`, `Parola`, `Telefon`, `Nume`, `Prenume`, `Sex`, `DataNasterii`, `Oras`, `Judet`, `Strada`, `Numar`, `Bloc`, `Scara`, `Etaj`, `Apartament`, `CodPostal`, `CodAfiliat`, `AfiliatID`, `ContActivat`) VALUES
(1, 'tudorcostin@yahoo.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '0770707070', 'Tudor', 'Costin', 'M', '2017-06-01 00:00:00', 'Bucuresti', 'Sector 3', 'Strada A', 1, '6A', 'A', 5, 25, '010164', 'COD123', 2, '1'),
(2, 'popescustefan@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '1234567890', 'Popescu', 'Stefan', 'M', '2017-06-01 00:00:00', 'Cluj', 'Cluj', 'Strada B', 3, 'A1', 'B', 2, 11, NULL, '1234', 1, '1'),
(7, 'tcostinnn@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '0772211111', 'Tudor', 'Costin', 'M', '1999-06-10 00:00:00', 'Bucuresti', 'Sector 3', 'Plt. Petre D. Ionescu', NULL, NULL, NULL, NULL, NULL, NULL, 'BONUS', 1, '1'),
(4, 'stanciumariana@yahoo.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '0771010101', 'Stanciu', 'Mariana', 'F', '1993-06-07 00:00:00', 'Bucuresti', 'Sector 4', 'Strada D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1'),
(18, 'becali_andreea@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '0752651624', 'Becali', 'Andreea', 'F', '1985-06-11 00:00:00', 'Bucuresti', 'Sector 6', 'Strada 10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1'),
(17, 'stan_george@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '0792312312', 'Stan', 'George', 'M', '1993-08-25 00:00:00', 'Bucuresti', 'Sector 2', 'Strada B', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1'),
(16, 'roman_serban@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '0740240241', 'Roman', 'Serban', 'M', '1983-02-15 00:00:00', 'Iasi', 'Iasi', 'Strada 7', NULL, NULL, NULL, NULL, NULL, NULL, 'COD2', 15, '1'),
(14, 'luca_david@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '0771234567', 'Luca', 'David', 'M', '1995-07-10 00:00:00', 'Cluj', 'Cluj', 'Strada 5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1'),
(15, 'stefan_cristian@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '0721212112', 'Stefan', 'Cristian', 'M', '1989-06-05 00:00:00', 'Bucuresti', 'Sector 2', 'Strada A1', NULL, NULL, NULL, NULL, NULL, NULL, 'COD1', NULL, '1'),
(19, 'stancu_ana@gmail.com', '2F9959B230A44678DD2DC29F037BA1159F233AA9AB183CE3A0678EAAE002E5AA6F27F47144A1A4365116D3DB1B58EC47896623B92D85CB2F191705DAF11858B8', '0712124365', 'Stancu', 'Ana', 'F', '1998-06-16 00:00:00', 'Bucuresti', 'Sector 4', 'Strada E', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1');

--
-- Triggers `clienti`
--
DROP TRIGGER IF EXISTS `DeleteClient`;
DELIMITER $$
CREATE TRIGGER `DeleteClient` BEFORE DELETE ON `clienti` FOR EACH ROW BEGIN
    DELETE FROM promotii_clienti WHERE ClientID = old.ClientID;
    
    DELETE FROM obiecte 
    WHERE ColetID IN (SELECT ColetID 
                      FROM colete 
                      WHERE ExpeditorID = old.ClientID
                      OR DestinatarID = old.ClientID);
                      
    DELETE FROM colete 
    WHERE ExpeditorID = old.ClientID OR DestinatarID = old.ClientID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `colete`
--

DROP TABLE IF EXISTS `colete`;
CREATE TABLE IF NOT EXISTS `colete` (
  `ColetID` int(11) NOT NULL AUTO_INCREMENT,
  `ExpeditorID` int(11) NOT NULL,
  `DestinatarID` int(11) NOT NULL,
  `ColectorID` int(11) DEFAULT NULL,
  `LivratorID` int(11) DEFAULT NULL,
  `Volum` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `Mesaj` varchar(512) NOT NULL,
  `Timestamp` int(11) NOT NULL,
  `CostLivrare` float NOT NULL,
  PRIMARY KEY (`ColetID`),
  KEY `FK_Expeditor` (`ExpeditorID`),
  KEY `FK_Destinatar` (`DestinatarID`),
  KEY `FK_Colector` (`ColectorID`),
  KEY `FK_Livrator` (`LivratorID`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `colete`
--

INSERT INTO `colete` (`ColetID`, `ExpeditorID`, `DestinatarID`, `ColectorID`, `LivratorID`, `Volum`, `Status`, `Mesaj`, `Timestamp`, `CostLivrare`) VALUES
(37, 2, 15, NULL, NULL, 100000, 1, 'Salut', 1643021024, 40),
(34, 18, 2, 12, NULL, 60000, 5, 'Coletul tau', 1643016952, 28),
(36, 19, 1, 12, 13, 60000, 7, 'colet', 1643019895, 28),
(35, 18, 1, 12, 13, 70000, 7, 'Salut', 1643019657, 31),
(33, 17, 1, 12, NULL, 75000, 4, 'Salut', 1643016722, 32),
(32, 16, 15, NULL, NULL, 70000, 1, 'Salut!', 1643016570, 31),
(31, 15, 7, NULL, NULL, 100000, 1, 'Salut', 1643016389, 40),
(30, 15, 2, 12, NULL, 70000, 3, 'colet pentru tine', 1643016354, 31),
(29, 1, 7, NULL, NULL, 60000, 1, 'salut', 1643016192, 28),
(27, 1, 14, NULL, NULL, 60000, 1, 'salut', 1643016021, 13),
(26, 14, 2, 12, NULL, 60000, 3, 'Coletul tau', 1643015986, 28),
(28, 1, 2, NULL, NULL, 300000, 2, 'Colet pentru tine', 1643016113, 100),
(25, 14, 1, NULL, NULL, 50000, 0, 'Salut!', 1643015898, 25);

--
-- Triggers `colete`
--
DROP TRIGGER IF EXISTS `InsertColete`;
DELIMITER $$
CREATE TRIGGER `InsertColete` AFTER INSERT ON `colete` FOR EACH ROW BEGIN
	DECLARE NumeExpeditor VARCHAR(30);
    DECLARE PrenumeExpeditor VARCHAR(30);
    
    SELECT Nume, Prenume
    INTO NumeExpeditor, PrenumeExpeditor
    FROM clienti
    WHERE ClientID = NEW.ExpeditorID;
    
	INSERT INTO log_colete(ColetID, Mesaj, Timp)
    VALUES (NEW.ColetID, CONCAT('Cererea de expediere a coletului a fost completata de catre clientul ', NumeExpeditor, ' ', PrenumeExpeditor), CURRENT_TIMESTAMP);
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `UpdateColete`;
DELIMITER $$
CREATE TRIGGER `UpdateColete` AFTER UPDATE ON `colete` FOR EACH ROW BEGIN
	DECLARE NumeAngajat VARCHAR(30);
    DECLARE PrenumeAngajat VARCHAR(30);
	IF OLD.LivratorID IS NULL AND NEW.LivratorID IS NOT NULL THEN
    	SELECT Nume, Prenume
        INTO NumeAngajat, PrenumeAngajat
        FROM angajati
        WHERE AngajatID = NEW.LivratorID;
    
    	INSERT INTO log_colete(ColetID, Mesaj, Timp)
        VALUES(NEW.ColetID, 
        CONCAT('Coletul a fost preluat de catre livratorul ', 
        NumeAngajat, ' ', PrenumeAngajat), CURRENT_TIMESTAMP);
    END IF;
    
	IF OLD.ColectorID IS NULL AND NEW.ColectorID IS NOT NULL THEN
    	SELECT Nume, Prenume
        INTO NumeAngajat, PrenumeAngajat
        FROM angajati
        WHERE AngajatID = NEW.ColectorID;
    
    	INSERT INTO log_colete(ColetID, Mesaj, Timp)
        VALUES(NEW.ColetID, 
        CONCAT('Coletul a fost preluat de catre colectorul ', 
        NumeAngajat, ' ', PrenumeAngajat), CURRENT_TIMESTAMP);
    END IF;

	IF NEW.Status = 7 THEN
    	INSERT INTO log_colete(ColetID, Mesaj, Timp)
        VALUES(NEW.ColetID, "Coletul a fost livrat cu succes!", CURRENT_TIMESTAMP);
    END IF;
    
    IF NEW.Status = 0 THEN
    	INSERT INTO log_colete(ColetID, Mesaj, Timp)
        VALUES(NEW.ColetID, "Coletul a fost refuzat de catre destinatar", CURRENT_TIMESTAMP);
    END IF;
                    
    IF NEW.Status = 2 THEN
    	INSERT INTO log_colete(ColetID, Mesaj, Timp)
        VALUES(NEW.ColetID, "Coletul a fost acceptat de catre destinatar", CURRENT_TIMESTAMP);
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `departamente`
--

DROP TABLE IF EXISTS `departamente`;
CREATE TABLE IF NOT EXISTS `departamente` (
  `DepartamentID` int(11) NOT NULL AUTO_INCREMENT,
  `ManagerID` int(11) DEFAULT NULL,
  `NumeDepartament` varchar(30) NOT NULL,
  PRIMARY KEY (`DepartamentID`),
  KEY `FK_Manager` (`ManagerID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departamente`
--

INSERT INTO `departamente` (`DepartamentID`, `ManagerID`, `NumeDepartament`) VALUES
(1, 1, 'Tehnic'),
(2, 9, 'Marketing'),
(3, 12, 'Colectori'),
(4, 13, 'Livratori');

-- --------------------------------------------------------

--
-- Table structure for table `log_colete`
--

DROP TABLE IF EXISTS `log_colete`;
CREATE TABLE IF NOT EXISTS `log_colete` (
  `logID` int(11) NOT NULL AUTO_INCREMENT,
  `ColetID` int(11) NOT NULL,
  `Mesaj` varchar(256) NOT NULL,
  `Timp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`logID`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_colete`
--

INSERT INTO `log_colete` (`logID`, `ColetID`, `Mesaj`, `Timp`) VALUES
(31, 30, 'Cererea de expediere a coletului a fost completata de catre clientul Stefan Cristian', '2022-01-24 11:25:54'),
(29, 28, 'Cererea de expediere a coletului a fost completata de catre clientul Tudor Costin', '2022-01-24 11:21:53'),
(30, 29, 'Cererea de expediere a coletului a fost completata de catre clientul Tudor Costin', '2022-01-24 11:23:12'),
(28, 25, 'Coletul a fost refuzat de catre destinatar', '2022-01-24 11:20:25'),
(27, 27, 'Cererea de expediere a coletului a fost completata de catre clientul Tudor Costin', '2022-01-24 11:20:21'),
(26, 26, 'Cererea de expediere a coletului a fost completata de catre clientul Luca David', '2022-01-24 11:19:46'),
(25, 25, 'Cererea de expediere a coletului a fost completata de catre clientul Luca David', '2022-01-24 11:18:18'),
(32, 31, 'Cererea de expediere a coletului a fost completata de catre clientul Stefan Cristian', '2022-01-24 11:26:29'),
(33, 32, 'Cererea de expediere a coletului a fost completata de catre clientul Roman Serban', '2022-01-24 11:29:30'),
(34, 33, 'Cererea de expediere a coletului a fost completata de catre clientul Stan George', '2022-01-24 11:32:02'),
(35, 34, 'Cererea de expediere a coletului a fost completata de catre clientul Becali Andreea', '2022-01-24 11:35:52'),
(36, 35, 'Cererea de expediere a coletului a fost completata de catre clientul Becali Andreea', '2022-01-24 12:20:57'),
(37, 36, 'Cererea de expediere a coletului a fost completata de catre clientul Stancu Ana', '2022-01-24 12:24:55'),
(38, 36, 'Coletul a fost acceptat de catre destinatar', '2022-01-24 12:25:13'),
(39, 35, 'Coletul a fost acceptat de catre destinatar', '2022-01-24 12:25:14'),
(40, 33, 'Coletul a fost acceptat de catre destinatar', '2022-01-24 12:25:15'),
(41, 36, 'Coletul a fost preluat de catre colectorul Tanase Florin', '2022-01-24 12:33:59'),
(42, 35, 'Coletul a fost preluat de catre colectorul Tanase Florin', '2022-01-24 12:34:00'),
(43, 33, 'Coletul a fost preluat de catre colectorul Tanase Florin', '2022-01-24 12:34:06'),
(44, 36, 'Coletul a fost preluat de catre livratorul Stan George', '2022-01-24 12:35:13'),
(45, 35, 'Coletul a fost preluat de catre livratorul Stan George', '2022-01-24 12:35:13'),
(46, 36, 'Coletul a fost livrat cu succes!', '2022-01-24 12:35:15'),
(47, 35, 'Coletul a fost livrat cu succes!', '2022-01-24 12:35:16'),
(48, 34, 'Coletul a fost acceptat de catre destinatar', '2022-01-24 12:36:19'),
(49, 30, 'Coletul a fost acceptat de catre destinatar', '2022-01-24 12:36:20'),
(50, 28, 'Coletul a fost acceptat de catre destinatar', '2022-01-24 12:36:21'),
(51, 26, 'Coletul a fost acceptat de catre destinatar', '2022-01-24 12:36:22'),
(52, 34, 'Coletul a fost preluat de catre colectorul Tanase Florin', '2022-01-24 12:36:33'),
(53, 30, 'Coletul a fost preluat de catre colectorul Tanase Florin', '2022-01-24 12:36:33'),
(54, 26, 'Coletul a fost preluat de catre colectorul Tanase Florin', '2022-01-24 12:36:34'),
(55, 37, 'Cererea de expediere a coletului a fost completata de catre clientul Popescu Stefan', '2022-01-24 12:43:44');

-- --------------------------------------------------------

--
-- Table structure for table `obiecte`
--

DROP TABLE IF EXISTS `obiecte`;
CREATE TABLE IF NOT EXISTS `obiecte` (
  `ObiectID` int(11) NOT NULL AUTO_INCREMENT,
  `ColetID` int(11) NOT NULL,
  `NumeObiect` varchar(30) NOT NULL,
  `Fragil` char(1) NOT NULL,
  PRIMARY KEY (`ObiectID`),
  KEY `FK_ColetObiect` (`ColetID`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `obiecte`
--

INSERT INTO `obiecte` (`ObiectID`, `ColetID`, `NumeObiect`, `Fragil`) VALUES
(64, 37, 'parfum', 'D'),
(63, 36, 'caciula', 'N'),
(62, 36, 'hanorac', 'N'),
(61, 36, 'tricou', 'N'),
(60, 36, 'geaca', 'N'),
(59, 35, 'caiet', 'N'),
(58, 35, 'carte', 'N'),
(57, 34, 'sapca', 'N'),
(56, 33, 'pix', 'N'),
(55, 33, 'creion', 'N'),
(54, 32, 'furculita', 'N'),
(53, 32, 'lingura', 'N'),
(52, 32, 'farfurie', 'D'),
(51, 31, 'minge de fotbal', 'N'),
(50, 30, 'magnet de frigider', 'D'),
(49, 30, 'dulciuri', 'N'),
(48, 30, 'mousepad', 'N'),
(47, 29, 'papuci', 'N'),
(46, 28, 'casti', 'N'),
(45, 28, 'tastatura', 'N'),
(44, 28, 'televizor', 'D'),
(43, 27, 'cana', 'D'),
(42, 26, 'tablou', 'D'),
(41, 26, 'vaza', 'D'),
(40, 25, 'tricou', 'N'),
(39, 25, 'hanorac', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `orase`
--

DROP TABLE IF EXISTS `orase`;
CREATE TABLE IF NOT EXISTS `orase` (
  `OrasID` int(11) NOT NULL AUTO_INCREMENT,
  `NumeOras` varchar(20) NOT NULL,
  PRIMARY KEY (`OrasID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orase`
--

INSERT INTO `orase` (`OrasID`, `NumeOras`) VALUES
(1, 'Bucuresti'),
(2, 'Cluj'),
(3, 'Iasi'),
(4, 'Craiova'),
(5, 'Timisoara'),
(7, 'Constanta');

-- --------------------------------------------------------

--
-- Table structure for table `promotii`
--

DROP TABLE IF EXISTS `promotii`;
CREATE TABLE IF NOT EXISTS `promotii` (
  `PromotieID` int(11) NOT NULL AUTO_INCREMENT,
  `NumePromotie` varchar(30) NOT NULL,
  `DiscountLei` int(11) NOT NULL,
  `Valabilitate` int(11) NOT NULL,
  PRIMARY KEY (`PromotieID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotii`
--

INSERT INTO `promotii` (`PromotieID`, `NumePromotie`, `DiscountLei`, `Valabilitate`) VALUES
(1, 'Livrare gratuita', 1000, 0),
(8, '15 RON LIVRARE GRATUITA', 15, 1746533742),
(7, '30 LEI BONUS', 30, 1612077810);

-- --------------------------------------------------------

--
-- Table structure for table `promotii_clienti`
--

DROP TABLE IF EXISTS `promotii_clienti`;
CREATE TABLE IF NOT EXISTS `promotii_clienti` (
  `PromotieID` int(11) NOT NULL,
  `ClientID` int(11) NOT NULL,
  PRIMARY KEY (`PromotieID`,`ClientID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotii_clienti`
--

INSERT INTO `promotii_clienti` (`PromotieID`, `ClientID`) VALUES
(8, 15),
(8, 16);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
