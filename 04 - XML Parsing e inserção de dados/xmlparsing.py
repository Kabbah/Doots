from urllib.request import urlopen
from bs4 import BeautifulSoup # Necessitar baixar: pip install bs4
from peewee import * # Pelo amor de deus conserta isso aki, n deixa como import *
import psycopg2

print('Obtendo dados da web...')

# Hmm... Sopa...
personSoup = BeautifulSoup(urlopen('http://dainf.ct.utfpr.edu.br/~gomesjr/BD1/data/person.xml'),'xml')
musicSoup = BeautifulSoup(urlopen('http://dainf.ct.utfpr.edu.br/~gomesjr/BD1/data/music.xml'),'xml')
movieSoup = BeautifulSoup(urlopen('http://dainf.ct.utfpr.edu.br/~gomesjr/BD1/data/movie.xml'),'xml')
knowsSoup = BeautifulSoup(urlopen('http://dainf.ct.utfpr.edu.br/~gomesjr/BD1/data/knows.xml'),'xml')

# Cada um desses retorna uma array de cada linha nos xml
entriesPerson = personSoup.find_all("Person") # Para acessar, por exemplo, o atributo "name", basta usar entriesPerson[i]["name"]
entriesMusic = musicSoup.find_all("LikesMusic") # Para o atributo "rating": entriesMusic[i]["rating"] e assim vai
entriesMovie = movieSoup.find_all("LikesMovie")
entriesKnows = knowsSoup.find_all("Knows")


print('Dados obtidos com sucesso.')

db = PostgresqlDatabase(
    '1717545_Victor',
    user='m0n0p0ly',
    password='#n0m0n3y#',
    host='200.134.10.32'
)

db.connect()

class BaseModel(Model):
    class Meta:
        database = db

class Usuario(BaseModel):
    login = CharField(primary_key = True)
    nomeCompleto = CharField()
    cidadeNatal = CharField()

db.create_tables([Usuario], safe=True)
for user in entriesPerson:
	# Comentando pq não testado
	#Usuario.create(
	#    login = user["uri"].lstrip('http://utfpr.edu.br/CSB30/2017/1/'),
	#    nomeCompleto = user["name"].title(), # title() deixa a primeira letra de cada palavra maiuscula
	#    cidadeNatal = user["hometown"].title()
	#)
result = Usuario.select()
for user in result:
    print(user.login)

if not db.is_closed():
    db.close()
