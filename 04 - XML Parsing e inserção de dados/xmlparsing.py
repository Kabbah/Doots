import requests
from xml.dom import minidom
from peewee import *
import psycopg2

print('Obtendo dados da web...')

personFile = requests.get('http://dainf.ct.utfpr.edu.br/~gomesjr/BD1/data/person.xml')
personData = minidom.parseString(personFile.text)

musicFile = requests.get('http://dainf.ct.utfpr.edu.br/~gomesjr/BD1/data/music.xml')
musicData = minidom.parseString(musicFile.text)

movieFile = requests.get('http://dainf.ct.utfpr.edu.br/~gomesjr/BD1/data/movie.xml')
movieData = minidom.parseString(movieFile.text)

knowsFile = requests.get('http://dainf.ct.utfpr.edu.br/~gomesjr/BD1/data/knows.xml')
knowsData = minidom.parseString(knowsFile.text)

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
#Usuario.create(
#    login = 'teste',
#    nomeCompleto = 'Teste',
#    cidadeNatal = 'Teste'
#)
result = Usuario.select()
for user in result:
    print(user.login)

if not db.is_closed():
    db.close()
