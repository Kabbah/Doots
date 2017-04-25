# -*- coding: utf-8 -*-

import matplotlib.pyplot as plt
import psycopg2

# ================================================================================
# Conexão com o banco de dados
# ================================================================================

try:
    conn = psycopg2.connect(
        host = "200.134.10.32",
        dbname = "1717545_Victor",
        user = "m0n0p0ly",
        password = "#n0m0n3y#"
    )
    db = conn.cursor()
except psycopg2.Error as e:
    print(e)
    exit()

# ================================================================================
# Operações
# ================================================================================

# 1. Qual é a média e desvio padrão dos ratings para artistas musicais e filmes?
def showArtistaAvgDev():
    formatTemplate = "| {0:50} | {1:4.4} | {2:>4.4} |"
    
    query = "SELECT \"idArtistaMusical\", avg(nota), stddev(nota) FROM CurtirArtistaMusical GROUP BY \"idArtistaMusical\" ORDER BY \"idArtistaMusical\" ASC"
    db.execute(query)
    result = db.fetchall()

    print("+----------------------------------------------------+------+------+")
    print(formatTemplate.format("Artista Musical","Avg","SDev"))
    print("+----------------------------------------------------+------+------+")
    for row in result:
        print(formatTemplate.format(row[0],str(row[1]),str(row[2])))
    print("+----------------------------------------------------+------+------+")
    
# 1. Qual é a média e desvio padrão dos ratings para artistas musicais e filmes?    
def showFilmeAvgDev():
    formatTemplate = "| {0:9} | {1:4.4} | {2:>4.4} |"
    
    query = "SELECT \"idFilme\", avg(nota), stddev(nota) FROM CurtirFilme GROUP BY \"idFilme\" ORDER BY \"idFilme\" ASC"
    db.execute(query)
    result = db.fetchall()

    print("+-----------+------+------+")
    print(formatTemplate.format("Filme","Avg","SDev"))
    print("+-----------+------+------+")
    for row in result:
        print(formatTemplate.format(row[0],str(row[1]),str(row[2])))
    print("+-----------+------+------+")

# 2. Quais são os artistas e filmes com o maior rating médio curtidos por pelo menos duas pessoas? Ordenados por rating médio.
def showArtista2orMoreLikes():
    formatTemplate = "| {0:50} | {1:>4.4} | {2:>5.5} |"

    query = "SELECT \"idArtistaMusical\", avg(nota), count(\"idArtistaMusical\") FROM CurtirArtistaMusical GROUP BY \"idArtistaMusical\" HAVING count(\"idArtistaMusical\") > 1 ORDER BY avg(nota) DESC"
    db.execute(query)
    result = db.fetchall()

    print("+----------------------------------------------------+------+-------+")
    print(formatTemplate.format("Artista Musical","Avg","Count"))
    print("+----------------------------------------------------+------+-------+")
    for row in result:
        print(formatTemplate.format(row[0],str(row[1]),str(row[2])))
    print("+----------------------------------------------------+------+-------+")

# 2. Quais são os artistas e filmes com o maior rating médio curtidos por pelo menos duas pessoas? Ordenados por rating médio.
def showFilme2orMoreLikes():
    formatTemplate = "| {0:9} | {1:>4.4} | {2:>5.5} |"

    query = "SELECT \"idFilme\", avg(nota), count(\"idFilme\") FROM CurtirFilme GROUP BY \"idFilme\" HAVING count(\"idFilme\") > 1 ORDER BY avg(nota) DESC"
    db.execute(query)
    result = db.fetchall()

    print("+-----------+------+-------+")
    print(formatTemplate.format("Filme","Avg","Count"))
    print("+-----------+------+-------+")
    for row in result:
        print(formatTemplate.format(row[0],str(row[1]),str(row[2])))
    print("+-----------+------+-------+")

# 3. Quais são os 10 artistas musicais e filmes mais populares? Ordenados por popularidade.
def showArtistaTop10():
    formatTemplate = "| {0:50} | {1:>4.4} |"
    
    # Popularidade foi definida como "Rating - 3". Assim, ao receber notas 2 e 1, o artista perde popularidade.
    query = "SELECT \"idArtistaMusical\", sum(nota - 3) FROM CurtirArtistaMusical GROUP BY \"idArtistaMusical\" HAVING count(\"idArtistaMusical\") > 1 ORDER BY sum(nota - 3) DESC LIMIT 10"
    db.execute(query)
    result = db.fetchall()

    print("+----------------------------------------------------+------+")
    print(formatTemplate.format("Artista Musical","Pop"))
    print("+----------------------------------------------------+------+")
    for row in result:
        print(formatTemplate.format(row[0],str(row[1])))
    print("+----------------------------------------------------+------+")

