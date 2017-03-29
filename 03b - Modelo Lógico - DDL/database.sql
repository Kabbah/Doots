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
	PRIMARY KEY (loginSujeito, loginConhecido),
	FOREIGN KEY (loginSujeito) REFERENCES Usuario(login),
	FOREIGN KEY (loginConhecido) REFERENCES Usuario(login)
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
	nota int,
	PRIMARY KEY (login, idFilme),
	FOREIGN KEY (login) REFERENCES Usuario(login),
	FOREIGN KEY (idFilme) REFERENCES Filme(id)
);
