from urllib.request import urlopen
from bs4 import BeautifulSoup # Necessitar baixar: pip install bs4
from peewee import * # Pelo amor de deus conserta isso aki, n deixa como import *
import psycopg2

# ================================================================================
# Parsing dos arquivos XML
# ================================================================================

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

# ================================================================================
# Conexão com o banco de dados
# ================================================================================

db = PostgresqlDatabase(
    '1717545_Victor',
    user='m0n0p0ly',
    password='#n0m0n3y#',
    host='200.134.10.32'
)

db.connect()

# Classes modelo das quais todas as tabelas herdam
class BaseModel(Model):
    class Meta:
        database = db

class Usuario(BaseModel):
    login = CharField(primary_key = True)
    nomeCompleto = CharField()
    cidadeNatal = CharField()

# Classes referentes a cada tabela
class UsuarioConhece(BaseModel):
    loginSujeito = ForeignKeyField(Usuario)
    loginConhecido = ForeignKeyField(Usuario)
    class Meta:
        primary_key = CompositeKey("loginSujeito", "loginConhecido")

class UsuarioBloqueia(BaseModel):
    loginSujeito = ForeignKeyField(Usuario)
    loginBloqueado = ForeignKeyField(Usuario)
    razaoSpam = BooleanField()
    razaoAbusivo = BooleanField()
    razaoPessoal = BooleanField()
    razaoOutra = CharField()
    class Meta:
        primary_key = CompositeKey("loginSujeito", "loginBloqueado")

class ArtistaCinema(BaseModel):
    id = PrimaryKeyField()
    endereco = CharField()
    telefone = CharField()

class Filme(BaseModel):
    id = PrimaryKeyField()
    nome = CharField()
    dataLancamento = DateField()
    idDiretor = ForeignKeyField(ArtistaCinema)

class AtorFilme(BaseModel):
    idFilme = ForeignKeyField(Filme)
    idAtor = ForeignKeyField(ArtistaCinema)
    class Meta:
        primary_key = CompositeKey("idFilme", "idAtor")

class Categoria(BaseModel):
    id = PrimaryKeyField()
    nome = CharField()
    idSupercategoria = ForeignKeyField("self", null = True)

class ClassificacaoFilme(BaseModel):
    idFilme = ForeignKeyField(Filme)
    idCategoria = ForeignKeyField(Categoria)
    class Meta:
        primary_key = CompositeKey("idFilme", "idCategoria")

# TODO: Terminar essas classes

# Cria as tabelas no banco de dados caso não existam (safe = True)
db.create_tables([Usuario], safe=True)

for user in entriesPerson:
	# Comentando pq não testado
	#Usuario.create(
	#    login = user["uri"].lstrip('http://utfpr.edu.br/CSB30/2017/1/'),
	#    nomeCompleto = user["name"].title(), # title() deixa a primeira letra de cada palavra maiuscula
	#    cidadeNatal = user["hometown"].title()
	#)

# Equivalente a SELECT * FROM Usuario
# result = Usuario.select()
# for user in result:
#    print(user.login)

if not db.is_closed():
    db.close()
