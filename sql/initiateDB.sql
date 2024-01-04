--
-- drop all tables first
--

DROP TABLE AdopterAddress CASCADE CONSTRAINTS;
DROP TABLE Registered CASCADE CONSTRAINTS;
DROP TABLE AdopterPersonal CASCADE CONSTRAINTS;
DROP TABLE PetBreed CASCADE CONSTRAINTS;
DROP TABLE Belong CASCADE CONSTRAINTS;
DROP TABLE PetInfo CASCADE CONSTRAINTS;
DROP TABLE AdoptionCertificate CASCADE CONSTRAINTS;
DROP TABLE EmployeePersonal CASCADE CONSTRAINTS;
DROP TABLE Sponsors CASCADE CONSTRAINTS;
DROP TABLE BranchDetails CASCADE CONSTRAINTS;
DROP TABLE BranchAddress CASCADE CONSTRAINTS;
DROP TABLE EmployeeAddress CASCADE CONSTRAINTS;
DROP TABLE FosterFamilyDetails CASCADE CONSTRAINTS;
DROP TABLE FosterFamilyAddress CASCADE CONSTRAINTS;
DROP TABLE EventDetails CASCADE CONSTRAINTS;
DROP TABLE EventAddress CASCADE CONSTRAINTS;
DROP TABLE Donor CASCADE CONSTRAINTS;
DROP TABLE TransportService CASCADE CONSTRAINTS;
DROP TABLE PetRetailer CASCADE CONSTRAINTS;
DROP TABLE SponsorDetails CASCADE CONSTRAINTS;
DROP TABLE SponsorAddress CASCADE CONSTRAINTS;

--
-- create tables
--

