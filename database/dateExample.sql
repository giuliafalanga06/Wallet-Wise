INSERT INTO User (Id, FirstName, LastName, BirthDate, Email, PhoneNumber, HashedPassword) VALUES
(1, 'John', 'Doe', '1985-01-01', 'john.doe@example.com', '1234567890', 'hashedpassword1'),
(2, 'Jane', 'Smith', '1990-02-02', 'jane.smith@example.com', '0987654321', 'hashedpassword2'),
(3, 'Alice', 'Johnson', '1988-03-03', 'alice.johnson@example.com', '1231231234', 'hashedpassword3'),
(4, 'Bob', 'Brown', '1975-04-04', 'bob.brown@example.com', '9876543210', 'hashedpassword4'),
(5, 'Charlie', 'Davis', '1995-05-05', 'charlie.davis@example.com', '5678901234', 'hashedpassword5'),
(6, 'Diana', 'Wilson', '1982-06-06', 'diana.wilson@example.com', '6789012345', 'hashedpassword6'),
(7, 'Evan', 'Taylor', '1986-07-07', 'evan.taylor@example.com', '7890123456', 'hashedpassword7'),
(8, 'Fiona', 'Moore', '1989-08-08', 'fiona.moore@example.com', '8901234567', 'hashedpassword8'),
(9, 'George', 'Miller', '1991-09-09', 'george.miller@example.com', '9012345678', 'hashedpassword9'),
(10, 'Hannah', 'Garcia', '1984-10-10', 'hannah.garcia@example.com', '0123456789', 'hashedpassword10');

INSERT INTO Status (Id, Description) VALUES
(1, 'Active'),
(2, 'Inactive'),
(3, 'Suspended');

INSERT INTO Type (Id, Name, Description) VALUES
(1, 'Groceries', 'Grocery shopping expenses'),
(2, 'Entertainment', 'Expenses on entertainment'),
(3, 'Utilities', 'Utility bills');

INSERT INTO Card (Id, Iban, Balance, Expiration, UserId, StatusId) VALUES
(1, 'IBAN001', 1000.00, '2025-12-31', 1, 1),
(2, 'IBAN002', 500.00, '2025-12-31', 1, 1),
(3, 'IBAN003', 2000.00, '2026-12-31', 2, 1),
(4, 'IBAN004', 1500.00, '2026-12-31', 2, 1),
(5, 'IBAN005', 2500.00, '2026-12-31', 3, 1),
(6, 'IBAN006', 1200.00, '2025-12-31', 4, 1),
(7, 'IBAN007', 1300.00, '2026-12-31', 5, 1),
(8, 'IBAN008', 3000.00, '2025-12-31', 6, 1),
(9, 'IBAN009', 700.00, '2026-12-31', 7, 1),
(10, 'IBAN010', 800.00, '2026-12-31', 8, 1),
(11, 'IBAN011', 1600.00, '2025-12-31', 9, 1),
(12, 'IBAN012', 1800.00, '2026-12-31', 10, 1);

INSERT INTO DrawerFund (Id, Balance, Goal, Description, StartDate, EndDate, CardId) VALUES
(1, 500.00, 1500.00, 'Vacation savings', '2025-01-01', '2025-12-31', 1),
(2, 300.00, 1000.00, 'Emergency savings', '2025-01-01', '2025-12-31', 2),
(3, 1000.00, 2000.00, 'Car savings', '2025-01-01', '2025-12-31', 3);

INSERT INTO Transaction (Id, Description, Creditor, Income, Cost, TransactionDate, CardId, TypeId) VALUES
(1, 'Grocery shopping', 'Supermarket', false, 100.00, '2025-01-05', 1, 1),
(2, 'Movie tickets', 'Cinema', false, 50.00, '2025-01-10', 1, 2),
(3, 'Utility bill', 'Electric Company', false, 200.00, '2025-01-15', 2, 3);
