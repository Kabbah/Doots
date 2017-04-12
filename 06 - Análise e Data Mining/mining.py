# -*- coding: utf-8 -*-

import psycopg2
import peewee as pw


def showMenu():
    menu = True
    while menu:
        print("\n[1] Exibir média e desvio padrão")
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
            print("Opção escolhida: [1] Exibir média e desvio padrão")
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
            exit()
        else:
            print("Opção inválida")

showMenu()
    
   
