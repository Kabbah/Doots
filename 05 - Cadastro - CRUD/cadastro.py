#!/usr/bin/python
# -*- coding: utf-8 -*-

import sys
import peewee as pw
import psycopg2
from getpass import getpass

# ================================================================================
# Conexão com o banco de dados
# ================================================================================

##if(len(sys.argv) == 5):
##    ihost = sys.argv[1]
##    iuser = sys.argv[2]
##    ipassword = sys.argv[3]
##    idatabase = sys.argv[4]
##else:
##    ihost = input("Digite o IP do host\n") # python 2 usa raw_input()
##    iuser = input("Digite o usuário\n")
##    ipassword = getpass("Digite a senha\n")
##    idatabase = input("Digite o banco de dados\n")

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

# ================================================================================
# Interface
# ================================================================================

def createUser():
    try:
        Usuario.create(
            login = input("Digite o login: "),
            nomeCompleto = input("Digite o nome completo: "),
            cidadeNatal = input("Digite a cidade natal: ")
        )
    except pw.IntegrityError:
        print("Não foi possível cadastrar o usuário porque já existe outro usuário cadastrado com o mesmo login.")
        db.rollback()
    else:
        print("Usuário criado com sucesso.")

def createUserKnows():
    try:
        UsuarioConhece.create(
            loginSujeito = input("Digite o login do usuário principal: "),
            loginConhecido = input("Digite o login do conhecido: ")
        )
    except pw.IntegrityError:
        print("Não foi possível cadastrar o relacionamento porque ele já existe.")
        db.rollback()
    else:
        print("Relacionamento criado com sucesso.")

def updateUser():
    oldLogin = input("Digite o login atual do usuário: ")
    try:
        usuario = Usuario.get(Usuario.login == oldLogin)
    except Usuario.DoesNotExist:
        print("Não há um usuário cadastrado com este login.")
        db.rollback()
    else:
        print("")
        # Aqui vai ter um UPDATE
        # Jeitos de fazer:
        # Primeiro: mostra um atributo, pede se quer alterar, mostra outro, pede se quer alterar...
        # Segundo: mostra todos os atributos, pede pra digitar valores novos e f*da-se

def deleteUser():
    oldLogin = input("Digite o login do usuário a ser excluído: ")
    try:
        usuario = Usuario.get(Usuario.login == oldLogin)
    except Usuario.DoesNotExist:
        print("Não há um usuário cadastrado com este login.")
    else:
        try:
            usuario.delete_instance()
        except pw.IntegrityError:
            print("Não foi possivel excluir o usuário porque ele possui relacionamentos no sistema.")
            db.rollback()
        else:
            print("Usuário excluído com sucesso.")
                  

def showUsers():
    for usuario in Usuario.select().order_by(Usuario.login.asc()):
        print(usuario.login)
    return

def editUserMenu():
    editingUsers = True
    while editingUsers:
        print("")
        print("Edição de usuários")
        print("[1] Alterar dados de um usuário")
        print("[2] Adicionar conhecido de um usuário")
        print("[3] Excluir um usuário")
        print("[4] Retornar ao menu principal")
        opcao = input("Digite uma opção: ")
        print("")

        if opcao == "1":
            print("Opção selecionada: [1] Alterar dados de um usuário")
            updateUser()
        elif opcao == "2":
            print("Opção selecionada: [2] Adicionar conhecido de um usuário")
            createUserKnows()
        elif opcao == "3":
            print("Opção selecionada: [3] Excluir um usuário")
            deleteUser()
        else:
            editingUsers = False
    
running = True
while running:
    print("")
    print("Menu principal")
    print("[1] Cadastrar um usuário")
    print("[2] Listar/Editar usuários")
    print("[3] Sair")
    opcao = input("Digite uma opção: ")
    print("")
    
    if opcao == "1":
        print("Opção selecionada: [1] Cadastrar um usuário")
        createUser()
    elif opcao == "2":
        print("Opção selecionada: [2] Listar/Editar usuários")
        showUsers()
        editUserMenu()
    elif opcao == "3":
        running = False
    else:
        print("Opção inválida.")

if not db.is_closed():
    db.close()