CREATE TABLE AdopterAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(15)
);
CREATE TABLE AdopterPersonal
(
    adopterID  VARCHAR(12),
    name       VARCHAR(15)        NOT NULL,
    email      VARCHAR(30) UNIQUE NOT NULL,
    phone      VARCHAR(12) UNIQUE NOT NULL,
    postalCode VARCHAR(12)        NOT NULL,
    street     VARCHAR(30),
    PRIMARY KEY (adopterID),
    FOREIGN KEY (postalCode) REFERENCES AdopterAddress (postalCode)
);
CREATE TABLE PetBreed
(
    breed   VARCHAR(20) NOT NULL PRIMARY KEY,
    species VARCHAR(12) NOT NULL
);
CREATE TABLE BranchAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(15)
);
CREATE TABLE BranchDetails
(
    branchID   VARCHAR(12) PRIMARY KEY,
    branchName VARCHAR(20)        NOT NULL,
    phone      VARCHAR(12) UNIQUE NOT NULL,
    email      VARCHAR(30) UNIQUE NOT NULL,
    postalCode VARCHAR(12)        NOT NULL,
    street     VARCHAR(30),
    FOREIGN KEY (postalCode) REFERENCES BranchAddress (postalCode)
);
CREATE TABLE FosterFamilyAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(15)
);
CREATE TABLE FosterFamilyDetails
(
    fosterFamilyID VARCHAR(12) PRIMARY KEY,
    email          VARCHAR(25) UNIQUE NOT NULL,
    phone          VARCHAR(12) UNIQUE NOT NULL,
    name           VARCHAR(30)        NOT NULL,
    postalCode     VARCHAR(12)        NOT NULL,
    street         VARCHAR(30),
    FOREIGN KEY (postalCode) REFERENCES FosterFamilyAddress (postalCode)
);
CREATE TABLE PetInfo
(
    petID          VARCHAR(12),
    name           VARCHAR(30) NOT NULL,
    weight         INTEGER,
    color          VARCHAR(12),
    age            INTEGER,
    breed          VARCHAR(20) NOT NULL,
    adopterID      VARCHAR(12),
    branchID       VARCHAR(12) NOT NULL,
    fosterFamilyID VARCHAR(12),
    PRIMARY KEY (petID),
    FOREIGN KEY (adopterID) REFERENCES AdopterPersonal (adopterID),
    FOREIGN KEY (branchID) REFERENCES BranchDetails (branchID),
    FOREIGN KEY (fosterFamilyID) REFERENCES FosterFamilyDetails (fosterFamilyID),
    FOREIGN KEY (breed) REFERENCES PetBreed (breed)
);
CREATE TABLE AdoptionCertificate
(
    certID    VARCHAR(12),
    "DATE"    DATE,
    adopterID VARCHAR(12),
    petID     VARCHAR(12),
    PRIMARY KEY (certID, adopterID, petID),
    FOREIGN KEY (adopterID) REFERENCES AdopterPersonal (adopterID),
    FOREIGN KEY (petID) REFERENCES PetInfo (petID)
);
CREATE TABLE EmployeeAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(15)
);
CREATE TABLE EmployeePersonal
(
    employeeID VARCHAR(12),
    postalCode VARCHAR(12)        NOT NULL,
    name       VARCHAR(30)        NOT NULL,
    email      VARCHAR(30) UNIQUE NOT NULL,
    phone      VARCHAR(12) UNIQUE NOT NULL,
    role       VARCHAR(12),
    branchID   VARCHAR(12)        NOT NULL,
    street     VARCHAR(20),
    PRIMARY KEY (employeeID),
    FOREIGN KEY (branchID) REFERENCES BranchDetails (branchID),
    FOREIGN KEY (postalCode) REFERENCES EmployeeAddress (postalCode)
);
CREATE TABLE SponsorAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(15)
);
CREATE TABLE SponsorDetails
(
    sponsorID  VARCHAR(12),
    name       VARCHAR(30)        NOT NULL,
    phone      VARCHAR(12) UNIQUE NOT NULL,
    postalCode VARCHAR(12)        NOT NULL,
    email      VARCHAR(30) UNIQUE NOT NULL,
    street     VARCHAR(30),
    PRIMARY KEY (sponsorID),
    FOREIGN KEY (postalCode) REFERENCES SponsorAddress (postalCode)
);
CREATE TABLE EventAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(15)
);
CREATE TABLE EventDetails
(
    eventID    VARCHAR(12) PRIMARY KEY,
    name       VARCHAR(30) NOT NULL,
    "DATE"     DATE        NOT NULL,
    postalCode VARCHAR(12) NOT NULL,
    sponsorID  VARCHAR(12),
    street     VARCHAR(15),
    FOREIGN KEY (sponsorID) REFERENCES SponsorDetails (sponsorID),
    FOREIGN KEY (postalCode) REFERENCES EventAddress (postalCode)
);
CREATE TABLE Donor
(
    sponsorID VARCHAR(12) PRIMARY KEY,
    amount    INTEGER NOT NULL,
    "DATE"    DATE    NOT NULL,
    FOREIGN KEY (sponsorID) REFERENCES SponsorDetails (sponsorID)
);
CREATE TABLE TransportService
(
    sponsorID         VARCHAR(12) PRIMARY KEY,
    connectedLocation VARCHAR(15) NOT NULL,
    FOREIGN KEY (sponsorID) REFERENCES SponsorDetails (sponsorID)
);
CREATE TABLE PetRetailer
(
    sponsorID    VARCHAR(12) PRIMARY KEY,
    businessType VARCHAR(15) NOT NULL,
    FOREIGN KEY (sponsorID) REFERENCES SponsorDetails (sponsorID)
);
CREATE TABLE Registered
(
    adopterID VARCHAR(12),
    branchID  VARCHAR(12),
    "DATE"    DATE NOT NULL,
    PRIMARY KEY (adopterID, branchID),
    FOREIGN KEY (adopterID) REFERENCES AdopterPersonal (adopterID),
    FOREIGN KEY (branchID) REFERENCES BranchDetails (branchID)
);
CREATE TABLE Belong
(
    petID      VARCHAR(12),
    branchID   VARCHAR(12),
    rescuedate DATE NOT NULL,
    PRIMARY KEY (petID, branchID),
    FOREIGN KEY (petID) REFERENCES PetInfo (petID),
    FOREIGN KEY (branchID) REFERENCES BranchDetails (branchID)
);
CREATE TABLE Sponsors
(
    sponsorID VARCHAR(12),
    branchID  VARCHAR(12),
    startDate DATE NOT NULL,
    PRIMARY KEY (sponsorID, branchID),
    FOREIGN KEY (sponsorID) REFERENCES SponsorDetails (sponsorID),
    FOREIGN KEY (branchID) REFERENCES BranchDetails (branchID)
);

--
-- insert initial data 
--

INSERT INTO AdopterAddress VALUES ('V6Q 3T7', 'Coquitlam');
INSERT INTO AdopterAddress VALUES('V5R 2Z1', 'Delta');
INSERT INTO AdopterAddress VALUES('V6Y 3P2', 'Burnaby');
INSERT INTO AdopterAddress VALUES('V7X 4Q4', 'New Westminster');
INSERT INTO AdopterAddress VALUES('V4W 5J1', 'Surrey');

