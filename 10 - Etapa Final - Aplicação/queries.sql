-- Seleciona todos os memes do DB, com contagem de comentários.
SELECT Meme.id, Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Usuario.login, count(Comentario.id)
FROM Meme
INNER JOIN Usuario ON Meme.poster = Usuario.id
LEFT JOIN Comentario ON Meme.id = Comentario.idMeme
GROUP BY Meme.id;

-- Seleciona todos os memes com contagem de comentários, ordenado pelos memes mais novos, limitado a 10 resultados, começando a partir do registro de número 11 (FRONT PAGE - NOVOS).
SELECT Meme.id, Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Usuario.login, count(Comentario.id)
FROM Meme
INNER JOIN Usuario ON Meme.poster = Usuario.id
LEFT JOIN Comentario ON Meme.id = Comentario.idMeme
GROUP BY Meme.id
ORDER BY Meme.dataHora DESC
LIMIT 10 OFFSET 10;

-- Seleciona todos os memes com contagem de comentários, ordenado pelos memes mais bem votados, limitado a 10 resultados, começando a partir do registro de número 11 (FRONT PAGE - NO TOPO).
SELECT Meme.id, Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Usuario.login, count(Comentario.id)
FROM Meme
INNER JOIN Usuario ON Meme.poster = Usuario.id
LEFT JOIN Comentario ON Meme.id = Comentario.idMeme
GROUP BY Meme.id
ORDER BY Meme.doots DESC
LIMIT 10 OFFSET 10;

-- Seleciona todos os memes com contagem de comentários, ordenado pelos memes mais populares, limitado a 10 resultados, começando a partir do registro de número 1 (FRONT PAGE - POPULARES).
SELECT Meme.id, Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Usuario.login, count(Comentario.id), ((sign(Meme.doots) * log(10, greatest(abs(Meme.doots),1))) + (unix_timestamp(Meme.dataHora) - 1134028003)/45000) AS popularity
FROM Meme INNER JOIN Usuario ON Meme.poster = Usuario.id
LEFT JOIN Comentario ON Meme.id = Comentario.idMeme
GROUP BY Meme.id
ORDER BY popularity DESC
LIMIT 10 OFFSET 0;

-- Seleciona todos os memes com contagem de comentários, ordenado pelos memes mais populares, limitado a 10 resultados, começando a partir do registro de número 1 (FRONT PAGE - POPULARES), com informação de updoot/downdoot do usuário.
SELECT Meme.id, Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Usuario.login, count(Comentario.id), ((sign(Meme.doots) * log(10, greatest(abs(Meme.doots),1))) + (unix_timestamp(Meme.dataHora) - 1134028003)/45000) AS popularity, MemeDoot.updoot
FROM Meme
INNER JOIN Usuario ON Meme.poster = Usuario.id
LEFT JOIN MemeDoot ON (Meme.id = MemeDoot.idMeme AND MemeDoot.idUsuario = 2)
LEFT JOIN Comentario ON Meme.id = Comentario.idMeme
GROUP BY Meme.id
ORDER BY popularity DESC
LIMIT 10 OFFSET 0;