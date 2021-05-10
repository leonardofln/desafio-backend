drop table extrato;
drop table carteira;
drop table usuario;

create table usuario (
	cd_usuario int NOT NULL AUTO_INCREMENT,
	nm_usuario varchar(250) NOT NULL,
	nr_documento varchar(18) NOT NULL,
	de_email varchar(250) NOT NULL,
	de_senha varchar(64) NOT NULL,
	id_tipo varchar(1) NOT NULL, -- C = Comum / L = Lojista
	dt_cadastro datetime NOT NULL default now(),
	PRIMARY KEY(cd_usuario),
	UNIQUE (nr_documento),
	UNIQUE (de_email)
);

create table carteira (
	cd_usuario int NOT NULL,
	vl_saldo decimal(15,2) NOT NULL,
	FOREIGN KEY (cd_usuario) REFERENCES usuario(cd_usuario),
	UNIQUE (cd_usuario)
);

create table extrato (
	cd_extrato int NOT NULL AUTO_INCREMENT,
	id_tipo varchar(1) NOT NULL, -- E = Entrada / S = Saída
	vl_transacao decimal(15,2) NOT NULL,
	dt_transacao datetime NOT NULL default now(),
	cd_usuario int NOT NULL,
	PRIMARY KEY(cd_extrato),
	FOREIGN KEY (cd_usuario) REFERENCES usuario(cd_usuario)
);