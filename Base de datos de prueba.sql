create database Pmate;
use Pmate;
create table Post(
ID_post int auto_increment,
Title text,
Caption text,
Date_crea datetime,
primary key(ID_post)
);
CREATE TABLE multi (
Num INT,
ID_post INT,
SRC LONGBLOB,
PRIMARY KEY (ID_post, Num),
FOREIGN KEY (ID_post) REFERENCES Post(ID_post)
);
Create table Evento(
ID_event int auto_increment,
ID_post int,
Loc_x int,
Loc_y int,
Date_crea datetime,
Date_eve datetime,
Date_end datetime,
primary key(ID_event),
FOREIGN KEY (ID_post) REFERENCES Post(ID_post)
);
Create table Users(
ID_user int auto_increment,
Full_name varchar(30) not null,
User_name varchar(15) not null unique,
Email varchar(255) not null,
Pass CHAR(60) not null,
 primary key(ID_user)
);
CREATE TABLE Profiles (
    Profile_ID INT AUTO_INCREMENT PRIMARY KEY,
    User_ID INT NOT NULL,
    profile_picture VARCHAR(255),
    color VARCHAR(50),
    bio TEXT,
    privacy ENUM('public', 'private', 'friends') DEFAULT 'public',
    FOREIGN KEY (User_ID) REFERENCES Users(ID_user)
);
select * from Users;
select * from Profiles;
SELECT Pass FROM Users WHERE User_name = "eze";
