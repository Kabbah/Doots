CREATE TABLE Usuario (
	login VARCHAR(255),
	nomeCompleto VARCHAR(255),
	cidadeNatal VARCHAR(255),
	PRIMARY KEY (login)
);

CREATE TABLE UsuarioConhece (
	loginSujeito VARCHAR(255),
	loginConhecido VARCHAR(255),
	PRIMARY KEY (loginSujeito, loginConhecido),
	FOREIGN KEY (loginSujeito) REFERENCES Usuario(login),
	FOREIGN KEY (loginConhecido) REFERENCES Usuario(login)
);

CREATE TABLE UsuarioBloqueia (
	loginSujeito VARCHAR(255),
	loginBloqueado VARCHAR(255),
	razaoSpam BOOLEAN,
	razaoAbusivo BOOLEAN,
	razaoPessoal BOOLEAN,
	razaoOutra VARCHAR(255),
	PRIMARY KEY (loginSujeito, loginBloqueado),
	FOREIGN KEY (loginSujeito) REFERENCES Usuario(login),
	FOREIGN KEY (loginBloqueado) REFERENCES Usuario(login)
);

CREATE TABLE ArtistaCinema (
	id INT,
	endereco VARCHAR(255),
	telefone VARCHAR(255),
	PRIMARY KEY (id)
);

CREATE TABLE Filme (
	id INT,
	nome VARCHAR(255),
	dataLancamento DATE,
	idDiretor INT,
	PRIMARY KEY (id),
	FOREIGN KEY (idDiretor) REFERENCES ArtistaCinema(id)
);

CREATE TABLE AtorFilme (
	idFilme INT,
	idAtor INT,
	PRIMARY KEY (idFilme, idAtor),
	FOREIGN KEY (idFilme) REFERENCES Filme(id),
	FOREIGN KEY (idAtor) REFERENCES ArtistaCinema(id)
);

CREATE TABLE Categoria (
	id INT,
	nome VARCHAR(255),
	idSupercategoria INT,
	PRIMARY KEY (id),
	FOREIGN KEY (idSupercategoria) REFERENCES Categoria(id)
);

CREATE TABLE ClassificacaoFilme (
	idFilme INT,
	idCategoria INT,
	PRIMARY KEY (idFilme, idCategoria),
	FOREIGN KEY (idFilme) REFERENCES Filme(id),
	FOREIGN KEY (idCategoria) REFERENCES Categoria(id)
);

CREATE TABLE CurtirFilme (
	login VARCHAR(255),
	idFilme INT,
	nota INT,
	PRIMARY KEY (login, idFilme),
	FOREIGN KEY (login) REFERENCES Usuario(login),
	FOREIGN KEY (idFilme) REFERENCES Filme(id)
);

CREATE TABLE ArtistaMusical(
    id INT,
    pais VARCHAR(255),
    genero VARCHAR(255),
    nomeArtistico VARCHAR(255),
    PRIMARY KEY (id)
);

CREATE TABLE Banda(
    id INT,
    nome VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (id) REFERENCES ArtistaMusical(id)
);

CREATE TABLE Musico(
    nomeReal VARCHAR(255),
    dataNascimento DATE,
    estiloMusical VARCHAR(255),
    idBanda INT,
    PRIMARY KEY (nomeReal),
    FOREIGN KEY (idBanda) REFERENCES Banda(id)
);

CREATE TABLE Cantor(
    id INT,
    nomeMusico VARCHAR(255),
    PRIMARY KEY (id, nomeMusico),
    FOREIGN KEY (id) REFERENCES ArtistaMusical(id),
    FOREIGN KEY (nomeMusico) REFERENCES Musico(nomeReal)
);

CREATE TABLE CurtirArtistaMusical(
    login VARCHAR(255),
    idArtistaMusical INT,
    nota SMALLINT,
    PRIMARY KEY (login, idArtistaMusical),
    FOREIGN KEY (login) REFERENCES Usuario(login),
    FOREIGN KEY (idArtistaMusical) REFERENCES ArtistaMusical(id)
); 

INSERT INTO Usuario VALUES ('Joao','João da Silva','Ponta Grossa');
INSERT INTO Usuario VALUES ('Maria','Maria Costa','Curitiba');
INSERT INTO Usuario VALUES ('Angelo','Angelo Borsoi Ross','Caçador');
INSERT INTO Usuario VALUES ('Charles','Charles Fridman','Uberlândia');

INSERT INTO ArtistaCinema VALUES (1,'Rua Silva Jardim 194','99854-6687');
INSERT INTO ArtistaCinema VALUES (2,'Av. Marechal Cornelho 333','3542-6532');
INSERT INTO ArtistaCinema VALUES (3,'Rua Biscoitos Cheirosos','3596-5263');
INSERT INTO ArtistaCinema VALUES (4,'Rua Skybridge 4242','99956-4523');

INSERT INTO Filme VALUES (1,'Logan','2017-02-28',1);
INSERT INTO Filme VALUES (2,'Como Treinar Seu Dragão','2010-06-13',2);
INSERT INTO Filme VALUES (3,'Up!','2008-08-12',3);
INSERT INTO Filme VALUES (4,'Biscoitos','2012-12-21',4);

