CREATE TABLE Usuario (
	id INT,
	login VARCHAR(255) NOT NULL,
	senha VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL,
	avatar VARCHAR(255),
	doots,
	dataJoin DATE,
	deletado BOOLEAN,
	
	PRIMARY KEY (id)
);

/* Usuario(*ID*, login, senha, email, avatar, doots, dataJoin, deletado) */



CREATE TABLE Meme (
	id INT,
	dataHora TIMESTAMP,
	arquivo VARCHAR(255),
	titulo VARCHAR(255),
	doots,
	deletado BOOLEAN,
	poster INT,
	
	PRIMARY KEY (id)
	FOREIGN KEY (poster) REFERENCES Usuario(id)
);

/* Meme(*ID*, dataHora, arquivo, titulo, doots, deletado, poster)
    poster -> Usuario(ID) */

	
	
CREATE TABLE MemeDoot (
	idMeme INT,
	idUsuario INT,
	updoot INT,
	
	FOREIGN KEY (idMeme) REFERENCES Meme(id)
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
);
	
/* MemeDoot(*IDMeme*, *IDUsuario*, updoot)
    IDMeme -> Meme(ID)
    IDUsuario -> Usuario(ID) */
	
	
	
CREATE TABLE Comentario (
	id INT,
	conteudo VARCHAR(255),
	conteudoOri VARCHAR(255),
	doots VARCHAR(255),
	dataHora TIMESTAMP,
	editado BOOLEAN,
	deletado BOOLEAN,
	dataHoraEdit TIMESTAMP,
	idUsuario INT,
	idMeme INT,
	
	PRIMARY KEY (id)
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
	FOREIGN KEY (idMeme) REFERENCES Meme(id)
);
	
/* Comentario(*ID*, conteudo, conteudoOri, doots, dataHora, editado, deletado, dataHoraEdit, IDUsuario, IDMeme)
    IDMeme -> Meme(ID)
    IDUsuario -> Usuario(ID) */
	
	
	
CREATE TABLE ComentarioDoot (
	idComentario INT,
	idUsuario INT,
	
	FOREIGN KEY (idComentario) REFERENCES Comentario(id)
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
);
	
/* ComentarioDoot(*IDComentario*, *IDUsuario*, updoot)
    IDComentario -> Comentario(ID)
    IDUsuario -> Usuario(ID) */
	
	
	
CREATE TABLE Reacao (
	id INT,
	label VARCHAR(255),
	imagem VARCHAR(255),
	
	PRIMARY KEY(id)
);
	
/* Reacao(*ID*, label, imagem) */



CREATE TABLE MemeReacao (
	idMeme INT,
	idUsuario INT,
	idReacao INT,

	FOREIGN KEY (idMeme) REFERENCES Meme(id)
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
	FOREIGN KEY (idReacao) REFERENCES idReacao(id)
);

/* MemeReacao(*IDMeme*, *IDUsuario*, *IDReacao*)
    IDMeme -> Meme(ID)
    IDUsuario -> Usuario(ID)
    IDReacao -> Reacao(ID) */
	
	

CREATE TABLE ComentarioReacao (
	idComentario INT,
	idUsuario INT,
	idReacao INT,
	
	FOREIGN KEY (idMeme) REFERENCES Meme(id)
	FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
	FOREIGN KEY (idReacao) REFERENCES idReacao(id)
);

/* ComentarioReacao(*IDComentario*, *IDUsuario*, *IDReacao*)
    IDComentario -> Comentario(ID)
    IDUsuario -> Usuario(ID)
    IDReacao -> Reacao(ID) */
