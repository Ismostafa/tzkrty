-- Create Tables
-------------Database creation-------------
--create database CompanyDBLab4
Create database consultation
GO
use consultation
CREATE TABLE UserType (
    UserTypeID INT PRIMARY KEY,
    UserTypeDescription VARCHAR(50)
);

CREATE TABLE Users (
    UserID INT IDENTITY(1,1) PRIMARY KEY ,
    Username VARCHAR(255) UNIQUE,
    Password VARCHAR(100),
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    BirthDate DATE,
    Gender VARCHAR(255),
    City VARCHAR(255),
    Address VARCHAR(255),
    EmailAddress VARCHAR(255),
    Role VARCHAR(255),
    UserType INT,
    FOREIGN KEY (UserType) REFERENCES UserType(UserTypeID)
);

CREATE TABLE Teams (
    TeamID INT IDENTITY(1,1) PRIMARY KEY ,
    TeamLogo varchar(50),
    TeamName VARCHAR(50)
);

CREATE TABLE Venues (
    VenueID INT IDENTITY(1,1) PRIMARY KEY ,
    VenueName VARCHAR(100),
    SeatCapacity INT,
    OtherDetails VARCHAR(255)
);

CREATE TABLE Matches (
    MatchID INT IDENTITY(1,1) PRIMARY KEY ,
    HomeTeamID INT,
    AwayTeamID INT,
    VenueID INT,
    DateTime DATETIME,
    MainReferee VARCHAR(255),
    Linesman1 VARCHAR(255),
    Linesman2 VARCHAR(255),
    FOREIGN KEY (HomeTeamID) REFERENCES Teams(TeamID)  ,
    FOREIGN KEY (AwayTeamID) REFERENCES Teams(TeamID) ,
    FOREIGN KEY (VenueID) REFERENCES Venues(VenueID) 
);

CREATE TABLE SeatStatus (
    SeatNumber INT IDENTITY(1,1) PRIMARY KEY ,
   
    MatchID INT,
	
		
Status INT CHECK (Status IN (0, 1)), /* 0 for Vacant, 1 for Reserved */
   FOREIGN KEY (MatchID) REFERENCES Matches(MatchID) on delete cascade on update cascade
);

CREATE TABLE Reservations (
    /* ReservationID auto generated random integer */
    ReservationID UNIQUEIDENTIFIER PRIMARY KEY DEFAULT NEWID(),
    MatchID INT,
    UserID INT,
    CreditCardNumber INT,
    PINNumber INT,
    ReservationDateTime DATETIME,
    Status VARCHAR(20),/* Reserved, Cancelled */
    FOREIGN KEY (MatchID) REFERENCES Matches(MatchID) on delete cascade on update cascade,
    FOREIGN KEY (UserID) REFERENCES Users(UserID) on delete cascade on update cascade

);
CREATE TABLE ReservedSeats (
    ReservationID UNIQUEIDENTIFIER ,
    SeatNumberReserved INT,
    FOREIGN KEY (ReservationID) REFERENCES Reservations(ReservationID),
    FOREIGN KEY (SeatNumberReserved) REFERENCES SeatStatus(SeatNumber)
);

insert into Teams(TeamID,TeamLogo,TeamName) values(5,'Al_Mokawloon_Al_Arab_SC_logo.png','Al-Mokawloon')
insert into Teams(TeamID,TeamLogo,TeamName) values(6,'Al_Masry_SC_logo.png','Al-Masry')
insert into Teams(TeamID,TeamLogo,TeamName) values(7,'Ceramica_Cleopatra_FC_logo.png','CeramicaCleopatra')
insert into Teams(TeamID,TeamLogo,TeamName) values(8,'El_Gouna_FC_Logo.png','El_Gouna')
insert into Teams(TeamID,TeamLogo,TeamName) values(9,'EasternCompanyLogo.png','EasternCompany')
insert into Teams(TeamID,TeamLogo,TeamName) values(10,'ENPPI_Club_Logo.png','ENPPI')
insert into Teams(TeamID,TeamLogo,TeamName) values(11,'Ghazllogo.png','Ghazl-ElMahalla')
insert into Teams(TeamID,TeamLogo,TeamName) values(12,'Ismaily_SC_logo.png','IsmailySC')
insert into Teams(TeamID,TeamLogo,TeamName) values(13,'Misr_Lel_Makkasa_logo.png','Misr-Lel-Makkasa')
insert into Teams(TeamID,TeamLogo,TeamName) values(14,'Logo_du_Modern_Future_FC.png','Modern Future')
insert into Teams(TeamID,TeamLogo,TeamName) values(15,'NBOEC.jpg','National Bank Of Egypt')
insert into Teams(TeamID,TeamLogo,TeamName) values(16,'Pharco_FC_Logo.png','Pharco FC')
insert into Teams(TeamID,TeamLogo,TeamName) values(17,'Pyramids_FClogo.png','Pyramids Fc')
insert into Teams(TeamID,TeamLogo,TeamName) values(18,'Tala"ea_El_Gaish_Logo.png','Talaea ElGaish')

insert into UserType values (1, 'Admin')
insert into UserType values (2, 'Manager')
insert into UserType values (3, 'Fan')
insert into UserType values(4,'pending')