# 3. Quais são os 10 artistas musicais e filmes mais populares? Ordenados por popularidade.
def showFilmeTop10():
    formatTemplate = "| {0:9} | {1:>4.4} |"
    
    # Popularidade foi definida como "Rating - 3". Assim, ao receber notas 2 e 1, o artista perde popularidade.
    query = "SELECT \"idFilme\", sum(nota - 3) FROM CurtirFilme GROUP BY \"idFilme\" HAVING count(\"idFilme\") > 1 ORDER BY sum(nota - 3) DESC LIMIT 10"
    db.execute(query)
    result = db.fetchall()

    print("+-----------+------+")
    print(formatTemplate.format("Filme","Pop"))
    print("+-----------+------+")
    for row in result:
        print(formatTemplate.format(row[0],str(row[1])))
    print("+-----------+------+")

# 4. Crie uma view chamada ConheceNormalizada que represente simetricamente os relacionamentos de comnhecidos da turma. Por exemplo, se a conhece b mas b não declarou conhecer a, a view criada deve conter o relacionamento (b,a) além de (a,b).
# Essa função não é executada já que a view já foi criada previamente
def createViewConheceNormalizada():
    query = "CREATE VIEW ConheceNormalizada AS SELECT DISTINCT A.login AS loginA, B.login AS loginB FROM Usuario AS A, Usuario AS B, UsuarioConhece WHERE (UsuarioConhece.\"loginSujeito\" = A.login AND UsuarioConhece.\"loginConhecido\" = B.login) OR (UsuarioConhece.\"loginSujeito\" = B.login AND UsuarioConhece.\"loginConhecido\" = A.login) ORDER BY A.login ASC"

    try:
        db.execute(query)
    except psycopg2.Error as e:
        print(e)
        conn.rollback()
    else:
        print("View ConheceNormalizada criada com sucesso.")
        conn.commit()


# 5. Quais são os conhecidos (duas pessoas ligadas na view ConheceNormalizada) que compartilham o maior numero de filmes curtidos?
def showTopConhecidosMsmFilmes():
    formatTemplate = "| {0:20} | {1:20} | {2:>4} |"
    
    query = "SELECT loginA, loginB, count(CF1.\"idFilme\") FROM ConheceNormalizada, CurtirFilme AS CF1, CurtirFilme AS CF2 WHERE (CF1.\"idFilme\" = CF2.\"idFilme\") AND (loginA = CF1.login) AND (loginB = CF2.login) GROUP BY loginA, loginB ORDER BY count(CF1.\"idFilme\") DESC LIMIT 1"
    db.execute(query)
    result = db.fetchall()

    print("+----------------------+----------------------+------+")
    print(formatTemplate.format("Usuário 1","Usuário 2","Num"))
    print("+----------------------+----------------------+------+")
    for row in result:
        print(formatTemplate.format(row[0],row[1],str(row[2])))
    print("+----------------------+----------------------+------+")

# 6. Qual o número de conhecidos dos conhecidos (usando ConheceNormalizada) para cada integrante do grupo?
def showCountConhecidosDeConhecidos():
    formatTemplate = "| {0:20} | {1:>4} |"
    
    query = "SELECT loginA, count(loginB) FROM (SELECT DISTINCT CN1.loginA, CN2.loginB FROM ConheceNormalizada AS CN1, ConheceNormalizada AS CN2 WHERE (CN1.loginB = CN2.loginA) AND (CN1.loginA <> CN2.loginB)) AS temp GROUP BY loginA"
    db.execute(query)
    result = db.fetchall()

    print("+----------------------+------+")
    print(formatTemplate.format("Usuário","Num"))
    print("+----------------------+------+")
    for row in result:
        print(formatTemplate.format(row[0],row[1]))
    print("+----------------------+------+")
    
# 7. Construa um gráfico para a função f(x) = número de pessoas que curtiram exatamente x filmes. 
def graphPeopleXMovies():
    query = "SELECT count(pessoa), filmes FROM (SELECT Usuario.login AS pessoa, count(CurtirFilme.login) AS filmes FROM Usuario NATURAL JOIN CurtirFilme GROUP BY Usuario.login) AS stuff GROUP BY filmes ORDER BY filmes"
    db.execute(query)
    result = db.fetchall()
    
    pessoasQCurtiram = []
    filmesCurtidos = []
    for row in result:
        filmesCurtidos.append(row[1])
        pessoasQCurtiram.append(row[0])
    
    plt.bar(filmesCurtidos, pessoasQCurtiram, 2/3, color="blue")
    
    # Coisas pra ficar bunito plot
    plt.ylabel("Número de pessoas")
    plt.xlabel("Número de filmes curtidos")
    plt.xticks(range(1,filmesCurtidos[len(filmesCurtidos)-1]+1))
    
    plt.show()