INSERT INTO Categoria VALUES (1,'Ação',NULL);
INSERT INTO Categoria VALUES (2,'Drama',NULL);
INSERT INTO Categoria VALUES (3,'Comédia',NULL);
INSERT INTO Categoria VALUES (4,'Ficção',NULL);
INSERT INTO Categoria VALUES (5,'Sci-fi',4);
INSERT INTO Categoria VALUES (6,'Romance',2);

INSERT INTO ArtistaMusical VALUES (1,'Brasil','Samba','Netinho');
INSERT INTO ArtistaMusical VALUES (2,'Estados Unidos da América','Regge','James Regging');
INSERT INTO ArtistaMusical VALUES (3,'Alemanha','Country','Priscilla');
INSERT INTO ArtistaMusical VALUES (4,'Argentina','Rock','Hermano');


INSERT INTO UsuarioConhece VALUES ('Joao', 'Maria');
INSERT INTO UsuarioConhece VALUES ('Joao', 'Angelo');
INSERT INTO UsuarioConhece VALUES ('Joao', 'Charles');
INSERT INTO UsuarioConhece VALUES ('Maria', 'Angelo');
INSERT INTO UsuarioConhece VALUES ('Maria', 'Charles');
INSERT INTO UsuarioConhece VALUES ('Angelo', 'Charles');


INSERT INTO UsuarioBloqueia VALUES ('Joao', 'Maria', TRUE, TRUE, FALSE, NULL);
INSERT INTO UsuarioBloqueia VALUES ('Joao', 'Angelo', FALSE, TRUE, FALSE, NULL);
INSERT INTO UsuarioBloqueia VALUES ('Joao', 'Charles', TRUE, FALSE, FALSE, NULL);
INSERT INTO UsuarioBloqueia VALUES ('Maria', 'Angelo', FALSE, FALSE, TRUE, NULL);
INSERT INTO UsuarioBloqueia VALUES ('Maria', 'Charles', TRUE, TRUE, TRUE, NULL);
INSERT INTO UsuarioBloqueia VALUES ('Angelo', 'Charles', TRUE, TRUE, TRUE, 'Comeu minha sopa!');


INSERT INTO AtorFilme VALUES (1, 1);
INSERT INTO AtorFilme VALUES (1, 2);
INSERT INTO AtorFilme VALUES (2, 2);
INSERT INTO AtorFilme VALUES (2, 3);
INSERT INTO AtorFilme VALUES (3, 3);
INSERT INTO AtorFilme VALUES (4, 4);


INSERT INTO ClassificacaoFilme VALUES (1, 1);
INSERT INTO ClassificacaoFilme VALUES (2, 1);
INSERT INTO ClassificacaoFilme VALUES (2, 3);
INSERT INTO ClassificacaoFilme VALUES (3, 2);
INSERT INTO ClassificacaoFilme VALUES (3, 3);
INSERT INTO ClassificacaoFilme VALUES (4, 6);


INSERT INTO CurtirFilme VALUES ('Joao', 1, 9);
INSERT INTO CurtirFilme VALUES ('Maria', 1, 10);
INSERT INTO CurtirFilme VALUES ('Angelo', 4, 5);
INSERT INTO CurtirFilme VALUES ('Charles', 3, 7);
INSERT INTO CurtirFilme VALUES ('Joao', 2, 9);
INSERT INTO CurtirFilme VALUES ('Maria', 2, 9);


INSERT INTO Banda VALUES (1, 'Marmonas');
INSERT INTO Banda VALUES (2, 'Mamutes');
INSERT INTO Banda VALUES (3, 'Sludge');
INSERT INTO Banda VALUES (4, 'Baby Metal');


INSERT INTO Musico VALUES ('Johnny Blaze','1982-02-28', 'Heavy Metal', 1);
INSERT INTO Musico VALUES ('Mary Anne','1990-11-07', 'Pop', 2);
INSERT INTO Musico VALUES ('Paul Jackson','1983-01-13', 'Rock', 2);
INSERT INTO Musico VALUES ('Martin Stevens','1976-08-05', 'Jazz', 3);
INSERT INTO Musico VALUES ('Frank Roberts','1971-04-11', 'Blues', 3);
INSERT INTO Musico VALUES ('Jose Carlito','1986-06-19', 'Salsa', 4);


INSERT INTO Cantor VALUES (1, 'Johnny Blaze');
INSERT INTO Cantor VALUES (2, 'Mary Anne');
INSERT INTO Cantor VALUES (2, 'Paul Jackson');
INSERT INTO Cantor VALUES (3, 'Martin Stevens');
INSERT INTO Cantor VALUES (3, 'Frank Roberts');
INSERT INTO Cantor VALUES (4, 'Jose Carlito');


INSERT INTO CurtirArtistaMusical VALUES ('Joao', 1, 8);
INSERT INTO CurtirArtistaMusical VALUES ('Joao', 2, 7);
INSERT INTO CurtirArtistaMusical VALUES ('Maria', 3, 8);
INSERT INTO CurtirArtistaMusical VALUES ('Angelo', 4, 10);
INSERT INTO CurtirArtistaMusical VALUES ('Charles', 3, 9);
INSERT INTO CurtirArtistaMusical VALUES ('Maria', 4, 8);