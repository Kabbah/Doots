# -*- coding: utf-8 -*-

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
# Descrição do banco de dados em classes
# ================================================================================

##class BaseModel(pw.Model):
##    class Meta:
##        database = db
##
##class Usuario(BaseModel):
##    login = pw.CharField(primary_key = True)
##    nomeCompleto = pw.CharField()
##    cidadeNatal = pw.CharField()
##
##class UsuarioConhece(BaseModel):
##    loginSujeito = pw.ForeignKeyField(Usuario, related_name = "conhecidos", db_column = "loginSujeito")
##    loginConhecido = pw.ForeignKeyField(Usuario, db_column = "loginConhecido")
##    class Meta:
##        primary_key = pw.CompositeKey("loginSujeito", "loginConhecido")
##
##class CurtirFilme(BaseModel):
##    login = pw.ForeignKeyField(Usuario, related_name = "filmescurtidos", db_column = "login")
##    idFilme = pw.CharField()
##    nota = pw.IntegerField()
##    class Meta:
##        primary_key = pw.CompositeKey("login", "idFilme")
##
##class CurtirArtistaMusical(BaseModel):
##    login = pw.ForeignKeyField(Usuario, related_name = "artistascurtidos", db_column = "login")
##    idArtistaMusical = pw.CharField()
##    nota = pw.IntegerField()
##    class Meta:
##        primary_key = pw.CompositeKey("login", "idArtistaMusical")

# ================================================================================
# Operações
# ================================================================================

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

def showArtistaTop10():
    formatTemplate = "| {0:50} | {1:>4.4} |"

    query = "SELECT \"idArtistaMusical\", sum(nota - 3) FROM CurtirArtistaMusical GROUP BY \"idArtistaMusical\" HAVING count(\"idArtistaMusical\") > 1 ORDER BY sum(nota - 3) DESC LIMIT 10"
    db.execute(query)
    result = db.fetchall()

    print("+----------------------------------------------------+------+")
    print(formatTemplate.format("Artista Musical","Pop"))
    print("+----------------------------------------------------+------+")
    for row in result:
        print(formatTemplate.format(row[0],str(row[1])))
    print("+----------------------------------------------------+------+")

def showFilmeTop10():
    formatTemplate = "| {0:9} | {1:>4.4} |"

    query = "SELECT \"idFilme\", sum(nota - 3) FROM CurtirFilme GROUP BY \"idFilme\" HAVING count(\"idFilme\") > 1 ORDER BY sum(nota - 3) DESC LIMIT 10"
    db.execute(query)
    result = db.fetchall()

    print("+-----------+------+")
    print(formatTemplate.format("Filme","Pop"))
    print("+-----------+------+")
    for row in result:
        print(formatTemplate.format(row[0],str(row[1])))
    print("+-----------+------+")

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

def showTopConhecidosMsmFilmes():
    formatTemplate = "| {0:20} | {1:20} | {2:>4} |"
    
    query = "SELECT loginA, loginB, count(CF1.\"idFilme\") FROM ConheceNormalizada, CurtirFilme AS CF1, CurtirFilme AS CF2 WHERE (CF1.\"idFilme\" = CF2.\"idFilme\") AND (loginA = CF1.login) AND (loginB = CF2.login) GROUP BY loginA, loginB ORDER BY count(CF1.\"idFilme\") DESC"
    db.execute(query)
    result = db.fetchall()

    print("+----------------------+----------------------+------+")
    print(formatTemplate.format("Usuário 1","Usuário 2","Num"))
    print("+----------------------+----------------------+------+")
    for row in result:
        print(formatTemplate.format(row[0],row[1],str(row[2])))
    print("+----------------------+----------------------+------+")
    
# ================================================================================
# Interface
# ================================================================================

showTopConhecidosMsmFilmes()

##menu = True
##while menu:
##    print("\n[1] Exibir média e desvio padrão de ratings")
##    print("[2] Exibir artistas ou filmes com maior rating médio")
##    print("[3] Exibir top10 artistas ou filmes mais populares")
##    print("[4] Exibir relacionamentos simetricamente")
##    print("[5] Exibir conhecidos com maior número de filmes em comum")
##    print("[6] Exibir conhecidos de conhecidos para cada usuário")
##    print("[7] Exibir gráfico de pessoas por números de filmes curtidos")
##    print("[8] Exibir gráfico de números de filmes curtidos por pessoas")
##    print("[9] ...")
##    print("[10] ...")
##    print("[11] Sair") 
##
##    case = raw_input("Digite uma opção: ") # Apenas input() em Python3
##    if case == "1":
##        print("Opção escolhida: [1] Exibir média e desvio padrão de ratings")
##        showAvgDev()
##    elif case == "2":
##        print("Opção escolhida: [2] Exibir artistas ou filmes com maior rating médio")
##    elif case == "3":
##        print("Opção escolhida: [3] Exibir top10 artistas ou filmes mais populares")
##    elif case == "4":
##        print("Opção escolhida: [4] Exibir relacionamentos simetricamente")
##    elif case == "5":
##        print("Opção escolhida: [5] Exibir conhecidos com maior número de filmes em comum")
##    elif case == "6":
##        print("Opção escolhida: [6] Exibir conhecidos de conhecidos para cada usuário")
##    elif case == "7":
##        print("Opção escolhida: [7] Exibir gráfico de pessoas por números de filmes curtidos")
##    elif case == "8":
##        print("Opção escolhida: [8] Exibir gráfico de números de filmes curtidos por pessoas")
##    elif case == "9":
##        print("Opção escolhida: [9] ")
##    elif case == "10":
##        print("Opção escolhida: [10] ")
##    elif case == "11":
##        menu = False
##    else:
##        print("Opção inválida")
##
##if not db.is_closed():
##    db.close()

db.close()
conn.close()
