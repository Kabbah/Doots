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
    dataNascimento SMALLINT,
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




