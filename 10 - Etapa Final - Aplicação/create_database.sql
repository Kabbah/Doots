CREATE TABLE IF NOT EXISTS Usuario (
	id INT AUTO_INCREMENT,
	login VARCHAR(255) NOT NULL UNIQUE,
	senha VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL UNIQUE,
	avatar VARCHAR(255) DEFAULT 'avatar.png',
	doots INT NOT NULL DEFAULT 0,
	dataJoin DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	deletado BOOLEAN NOT NULL DEFAULT FALSE,
	
	PRIMARY KEY (id)
);

/* Usuario(*ID*, login, senha, email, avatar, doots, dataJoin, deletado) */

CREATE TABLE IF NOT EXISTS Meme (
	id INT AUTO_INCREMENT,
	dataHora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	arquivo VARCHAR(255) NOT NULL,
	titulo VARCHAR(255) NOT NULL,
	doots INT NOT NULL DEFAULT 0,
	deletado BOOLEAN NOT NULL DEFAULT FALSE,
	poster INT NOT NULL,
	
	PRIMARY KEY (id),
	FOREIGN KEY (poster) REFERENCES Usuario(id)
);

/* Meme(*ID*, dataHora, arquivo, titulo, doots, deletado, poster)
    poster -> Usuario(ID) */
	
CREATE TABLE IF NOT EXISTS MemeDoot (
	idMeme INT,
	idUsuario INT,
	updoot BOOLEAN NOT NULL,
	
    PRIMARY KEY (idMeme, idUsuario),
	FOREIGN KEY (idMeme) REFERENCES Meme(id),
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
);
	
/* MemeDoot(*IDMeme*, *IDUsuario*, updoot)
    IDMeme -> Meme(ID)
    IDUsuario -> Usuario(ID) */
	
	
	
CREATE TABLE IF NOT EXISTS Comentario (
	id INT AUTO_INCREMENT,
	conteudo TEXT NOT NULL,
	conteudoOri TEXT NOT NULL,
	doots VARCHAR(255) NOT NULL DEFAULT 0,
	dataHora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	editado BOOLEAN NOT NULL DEFAULT FALSE,
	deletado BOOLEAN NOT NULL DEFAULT FALSE,
	dataHoraEdit DATETIME,
	idUsuario INT NOT NULL,
	idMeme INT NOT NULL,
	
	PRIMARY KEY (id),
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id),
	FOREIGN KEY (idMeme) REFERENCES Meme(id)
);
	
/* Comentario(*ID*, conteudo, conteudoOri, doots, dataHora, editado, deletado, dataHoraEdit, IDUsuario, IDMeme)
    IDMeme -> Meme(ID)
    IDUsuario -> Usuario(ID) */
	
	
	
CREATE TABLE IF NOT EXISTS ComentarioDoot (
	idComentario INT,
	idUsuario INT,
	updoot BOOLEAN NOT NULL,
    
    PRIMARY KEY (idComentario, idUsuario),
	FOREIGN KEY (idComentario) REFERENCES Comentario(id),
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
);
	
/* ComentarioDoot(*IDComentario*, *IDUsuario*, updoot)
    IDComentario -> Comentario(ID)
    IDUsuario -> Usuario(ID) */
	
	
	
CREATE TABLE IF NOT EXISTS Reacao (
	id INT AUTO_INCREMENT,
	label VARCHAR(255) NOT NULL UNIQUE,
	imagem VARCHAR(255) NOT NULL,
	
	PRIMARY KEY(id)
);
	
/* Reacao(*ID*, label, imagem) */



CREATE TABLE IF NOT EXISTS MemeReacao (
	idMeme INT,
	idUsuario INT,
	idReacao INT,

    PRIMARY KEY (idMeme, idUsuario, idReacao),
	FOREIGN KEY (idMeme) REFERENCES Meme(id),
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id),
	FOREIGN KEY (idReacao) REFERENCES Reacao(id)
);

/* MemeReacao(*IDMeme*, *IDUsuario*, *IDReacao*)
    IDMeme -> Meme(ID)
    IDUsuario -> Usuario(ID)
    IDReacao -> Reacao(ID) */
	
	

CREATE TABLE IF NOT EXISTS ComentarioReacao (
	idComentario INT,
	idUsuario INT,
	idReacao INT,
	
    PRIMARY KEY (idComentario, idUsuario, idReacao),
	FOREIGN KEY (idComentario) REFERENCES Comentario(id),
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id),
	FOREIGN KEY (idReacao) REFERENCES Reacao(id)
);

/* ComentarioReacao(*IDComentario*, *IDUsuario*, *IDReacao*)
    IDComentario -> Comentario(ID)
    IDUsuario -> Usuario(ID)
    IDReacao -> Reacao(ID) */