INSERT INTO AdopterPersonal VALUES ('1', 'John Smith', 'john.smith@example.com', '123-456-7890',
'V6Q 3T7','1st ave');
INSERT INTO AdopterPersonal VALUES ('2', 'Alice Johnson', 'alice.j@example.com', '987-654-3210',
'V5R 2Z1','2nd ave');
INSERT INTO AdopterPersonal VALUES ('3', 'David Lee', 'david.lee@example.com', '555-123-4567',
'V6Y 3P2','3rd ave');
INSERT INTO AdopterPersonal VALUES ('4', 'Emily White', 'emily.w@example.com', '444-555-6666',
'V7X 4Q4','4th ave');
INSERT INTO AdopterPersonal VALUES ('5', 'Michael Brown', 'michael.b@example.com', '777-888-9999',
'V4W 5J1','5th ave');

INSERT INTO BranchAddress VALUES ('V6T 1Z1', 'Vancouver');
INSERT INTO BranchAddress VALUES ('V5K 2B3', 'Burnaby');
INSERT INTO BranchAddress VALUES ('V2S 1S2', 'Surrey');
INSERT INTO BranchAddress VALUES ('V7Y 1C6', 'Richmond');
INSERT INTO BranchAddress VALUES ('V4A 3K9', 'Langley');

INSERT INTO BranchDetails VALUES ('A001', 'Vancouver Branch', '604-123-4567', 'vancouver@example.com', 'V6T 1Z1', '11th st');
INSERT INTO BranchDetails VALUES ('B002', 'Burnaby Branch', '604-987-6543', 'burnaby@example.com', 'V5K 2B3', '12th st');
INSERT INTO BranchDetails VALUES ('C003', 'Surrey Branch', '604-555-7777', 'surrey@example.com', 'V2S 1S2', '13th st');
INSERT INTO BranchDetails VALUES ('D004', 'Richmond Branch', '604-888-9999', 'richmond@example.com', 'V7Y 1C6', '14th st');
INSERT INTO BranchDetails VALUES ('E005', 'Langley Branch', '604-111-2222', 'langley@example.com', 'V4A 3K9', '15th st');

INSERT INTO FosterFamilyAddress VALUES ('V6X 4Z2', 'New Westminster');
INSERT INTO FosterFamilyAddress VALUES ('V5L 3R6', 'Coquitlam');
INSERT INTO FosterFamilyAddress VALUES ('V2K 1E3', 'Maple Ridge');
INSERT INTO FosterFamilyAddress VALUES ('V7T 2W7', 'Delta');
INSERT INTO FosterFamilyAddress VALUES ('V4P 6H8', 'North Vancouver');

INSERT INTO FosterFamilyDetails VALUES ('F001', 'foster1@example.com', '123-456-7890', 'Smith Family', 'V6X 4Z2', 'f1 Ave');
INSERT INTO FosterFamilyDetails VALUES ('F002', 'foster2@example.com', '987-654-3210', 'Johnson Family', 'V5L 3R6', 'f2 Ave');
INSERT INTO FosterFamilyDetails VALUES ('F003', 'foster3@example.com', '555-123-4567', 'Lee Family', 'V2K 1E3', 'f3 Ave');
INSERT INTO FosterFamilyDetails VALUES ('F004', 'foster4@example.com', '444-555-6666', 'White Family', 'V7T 2W7', 'f4 Ave');
INSERT INTO FosterFamilyDetails VALUES ('F005', 'foster5@example.com', '777-888-9999', 'Brown Family', 'V4P 6H8', 'f5 Ave');

INSERT INTO PetBreed VALUES ('Labrador Retriever', 'Dog');
INSERT INTO PetBreed VALUES ('Siamese', 'Cat');
INSERT INTO PetBreed VALUES ('Canary', 'Bird');
INSERT INTO PetBreed VALUES ('Persian', 'Cat');
INSERT INTO PetBreed VALUES ('Domestic Cat', 'Cat');
INSERT INTO PetBreed VALUES ('Golden Retriever', 'Dog');

