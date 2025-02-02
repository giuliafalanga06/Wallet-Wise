CREATE TABLE User (
    Id INT PRIMARY KEY,
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    BirthDate DATE,
    Email VARCHAR(255),
    PhoneNumber VARCHAR(255),
    HashedPassword VARCHAR(255)
);

CREATE TABLE Status (
    Id INT PRIMARY KEY,
    Description VARCHAR(255)
);

CREATE TABLE Type (
    Id INT PRIMARY KEY,
    Name VARCHAR(255),
    Description VARCHAR(255)
);

CREATE TABLE Card (
    Id INT PRIMARY KEY,
    Iban VARCHAR(255),
    Balance DECIMAL(10, 2),
    Expiration DATE,
    UserId INT,
    StatusId INT,
    FOREIGN KEY (UserId) REFERENCES User(Id),
    FOREIGN KEY (StatusId) REFERENCES Status(Id)
);

CREATE TABLE DrawerFund (
    Id INT PRIMARY KEY,
    Balance DECIMAL(10, 2),
    Goal DECIMAL(10, 2),
    Description VARCHAR(255),
    StartDate DATE,
    EndDate DATE,
    CardId INT,
    FOREIGN KEY (CardId) REFERENCES Card(Id)
);

CREATE TABLE Transaction (
    Id INT PRIMARY KEY,
    Description VARCHAR(255),
    Creditor VARCHAR(255),
    Income BOOLEAN,
    Cost DECIMAL(10, 2),
    TransactionDate DATE,
    CardId INT,
    TypeId INT,
    FOREIGN KEY (CardId) REFERENCES Card(Id),
    FOREIGN KEY (TypeId) REFERENCES Type(Id)
);
