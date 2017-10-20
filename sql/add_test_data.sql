-- Lisää INSERT INTO lauseet tähän tiedostoon

INSERT INTO Person (username, name, password) VALUES ('test_user', 'Test User', 'test_password');
INSERT INTO Auction (item_name, minimum_bid, create_date, end_date) VALUES ('PS3', '70', '2017-10-18 12:00:00', '2017-10-22 12:00:00');
INSERT INTO Auction (item_name, minimum_bid, create_date, end_date) VALUES ('Iphone 5', '100', '2017-10-19 12:00:00', '2017-10-23 12:00:00');

INSERT INTO Tag (tag_name) VALUES ('Tietotekniikka');
INSERT INTO Tag (tag_name) VALUES ('Huonekalu');
INSERT INTO Tag (tag_name) VALUES ('Antiikki');
INSERT INTO Tag (tag_name) VALUES ('Vähän käytetty');
INSERT INTO Tag (tag_name) VALUES ('Ajoneuvo');
INSERT INTO Tag (tag_name) VALUES ('Työkalu');