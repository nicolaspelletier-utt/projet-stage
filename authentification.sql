CREATE TABLE authentification (
	id INT auto_increment NOT NULL,
	hash varchar(512) NOT NULL,
	CONSTRAINT authentification_PK PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1;

INSERT INTO authentification (hash) VALUES
	 ('1465be88eaa0b855641c076cf188707fed22a77f43526d59ecf300444bc7dd4937db9a26c30f0a28755acd11643eb83e42be6b6d9b2efd7502bbe634f30bc3ef');
