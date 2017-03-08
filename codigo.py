#!/usr/bin/python

from xml.dom.minidom import parse
import xml.dom.minidom
#import os

#os.mkdir("dadosMarvel")
#file = open("herois.csv", 'w')

dom = parse("marvel_simplificado.xml")
universe = dom.documentElement
heroes = universe.getElementsByTagName("hero")
for hero in heroes:
    print "%s" % hero.getAttribute("id"),
    print ", %s" % hero.getElementsByTagName("name")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("popularity")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("alignment")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("gender")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("height_m")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("weight_kg")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("hometown")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("intelligence")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("strength")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("speed")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("durability")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("energy_Projection")[0].childNodes[0].data,
    print ", %s" % hero.getElementsByTagName("fighting_Skills")[0].childNodes[0].data






