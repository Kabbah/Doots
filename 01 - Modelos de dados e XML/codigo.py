#!/usr/bin/python

from xml.dom.minidom import parse
import xml.dom.minidom
import os

if os.path.exists("dadosMarvel") == False:
    os.mkdir("dadosMarvel")

if os.path.exists("dadosMarvel/herois.csv"):
    os.remove("dadosMarvel/herois.csv")
file = open("dadosMarvel/herois.csv", "w")

dom = parse("marvel_simplificado.xml")
universe = dom.documentElement
heroAttributes = ['name', 'popularity', 'alignment', 'gender', 'height_m', 'weight_kg', 'hometown', 'intelligence', 'strength', 'speed', 'durability', 'energy_Projection', 'fighting_Skills']

heroes = universe.getElementsByTagName("hero")
for hero in heroes:
    #print(hero.getAttribute("id"), end = "")
    file.write(hero.getAttribute("id"))
    for attr in heroAttributes:
        #print(", %s" % hero.getElementsByTagName(attr)[0].childNodes[0].data, end = "")
        file.write(", %s" % hero.getElementsByTagName(attr)[0].childNodes[0].data)
    #print("")
    file.write("\n")

file.close()
