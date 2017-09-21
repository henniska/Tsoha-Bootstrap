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
  person_id INTEGER REFERENCES Person(id), -- seller
  item_name varchar(50) NOT NULL,
  minimum_bid INTEGER NOT NULL,
  description varchar(400),
  create_date DATE NOT NULL,
  end_date DATE NOT NULL
);

CREATE TABLE Bid(
  id SERIAL PRIMARY KEY,
  person_id INTEGER REFERENCES Person(id),
  auction_id INTEGER REFERENCES Auction(id),
  money_value INTEGER NOT NULL,
  create_date DATE NOT NULL
);

CREATE TABLE Comment(
  id SERIAL PRIMARY KEY,
  person_id INTEGER REFERENCES Person(id),
  auction_id INTEGER REFERENCES Auction(id),
  description varchar(400) NOT NULL,
  create_date DATE NOT NULL
);

CREATE TABLE Item_image(
  id SERIAL PRIMARY KEY,
  auction_id INTEGER REFERENCES Auction(id),
  data bytea NOT NULL
);

CREATE TABLE Tag_group(
  id SERIAL PRIMARY KEY,
  group_name varchar(50) NOT NULL
);

CREATE TABLE Tag(
  id SERIAL PRIMARY KEY,
  tag_group_id INTEGER REFERENCES Tag_group(id),
  tag_name varchar(50) NOT NULL
);

CREATE TABLE Auction_tag(
  id SERIAL PRIMARY KEY,
  tag_id INTEGER REFERENCES Tag(id),
  auction_id INTEGER REFERENCES Auction(id)
);