# 8. Construa um gráfico para a função f(x) = número de filmes curtidos por exatamente x pessoas.    
def graphMoviesXPeople():
    query = "SELECT count(filme), numeroCurtidas FROM (SELECT DISTINCT CurtirFilme.\"idFilme\" AS filme, count(CurtirFilme.login) AS numeroCurtidas FROM Usuario NATURAL JOIN CurtirFilme GROUP BY filme) AS things GROUP BY numeroCurtidas ORDER BY numeroCurtidas"
    db.execute(query)
    result = db.fetchall()
    
    numeroFilmes = []
    numeroCurtidas = []
    for row in result:
        numeroFilmes.append(row[0])
        numeroCurtidas.append(row[1])
        
    plt.bar(numeroCurtidas, numeroFilmes, 2/3, color="blue")
    
    # Coisas pra ficar bunito plot
    plt.ylabel("Número de filmes")
    plt.xlabel("Número de pessoas que curtiu")
    plt.xticks(range(1,numeroCurtidas[len(numeroCurtidas)-1]+1))
    
    plt.show()
    
# 9. Criado: Os 10 usuários que são mais conhecidos pelos outros
def top10MostKnown():
    query = "SELECT \"loginConhecido\", count(\"loginSujeito\") FROM UsuarioConhece GROUP BY \"loginConhecido\" ORDER BY count(\"loginSujeito\") DESC LIMIT 10"
    db.execute(query)
    result = db.fetchall()

    formatTemplate = "| {0:20} | {1:>3} |"
    print("+----------------------+-----+")
    print(formatTemplate.format("Usuário","Num"))
    print("+----------------------+-----+")
    for row in result:
        print(formatTemplate.format(row[0],row[1]))
    print("+----------------------+-----+")

# 10. Criado: Pessoas com mais probabilidade de expansão da network social
# Em outras palavras: Contagem de conhecidos de conhecidos, os quais não são conhecidos diretos
def numberOfSuggestionsForEachUser():
    query = "SELECT loginA, count(loginB) FROM (SELECT DISTINCT CN1.loginA, CN2.loginB FROM ConheceNormalizada AS CN1, ConheceNormalizada AS CN2 WHERE (CN1.loginB = CN2.loginA) AND (CN1.loginA <> CN2.loginB) AND CN2.loginB NOT IN (SELECT \"loginConhecido\" FROM UsuarioConhece WHERE \"loginSujeito\" = CN1.loginA)) AS temp GROUP BY loginA ORDER BY count(loginB) DESC"
    db.execute(query)
    result = db.fetchall()

    formatTemplate = "| {0:20} | {1:>3} |"
    print("+----------------------+-----+")
    print(formatTemplate.format("Usuário","Num"))
    print("+----------------------+-----+")
    for row in result:
        print(formatTemplate.format(row[0],row[1]))
    print("+----------------------+-----+")
    
# ================================================================================
# Interface
# ================================================================================

menu = True
while menu:
    print("\n[1] Exibir média e desvio padrão de ratings")
    print("[2] Exibir artistas ou filmes com maior rating médio")
    print("[3] Exibir top10 artistas ou filmes mais populares")
    print("[4] Criar view de relacionamentos simétricos")
    print("[5] Exibir conhecidos com maior número de filmes em comum")
    print("[6] Exibir número de conhecidos de conhecidos para cada usuário")
    print("[7] Exibir gráfico de pessoas por números de filmes curtidos")
    print("[8] Exibir gráfico de números de filmes curtidos por pessoas")
    print("[9] Exibir os 10 usuários mais conhecidos")
    print("[10] Exibir pessoas com mais probabilidade de expansão da network social")
    print("[11] Sair") 

    case = input("Digite uma opção: ") # Apenas input() em Python3
    if case == "1":
        print("Opção escolhida: [1] Exibir média e desvio padrão de ratings")
        showArtistaAvgDev()
        showFilmeAvgDev()
    elif case == "2":
        print("Opção escolhida: [2] Exibir artistas ou filmes com maior rating médio")
        showArtista2orMoreLikes()
        showFilme2orMoreLikes()
    elif case == "3":
        print("Opção escolhida: [3] Exibir top10 artistas ou filmes mais populares")
        showArtistaTop10()
        showFilmeTop10()
    elif case == "4":
        print("Opção escolhida: [4] Criar view de relacionamentos simétricos")
        createViewConheceNormalizada()
    elif case == "5":
        print("Opção escolhida: [5] Exibir conhecidos com maior número de filmes em comum")
        showTopConhecidosMsmFilmes()
    elif case == "6":
        print("Opção escolhida: [6] Exibir número de conhecidos de conhecidos para cada usuário")
        showCountConhecidosDeConhecidos()
    elif case == "7":
        print("Opção escolhida: [7] Exibir gráfico de pessoas por números de filmes curtidos")
        graphPeopleXMovies()
    elif case == "8":
        print("Opção escolhida: [8] Exibir gráfico de números de filmes curtidos por pessoas")
        graphMoviesXPeople()
    elif case == "9":
        print("Opção escolhida: [9] Exibir os 10 usuários mais conhecidos")
        top10MostKnown()
    elif case == "10":
        print("Opção escolhida: [10] Exibir pessoas com mais probabilidade de expansão da network social")
        numberOfSuggestionsForEachUser()
    elif case == "11":
        menu = False
    else:
        print("Opção inválida")

db.close()
conn.close()