INSERT INTO PetInfo VALUES ('P001', 'Buddy', 20, 'Brown', 3, 'Labrador Retriever', '1', 'A001', NULL);
INSERT INTO PetInfo VALUES ('P002', 'Whiskers', 5, 'Gray', 2, 'Siamese', '2', 'B002', NULL);
INSERT INTO PetInfo VALUES ('P003', 'Fluffy', 15, 'White', 4, 'Persian', '3', 'C003', NULL);
INSERT INTO PetInfo VALUES ('P004', 'Rocky', 18, 'Golden', 2, 'Golden Retriever', NULL, 'A001', 'F001');
INSERT INTO PetInfo VALUES ('P005', 'Tweety', 1, 'Yellow', 3, 'Canary', NULL, 'B002', NULL);
INSERT INTO PetInfo VALUES ('P006', 'Minni', 5, 'Tabby Yellow', 9, 'Domestic Cat', NULL, 'B002', 'F001');
INSERT INTO PetInfo VALUES ('P007', 'Chibi', 3, 'Tabby Grey', 5, 'Domestic Cat', NULL, 'C003', 'F002');
INSERT INTO PetInfo VALUES ('P008', 'Jiro', 4, 'Tuxedo', 1, 'Domestic Cat', NULL, 'C003', 'F001');
INSERT INTO PetInfo VALUES ('P009', 'Taro', 5, 'Blue', 1, 'Domestic Cat', NULL, 'D004', NULL);
INSERT INTO PetInfo VALUES ('P010', 'Popo', 6, 'Ginger', 1, 'Domestic Cat', NULL, 'D004', 'F003');

INSERT INTO AdoptionCertificate VALUES ('Cert1', SYSDATE, '1', 'P001');
INSERT INTO AdoptionCertificate VALUES ('Cert2', SYSDATE, '2', 'P002');
INSERT INTO AdoptionCertificate VALUES ('Cert3', SYSDATE, '3', 'P003');
INSERT INTO AdoptionCertificate VALUES ('Cert4', SYSDATE, '4', 'P004');
INSERT INTO AdoptionCertificate VALUES ('Cert5', SYSDATE, '5', 'P005');

INSERT INTO EmployeeAddress VALUES ('V6T 1Z1', 'Vancouver');
INSERT INTO EmployeeAddress VALUES ('V5K 2B3', 'Burnaby');
INSERT INTO EmployeeAddress VALUES ('V2S 1S2', 'Surrey');
INSERT INTO EmployeeAddress VALUES ('V7Y 1C6', 'Richmond');
INSERT INTO EmployeeAddress VALUES ('V4A 3K9', 'Langley');

INSERT INTO EmployeePersonal VALUES ('E001', 'V6T 1Z1', 'John Employee', 'john.employee@example.com', '123-456-7890', 'Manager', 'A001', 'ab Ave');
INSERT INTO EmployeePersonal VALUES ('E002', 'V5K 2B3', 'Alice Employee', 'alice.employee@example.com', '987-654-3210', 'Clerk', 'C003', '20th Ave');
INSERT INTO EmployeePersonal VALUES ('E003', 'V2S 1S2', 'David Employee', 'david.employee@example.com', '555-123-4567', 'Veterinarian', 'B002', '21st Ave');
INSERT INTO EmployeePersonal VALUES ('E004', 'V7Y 1C6', 'Emily Employee', 'emily.employee@example.com', '444-555-6666', 'Clerk', 'C003','22nd Ave');
INSERT INTO EmployeePersonal VALUES ('E005', 'V4A 3K9', 'Michael Employee', 'michael.employee@example.com', '777-888-9999', 'Manager', 'D004', '23rd Ave');

INSERT INTO SponsorAddress VALUES ('V6T 1Z1', 'Vancouver');
INSERT INTO SponsorAddress VALUES ('V5K 2B3', 'Burnaby');
INSERT INTO SponsorAddress VALUES ('V2S 1S2', 'Surrey');
INSERT INTO SponsorAddress VALUES ('V7Y 1C6', 'Richmond');
INSERT INTO SponsorAddress VALUES ('V4A 3K9', 'Langley');
INSERT INTO SponsorAddress VALUES ('V6M 3X5', 'West Vancouver');
INSERT INTO SponsorAddress VALUES ('V5R 2T4', 'Port Coquitlam');
INSERT INTO SponsorAddress VALUES ('V2L 1K7', 'White Rock');
INSERT INTO SponsorAddress VALUES ('V7U 4Y1', 'Squamish');
INSERT INTO SponsorAddress VALUES ('V4W 5P2', 'Abbotsford');
INSERT INTO SponsorAddress VALUES ('V6V 4W5', 'Coquitlam');
INSERT INTO SponsorAddress VALUES ('V5J 2R3', 'New Westminster');
INSERT INTO SponsorAddress VALUES ('V2P 1M2', 'Langley City');
INSERT INTO SponsorAddress VALUES ('V7V 4R3', 'Delta');
INSERT INTO SponsorAddress VALUES ('V4S 5Q7', 'North Vancouver');
INSERT INTO SponsorAddress VALUES ('V6X 4T8', 'Kamloops');

