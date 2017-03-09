#!/usr/bin/python

from xml.dom.minidom import parse
import xml.dom.minidom
import os

if os.path.exists("dadosMarvel") == False:
    os.mkdir("dadosMarvel")

if os.path.exists("dadosMarvel/herois.csv"):
    os.remove("dadosMarvel/herois.csv")
file = open("dadosMarvel/herois.csv", "w")

if os.path.exists("dadosMarvel/herois_good.csv"):
    os.remove("dadosMarvel/herois_good.csv")
fileGood = open("dadosMarvel/herois_good.csv", "w")

if os.path.exists("dadosMarvel/herois_bad.csv"):
    os.remove("dadosMarvel/herois_bad.csv")
fileBad = open("dadosMarvel/herois_bad.csv", "w")

dom = parse("marvel_simplificado.xml")
universe = dom.documentElement

heroAttributes = ['name', 'popularity', 'alignment', 'gender', 'height_m', 'weight_kg', 'hometown', 'intelligence', 'strength', 'speed', 'durability', 'energy_Projection', 'fighting_Skills']

num = 0
numGood = 0
numBad = 0
totalWeight = 0

heroes = universe.getElementsByTagName("hero")
for hero in heroes:
    alignment = hero.getElementsByTagName("alignment")[0].childNodes[0].data
    
    file.write(hero.getAttribute("id"))
    num += 1
    if alignment == "Good":
        fileGood.write(hero.getAttribute("id"))
        numGood += 1
    elif alignment == "Bad":
        fileBad.write(hero.getAttribute("id"))
        numBad += 1

    totalWeight += float(hero.getElementsByTagName("weight_kg")[0].childNodes[0].data)

    if hero.getElementsByTagName("name")[0].childNodes[0].data == "Hulk":
        massa = float(hero.getElementsByTagName("weight_kg")[0].childNodes[0].data)
        altura = float(hero.getElementsByTagName("height_m")[0].childNodes[0].data)
    
    for attr in heroAttributes:
        file.write(", %s" % hero.getElementsByTagName(attr)[0].childNodes[0].data)
        if alignment == "Good":
            fileGood.write(", %s" % hero.getElementsByTagName(attr)[0].childNodes[0].data)
        elif alignment == "Bad":
            fileBad.write(", %s" % hero.getElementsByTagName(attr)[0].childNodes[0].data)
        
    file.write("\n")
    if alignment == "Good":
        fileGood.write("\n")
    elif alignment == "Bad":
        fileBad.write("\n")

file.close()
fileGood.close()
fileBad.close()

print("Proporção de bons/maus: %f" % (float(numGood)/numBad))
print("Média de peso dos herois: %f" % (totalWeight/num))
print("IMC do Hulk: %f" % (massa/altura/altura))
