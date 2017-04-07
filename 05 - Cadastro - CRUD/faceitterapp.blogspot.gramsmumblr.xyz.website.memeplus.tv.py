#!/usr/bin/python
# -*- coding: utf-8 -*-

import sys
import peewee as pw
import psycopg2
from getpass import getpass

if(len(sys.argv) == 5):
	ihost = sys.argv[1]
	iuser = sys.argv[2]
	ipassword = sys.argv[3]
	idatabase = sys.argv[4]
else:
	ihost = raw_input("Digite o IP do host\n") # python 3 usa só input()
	iuser = raw_input("Digite o usuário\n")
	ipassword = getpass("Digite a senha\n")
	idatabase = raw_input("Digite o banco de dados\n")

try:
	db = pw.PostgresqlDatabase(idatabase, user=iuser, password=ipassword, host=ihost)
	db.connect()
except Exception as e:
	print(e)
	exit()
print("")

while True:
	print("[1] Cadastrar uma pessoa\n[2] Listar/Editar pessoas")
	opcao = raw_input("Digite uma opção: ")
	if opcao not in ("1","2"):
		print("Opção inválida\n")
		continue
	
	print("yay")
	break