INSERT INTO SponsorDetails VALUES ('D001', 'Mash Meollow', '123-456-7890', 'V6T 1Z1', 'mashmeollow@example.com', 'd1 st');
INSERT INTO SponsorDetails VALUES ('D002', 'Micchi Fatty', '987-654-3210', 'V5K 2B3', 'micchifatty@example.com', 'd2 st');
INSERT INTO SponsorDetails VALUES ('D003', 'Poop Scooper', '555-123-4567', 'V2S 1S2', 'poopscooper@example.com', 'd3 st');
INSERT INTO SponsorDetails VALUES ('D004', 'Human Servant', '444-555-6666', 'V7Y 1C6', 'humanservant@example.com', 'd4 st');
INSERT INTO SponsorDetails VALUES ('D005', 'Finicky Floof', '777-888-9999', 'V4A 3K9', 'finickyfloof@example.com', 'd5 st');
INSERT INTO SponsorDetails VALUES ('TS001', 'GHI Corporation', '111-222-3333', 'V6M 3X5', 'ghi@example.com', 'ts1 st');
INSERT INTO SponsorDetails VALUES ('TS002', 'RST Inc.', '333-444-5555', 'V5R 2T4', 'rst@example.com', 'ts2 st');
INSERT INTO SponsorDetails VALUES ('TS003', 'UVW Ltd.', '888-777-6666', 'V2L 1K7', 'uvw@example.com', 'ts3 st');
INSERT INTO SponsorDetails VALUES ('TS004', 'DEF Enterprises', '999-888-7777', 'V7U 4Y1', 'def@example.com', 'ts4 st');
INSERT INTO SponsorDetails VALUES ('TS005', 'MNO Company', '222-333-4444', 'V4W 5P2', 'mno@example.com', 'ts5 st');
INSERT INTO SponsorDetails VALUES ('PR001', 'IJK Corporation', '444-333-2222', 'V6V 4W5', 'ijk@example.com', 'pr1 st');
INSERT INTO SponsorDetails VALUES ('PR002', 'PST Inc.', '666-777-8888', 'V5J 2R3', 'pst@example.com', 'pr2 st');
INSERT INTO SponsorDetails VALUES ('PR003', 'WXY Ltd.', '222-555-7777', 'V2P 1M2', 'wxy@example.com', 'pr3 st');
INSERT INTO SponsorDetails VALUES ('PR004', 'EFG Enterprises', '888-222-7777', 'V7V 4R3', 'efg@example.com', 'pr4 st');
INSERT INTO SponsorDetails VALUES ('PR005', 'NOP Company', '777-999-5555', 'V4S 5Q7', 'nop@example.com', 'pr5 st');
INSERT INTO SponsorDetails VALUES ('S016', 'QRS Corporation', '111-444-9999', 'V6X 4T8', 'qrs@example.com', 's16 st');

INSERT INTO EventAddress VALUES ('V6T 1Z1', 'Vancouver');
INSERT INTO EventAddress VALUES ('V5K 2B3', 'Burnaby');
INSERT INTO EventAddress VALUES ('V2S 1S2', 'Surrey');
INSERT INTO EventAddress VALUES ('V7Y 1C6', 'Richmond');
INSERT INTO EventAddress VALUES ('V4A 3K9', 'Langley');

INSERT INTO EventDetails VALUES ('E001', 'Pet Adoption Event', TO_DATE('2023-11-15', 'YYYY-MM-DD'), 'V6T 1Z1', 'PR001', '30th ave');
INSERT INTO EventDetails VALUES ('E002', 'Animal Charity Gala', TO_DATE('2023-12-10', 'YYYY-MM-DD'), 'V5K 2B3', 'PR002', '31st ave');
INSERT INTO EventDetails VALUES ('E003', 'Pet Expo', TO_DATE('2023-10-20', 'YYYY-MM-DD'), 'V2S 1S2', 'PR003', '32nd ave');
INSERT INTO EventDetails VALUES ('E004', 'Fur Ball Fundraiser', TO_DATE('2023-11-30', 'YYYY-MM-DD'), 'V7Y 1C6', 'PR004', '33th ave');
INSERT INTO EventDetails VALUES ('E005', 'Paws for a Cause Walk', TO_DATE('2023-09-25', 'YYYY-MM-DD'), 'V4A 3K9', 'PR005', '34th ave');

