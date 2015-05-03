
-- Drop all of the tables
DROP TABLE IF EXISTS cram_equipment;
DROP TABLE IF EXISTS cram_room;
DROP TABLE IF EXISTS cram_account;
DROP TABLE IF EXISTS cram_building;
DROP TABLE IF EXISTS cram_department;
DROP TABLE IF EXISTS cram_reservation;

-- Create department table
CREATE TABLE IF NOT EXISTS cram_department (
	dpt_name VARCHAR( 50 ) NOT NULL DEFAULT '',
	dpt_prefix VARCHAR( 4 ) NOT NULL DEFAULT '',
	dpt_college VARCHAR( 50 ) NOT NULL DEFAULT '',
	dpt_phone_number VARCHAR( 13 ) NOT NULL DEFAULT '',
	
	PRIMARY KEY( dpt_prefix ),
	INDEX( dpt_prefix )
);

-- Create building table
CREATE TABLE IF NOT EXISTS cram_building (
	bldg_name VARCHAR( 50 ) NOT NULL DEFAULT '',
	bldg_address VARCHAR( 50 ) NOT NULL DEFAULT '',
	bldg_state CHAR( 2 ) NOT NULL DEFAULT '',
	bldg_zip INTEGER( 5 ) NOT NULL DEFAULT -1,
	bldg_longitude DECIMAL( 25 ) NOT NULL DEFAULT 0,
	bldg_latitude DECIMAL( 25 ) NOT NULL DEFAULT 0,

	PRIMARY KEY ( bldg_name ),
	INDEX ( bldg_name )
);

-- Create account table
CREATE TABLE IF NOT EXISTS cram_account (
	acct_username VARCHAR( 15 ) NOT NULL DEFAULT '',
	acct_password CHAR( 128 ) NOT NULL DEFAULT '',
	acct_salt VARCHAR( 12 ) NOT NULL DEFAULT '',
	acct_authorization ENUM( 'student' , 'professor' , 'admin' ) NOT NULL DEFAULT 'student',
	acct_first_name VARCHAR( 15 ) NOT NULL DEFAULT '',
	acct_last_name VARCHAR( 15) NOT NULL DEFAULT '',
	acct_phone_number CHAR( 14 ) NOT NULL DEFAULT '',
	dpt_prefix VARCHAR( 4 ) NOT NULL DEFAULT '',
	
	PRIMARY KEY ( acct_username ),
	FOREIGN KEY ( dpt_prefix ) REFERENCES cram_department( dpt_prefix ),
	INDEX ( acct_username , dpt_prefix )
);

-- Create room table
CREATE TABLE IF NOT EXISTS cram_room (
	room_number INTEGER( 3 ) UNSIGNED NOT NULL DEFAULT 0,
	bldg_name VARCHAR( 50 ) NOT NULL DEFAULT '',
	dpt_prefix VARCHAR( 4 ) NOT NULL DEFAULT '',
	room_doors TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 1,
	room_seats INTEGER( 3 ) UNSIGNED NOT NULL DEFAULT 0,
	room_connected INTEGER( 3 ) UNSIGNED NOT NULL DEFAULT 0,
	room_handicap TINYINT( 1 ) UNSIGNED DEFAULT NULL,
	room_lab TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0,

	PRIMARY KEY ( bldg_name , room_number ),
	FOREIGN KEY ( bldg_name ) REFERENCES cram_building( bldg_name ) ,
	FOREIGN KEY ( dpt_prefix ) REFERENCES cram_department( dpt_prefix ),
	FOREIGN KEY ( room_connected ) REFERENCES cram_room( room_number ),
	INDEX ( room_number , bldg_name , dpt_prefix , room_connected ),
	INDEX ( room_lab )
);

-- Create equipment table
CREATE TABLE IF NOT EXISTS cram_equipment (
	eqpt_name VARCHAR( 15 ) NOT NULL DEFAULT '',
	room_number INTEGER( 3 ) UNSIGNED NOT NULL DEFAULT 0,
	bldg_name VARCHAR( 50 ) NOT NULL DEFAULT '',
	eqpt_quantity INTEGER( 3 ) NOT NULL DEFAULT 1,

	PRIMARY KEY ( eqpt_name , room_number , bldg_name ),
	FOREIGN KEY ( room_number ) REFERENCES cram_room( room_number ),
	FOREIGN KEY ( bldg_name ) REFERENCES cram_room( bldg_name ),
	INDEX ( room_number , bldg_name , eqpt_name )
);

-- Create reservation table
CREATE TABLE IF NOT EXISTS cram_reservation (
	resv_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	acct_username VARCHAR( 15 ) NOT NULL DEFAULT '',
	room_number INTEGER( 3 ) UNSIGNED NOT NULL DEFAULT 0,
	bldg_name VARCHAR( 50 ) NOT NULL DEFAULT '',
	resv_start INTEGER( 10 ) UNSIGNED NOT NULL DEFAULT 0,
	resv_end INTEGER( 10 ) UNSIGNED NOT NULL DEFAULT 0,
	resv_size INTEGER( 2 ) UNSIGNED NOT NULL DEFAULT 1,
	resv_reason TEXT NOT NULL DEFAULT '',
	
	PRIMARY KEY ( resv_id , acct_username , room_number, bldg_name ),
	FOREIGN KEY ( acct_username ) REFERENCES cram_account( acct_username),
	FOREIGN KEY ( room_number ) REFERENCES cram_room( room_number ),
	FOREIGN KEY ( bldg_name ) REFERENCES cram_room( bldg_name ),
	INDEX ( resv_id , acct_username, room_number, bldg_name )
);

