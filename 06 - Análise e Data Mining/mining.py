# -*- coding: utf-8 -*-

import psycopg2
import peewee as pw

# ================================================================================
# Conexão com o banco de dados
# ================================================================================

ihost = "200.134.10.32"
iuser = "m0n0p0ly"
ipassword = "#n0m0n3y#"
idatabase = "1717545_Victor"

try:
    db = pw.PostgresqlDatabase(idatabase, user=iuser, password=ipassword, host=ihost)
    db.connect()
except Exception as e:
    print(e)
    exit()
print("")

# ================================================================================
# Descrição do banco de dados em classes
# ================================================================================

class BaseModel(pw.Model):
    class Meta:
        database = db

class Usuario(BaseModel):
    login = pw.CharField(primary_key = True)
    nomeCompleto = pw.CharField()
    cidadeNatal = pw.CharField()

class UsuarioConhece(BaseModel):
    loginSujeito = pw.ForeignKeyField(Usuario, related_name = "conhecidos", db_column = "loginSujeito")
    loginConhecido = pw.ForeignKeyField(Usuario, db_column = "loginConhecido")
    class Meta:
        primary_key = pw.CompositeKey("loginSujeito", "loginConhecido")

class CurtirFilme(BaseModel):
    login = pw.ForeignKeyField(Usuario, related_name = "filmescurtidos", db_column = "login")
    idFilme = pw.CharField()
    nota = pw.IntegerField()
    class Meta:
        primary_key = pw.CompositeKey("login", "idFilme")

class CurtirArtistaMusical(BaseModel):
    login = pw.ForeignKeyField(Usuario, related_name = "artistascurtidos", db_column = "login")
    idArtistaMusical = pw.CharField()
    nota = pw.IntegerField()
    class Meta:
        primary_key = pw.CompositeKey("login", "idArtistaMusical")

# ================================================================================
# Operações
# ================================================================================

def showAvgDev():
    formatTemplate = "|{0:9}|{1:4.4}|{2:>4.4}|"
    queryFilme = CurtirFilme.select(CurtirFilme.idFilme,pw.fn.Avg(CurtirFilme.nota).over(partition_by=[CurtirFilme.idFilme]),pw.fn.Stddev(CurtirFilme.nota).over(partition_by=[CurtirFilme.idFilme])).distinct()
    print(formatTemplate.format("Filme","Avg","SDev"))
    print("|---------|----|----|")
    for result in queryFilme:
        print(formatTemplate.format(result.idFilme,str(result.avg),str(result.stddev)))

# ================================================================================
# Interface
# ================================================================================


menu = True
while menu:
    print("\n[1] Exibir média e desvio padrão de ratings")
    print("[2] Exibir artistas ou filmes com maior rating médio")
    print("[3] Exibir top10 artistas ou filmes mais populares")
    print("[4] Exibir relacionamentos simetricamente")
    print("[5] Exibir conhecidos com maior número de filmes em comum")
    print("[6] Exibir conhecidos de conhecidos para cada usuário")
    print("[7] Exibir gráfico de pessoas por números de filmes curtidos")
    print("[8] Exibir gráfico de números de filmes curtidos por pessoas")
    print("[9] ...")
    print("[10] ...")
    print("[11] Sair") 

    case = raw_input("Digite uma opção: ") # Apenas input() em Python3
    if case == "1":
        print("Opção escolhida: [1] Exibir média e desvio padrão de ratings")
        showAvgDev()
    elif case == "2":
        print("Opção escolhida: [2] Exibir artistas ou filmes com maior rating médio")
    elif case == "3":
        print("Opção escolhida: [3] Exibir top10 artistas ou filmes mais populares")
    elif case == "4":
        print("Opção escolhida: [4] Exibir relacionamentos simetricamente")
    elif case == "5":
        print("Opção escolhida: [5] Exibir conhecidos com maior número de filmes em comum")
    elif case == "6":
        print("Opção escolhida: [6] Exibir conhecidos de conhecidos para cada usuário")
    elif case == "7":
        print("Opção escolhida: [7] Exibir gráfico de pessoas por números de filmes curtidos")
    elif case == "8":
        print("Opção escolhida: [8] Exibir gráfico de números de filmes curtidos por pessoas")
    elif case == "9":
        print("Opção escolhida: [9] ")
    elif case == "10":
        print("Opção escolhida: [10] ")
    elif case == "11":
        menu = False
    else:
        print("Opção inválida")

if not db.is_closed():
    db.close()
