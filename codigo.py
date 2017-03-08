#!/usr/bin/python

from xml.dom.minidom import parse
import xml.dom.minidom
#import os

#os.mkdir("dadosMarvel")
#file = open("herois.csv", 'w')

dom = parse("marvel_simplificado.xml")
universe = dom.documentElement
heroAttributes = ['name', 'popularity', 'alignment', 'gender', 'height_m', 'weight_kg', 'hometown', 'intelligence', 'strength', 'speed', 'durability', 'energy_Projection', 'fighting_Skills']

heroes = universe.getElementsByTagName("hero")
for hero in heroes:
    for attr in heroAttributes:
        print(hero.getElementByTagName(attr)[0].childNodes[0].data, end = ', ')





