-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Person(
  id SERIAL PRIMARY KEY,
  username varchar(50) NOT NULL,
  name varchar(50) NOT NULL,
  email varchar(50),
  phone varchar(50),
  home_address varchar(50),
  password varchar(50) NOT NULL
);

CREATE TABLE Auction(
  id SERIAL PRIMARY KEY,
  person_id INTEGER REFERENCES Person(id) ON DELETE CASCADE, -- seller
  item_name varchar(50) NOT NULL,
  minimum_bid INTEGER NOT NULL,
  description varchar(400),
  create_date TIMESTAMP NOT NULL,
  end_date TIMESTAMP NOT NULL
);

CREATE TABLE Bid(
  id SERIAL PRIMARY KEY,
  person_id INTEGER REFERENCES Person(id) ON DELETE CASCADE,
  auction_id INTEGER REFERENCES Auction(id) ON DELETE CASCADE,
  money_value INTEGER NOT NULL,
  create_date TIMESTAMP NOT NULL
);

CREATE TABLE Comment(
  id SERIAL PRIMARY KEY,
  person_id INTEGER REFERENCES Person(id) ON DELETE CASCADE,
  auction_id INTEGER REFERENCES Auction(id) ON DELETE CASCADE,
  description varchar(400) NOT NULL,
  create_date TIMESTAMP NOT NULL
);

CREATE TABLE Tag(
  id SERIAL PRIMARY KEY,
  tag_name varchar(50) NOT NULL
);

CREATE TABLE Auction_tag(
  id SERIAL PRIMARY KEY,
  tag_id INTEGER REFERENCES Tag(id) ON DELETE CASCADE,
  auction_id INTEGER REFERENCES Auction(id) ON DELETE CASCADE
);