CREATE TABLE AdopterAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(12)
);
CREATE TABLE AdopterPersonal
(
    adopterID  VARCHAR(12),
    name       VARCHAR(12)        NOT NULL,
    email      VARCHAR(12) UNIQUE NOT NULL,
    phone      VARCHAR(12) UNIQUE NOT NULL,
    postalCode VARCHAR(12)        NOT NULL,
    street     VARCHAR(12),
    PRIMARY KEY (adopterID),
    FOREIGN KEY (postalCode) REFERENCES AdopterAddress (postalCode)
);
CREATE TABLE PetBreed
(
    breed   VARCHAR(12) NOT NULL PRIMARY KEY,
    species VARCHAR(12) NOT NULL
);
CREATE TABLE BranchAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(12)
);
CREATE TABLE BranchDetails
(
    branchID   VARCHAR(12) PRIMARY KEY,
    branchName VARCHAR(12)        NOT NULL,
    phone      VARCHAR(12) UNIQUE NOT NULL,
    email      VARCHAR(12) UNIQUE NOT NULL,
    postalCode VARCHAR(12)        NOT NULL,
    street     VARCHAR(12),
    FOREIGN KEY (postalCode) REFERENCES BranchAddress (postalCode)
);
CREATE TABLE FosterFamilyAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(12)
);
CREATE TABLE FosterFamilyDetails
(
    fosterFamilyID VARCHAR(12) PRIMARY KEY,
    email          VARCHAR(12) UNIQUE NOT NULL,
    phone          VARCHAR(12) UNIQUE NOT NULL,
    name           VARCHAR(12)        NOT NULL,
    postalCode     VARCHAR(12)        NOT NULL,
    street         VARCHAR(12),
    FOREIGN KEY (postalCode) REFERENCES FosterFamilyAddress (postalCode)
);
CREATE TABLE PetInfo
(
    petID          VARCHAR(12),
    name           VARCHAR(12) NOT NULL,
    weight         INTEGER,
    color          VARCHAR(12),
    age            INTEGER,
    breed          VARCHAR(12) NOT NULL,
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
    city       VARCHAR(12)
);
CREATE TABLE EmployeePersonal
(
    employeeID VARCHAR(12),
    postalCode VARCHAR(12)        NOT NULL,
    street     VARCHAR(12),
    name       VARCHAR(12)        NOT NULL,
    email      VARCHAR(12) UNIQUE NOT NULL,
    phone      VARCHAR(12) UNIQUE NOT NULL,
    role       VARCHAR(12),
    branchID   VARCHAR(12)        NOT NULL,
    PRIMARY KEY (employeeID),
    FOREIGN KEY (branchID) REFERENCES BranchDetails (branchID),
    FOREIGN KEY (postalCode) REFERENCES EmployeeAddress (postalCode)
);
CREATE TABLE SponsorAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(12)
);
CREATE TABLE SponsorDetails
(
    sponsorID  VARCHAR(12),
    name       VARCHAR(12)        NOT NULL,
    phone      VARCHAR(12) UNIQUE NOT NULL,
    postalCode VARCHAR(12)        NOT NULL,
    street     VARCHAR(12),
    email      VARCHAR(12) UNIQUE NOT NULL,
    PRIMARY KEY (sponsorID),
    FOREIGN KEY (postalCode) REFERENCES SponsorAddress (postalCode)
);
CREATE TABLE EventAddress
(
    postalCode VARCHAR(12) NOT NULL PRIMARY KEY,
    city       VARCHAR(12)
);
CREATE TABLE EventDetails
(
    eventID    VARCHAR(12) PRIMARY KEY,
    name       VARCHAR(12) NOT NULL,
    "DATE"     DATE        NOT NULL,
    postalCode VARCHAR(12) NOT NULL,
    street     VARCHAR(12),
    sponsorID  VARCHAR(12),
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
    connectedLocation VARCHAR(12) NOT NULL,
    FOREIGN KEY (sponsorID) REFERENCES SponsorDetails (sponsorID)
);
CREATE TABLE PetRetailer
(
    sponsorID    VARCHAR(12) PRIMARY KEY,
    businessType VARCHAR(12) NOT NULL,
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
