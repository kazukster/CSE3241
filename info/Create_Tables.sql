CREATE TABLE users (
    Username_ID VARCHAR(255) NOT NULL,
    Cellphone VARCHAR(12) NOT NULL,
    Admin BOOLEAN,
    Password VARCHAR(255),
    PRIMARY KEY (Username_ID, Cellphone)
);


CREATE TABLE Zones (
    zone_ID INT PRIMARY KEY,
    zone_name VARCHAR(255),
    total_spots INT,
    current_spots INT,
    rate DECIMAL(10, 2),
    zone_fee DECIMAL(10, 2)
);

CREATE TABLE Events (
  event_ID INT PRIMARY KEY,
  event_name VARCHAR(255),
  event_date DATE, 
  event_fee DECIMAL(10, 2)
);

CREATE TABLE Reservations (
    Confirmation_number INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255),
    Cellphone VARCHAR(12) NOT NULL,
    zone_id INT,
    event_date DATE,
    status BOOLEAN,
    event_ID INT,
    total_fee DECIMAL(10, 2),
    FOREIGN KEY (user_name) REFERENCES users (Username_ID),
    FOREIGN KEY (zone_id) REFERENCES zones (zone_ID),
    FOREIGN KEY (event_ID) REFERENCES events (event_ID)
);

CREATE TABLE ZoneEventsDistances (
  zone_ID INT,
  event_ID INT,  
  distance_miles INT,
  FOREIGN KEY (zone_ID) REFERENCES zones (zone_ID),
  FOREIGN KEY (event_ID) REFERENCES events (event_ID)
);