INSERT INTO Donor VALUES ('D001', 500, TO_DATE('2023-11-15', 'YYYY-MM-DD'));
INSERT INTO Donor VALUES ('D002', 1000, TO_DATE('2023-12-10', 'YYYY-MM-DD'));
INSERT INTO Donor VALUES ('D003', 750, TO_DATE('2023-10-20', 'YYYY-MM-DD'));
INSERT INTO Donor VALUES ('D004', 1200, TO_DATE('2023-11-30', 'YYYY-MM-DD'));
INSERT INTO Donor VALUES ('D005', 900, TO_DATE('2023-09-25', 'YYYY-MM-DD'));

INSERT INTO TransportService VALUES ('TS001', 'Victoria');
INSERT INTO TransportService VALUES ('TS002', 'Kelowna');
INSERT INTO TransportService VALUES ('TS003', 'Nanaimo');
INSERT INTO TransportService VALUES ('TS004', 'Kamloops');
INSERT INTO TransportService VALUES ('TS005', 'Prince George');

INSERT INTO PetRetailer VALUES ('PR001', 'Pet Store');
INSERT INTO PetRetailer VALUES ('PR002', 'Pet Supplies');
INSERT INTO PetRetailer VALUES ('PR003', 'Pet Shop');
INSERT INTO PetRetailer VALUES ('PR004', 'Pet Accessories');
INSERT INTO PetRetailer VALUES ('PR005', 'Pet Boutique');

INSERT INTO Registered VALUES ('1', 'A001', TO_DATE('2023-11-15', 'YYYY-MM-DD'));
INSERT INTO Registered VALUES ('2', 'B002', TO_DATE('2023-12-10', 'YYYY-MM-DD'));
INSERT INTO Registered VALUES ('3', 'C003', TO_DATE('2023-10-20', 'YYYY-MM-DD'));
INSERT INTO Registered VALUES ('4', 'D004', TO_DATE('2023-11-30', 'YYYY-MM-DD'));
INSERT INTO Registered VALUES ('5', 'E005', TO_DATE('2023-09-25', 'YYYY-MM-DD'));

INSERT INTO Belong VALUES ('P001', 'A001', TO_DATE('2023-11-15', 'YYYY-MM-DD'));
INSERT INTO Belong VALUES ('P002', 'B002', TO_DATE('2023-12-10', 'YYYY-MM-DD'));
INSERT INTO Belong VALUES ('P003', 'C003', TO_DATE('2023-10-20', 'YYYY-MM-DD'));
INSERT INTO Belong VALUES ('P004', 'D004', TO_DATE('2023-11-30', 'YYYY-MM-DD'));
INSERT INTO Belong VALUES ('P005', 'E005', TO_DATE('2023-09-25', 'YYYY-MM-DD'));

INSERT INTO Sponsors VALUES ('D001', 'A001', TO_DATE('2023-11-15', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('D002', 'B002', TO_DATE('2023-12-10', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('D003', 'C003', TO_DATE('2023-10-20', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('D004', 'D004', TO_DATE('2023-11-30', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('D005', 'E005', TO_DATE('2023-09-25', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('TS001', 'A001', TO_DATE('2023-08-15', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('TS002', 'B002', TO_DATE('2023-07-10', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('TS003', 'C003', TO_DATE('2023-06-20', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('TS004', 'D004', TO_DATE('2023-05-30', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('TS005', 'E005', TO_DATE('2023-04-25', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('PR001', 'A001', TO_DATE('2023-03-15', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('PR002', 'A001', TO_DATE('2023-10-12', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('PR003', 'A001', TO_DATE('2023-03-14', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('PR004', 'A001', TO_DATE('2023-07-14', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('PR005', 'A001', TO_DATE('2023-10-13', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('PR002', 'B002', TO_DATE('2023-02-10', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('PR003', 'C003', TO_DATE('2023-01-20', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('PR004', 'D004', TO_DATE('2023-12-30', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('PR005', 'E005', TO_DATE('2023-11-25', 'YYYY-MM-DD'));
INSERT INTO Sponsors VALUES ('S016', 'E005', TO_DATE('2023-11-26', 'YYYY-MM-DD'));
commit;
