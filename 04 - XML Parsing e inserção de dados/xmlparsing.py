from urllib.request import urlopen
from bs4 import BeautifulSoup
from peewee import * # Pelo amor de deus conserta isso aki, n deixa como import *
import psycopg2

# Fazer antes de rodar o script:
# pip install bs4
# pip install lxml
# pip install peewee
# pip install psycopg2

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

# ================================================================================
# Descrição do banco de dados em classes
# ================================================================================

# Classe modelo da qual todas as outras classes (tabelas) herdam
class BaseModel(Model):
    class Meta:
        database = db

# Classes referentes a cada tabela
# Observação: Para esse exercício, algumas classes foram simplificadas,
# e classes não utilizadas foram comentadas.
class Usuario(BaseModel):
    login = CharField(primary_key = True)
    nomeCompleto = CharField()
    cidadeNatal = CharField()

class UsuarioConhece(BaseModel):
    loginSujeito = ForeignKeyField(Usuario, related_name = "conhecidos")
    loginConhecido = ForeignKeyField(Usuario)
    class Meta:
        primary_key = CompositeKey("loginSujeito", "loginConhecido")

##class UsuarioBloqueia(BaseModel):
##    loginSujeito = ForeignKeyField(Usuario, related_name = "bloqueados")
##    loginBloqueado = ForeignKeyField(Usuario)
##    razaoSpam = BooleanField()
##    razaoAbusivo = BooleanField()
##    razaoPessoal = BooleanField()
##    razaoOutra = CharField()
##    class Meta:
##        primary_key = CompositeKey("loginSujeito", "loginBloqueado")

##class ArtistaCinema(BaseModel):
##    id = PrimaryKeyField()
##    endereco = CharField()
##    telefone = CharField()

##class Filme(BaseModel):
##    id = PrimaryKeyField()
##    nome = CharField()
##    dataLancamento = DateField()
##    idDiretor = ForeignKeyField(ArtistaCinema, related_name = "filmes")

##class AtorFilme(BaseModel):
##    idFilme = ForeignKeyField(Filme, related_name = "atores")
##    idAtor = ForeignKeyField(ArtistaCinema)
##    class Meta:
##        primary_key = CompositeKey("idFilme", "idAtor")

##class Categoria(BaseModel):
##    id = PrimaryKeyField()
##    nome = CharField()
##    idSupercategoria = ForeignKeyField("self", null = True, related_name = "subcategorias")

##class ClassificacaoFilme(BaseModel):
##    idFilme = ForeignKeyField(Filme, related_name = "categorias")
##    idCategoria = ForeignKeyField(Categoria, related_name = "filmes")
##    class Meta:
##        primary_key = CompositeKey("idFilme", "idCategoria")

# Essa classe foi simplificada, pois não há necessidade de ter uma tabela Filme para esse exercício.
##class CurtirFilme(BaseModel):
##    login = ForeignKeyField(Usuario, related_name = "filmescurtidos")
##    idFilme = ForeignKeyField(Filme, related_name = "usuariosquecurtem")
##    nota = IntegerField()
##    class Meta:
##        primary_key = CompositeKey("login", "idFilme")
class CurtirFilme(BaseModel):
    login = ForeignKeyField(Usuario, related_name = "filmescurtidos")
    idFilme = CharField()
    nota = IntegerField()
    class Meta:
        primary_key = CompositeKey("login", "idFilme")

##class ArtistaMusical(BaseModel):
##    id = PrimaryKeyField()
##    pais = CharField()
##    genero = CharField()
##    nomeArtistico = CharField()

##class Banda(BaseModel):
##    id = ForeignKeyField(ArtistaMusical, primary_key = True)

##class Musico(BaseModel):
##    nomeReal = CharField(primary_key = True)
##    estiloMusical = CharField()
##    idBanda = ForeignKeyField(Banda, related_name = "musicos")
##    dataNascimento = DateField()

##class Cantor(BaseModel):
##    id = ForeignKeyField(ArtistaMusical, related_name = "cantor")
##    nomeMusico = ForeignKeyField(Musico, related_name = "cantorRel")
##    class Meta:
##        primary_key = CompositeKey("id", "nomeMusico")

# Essa classe foi simplificada, pois as informações do XML não são suficientes para
# justificar a necessidade das classes ArtistaMusica, Banda, Musico e Cantor.
##class CurtirArtistaMusical(BaseModel):
##    login = ForeignKeyField(Usuario, related_name = "artistascurtidos")
##    idArtistaMusical = ForeignKeyField(ArtistaMusical, related_name = "usuariosquecurtem")
##    nota = IntegerField()
##    class Meta:
##        primary_key = CompositeKey("login", "idArtistaMusical")
class CurtirArtistaMusical(BaseModel):
    login = ForeignKeyField(Usuario, related_name = "artistascurtidos")
    idArtistaMusical = CharField()
    nota = IntegerField()
    class Meta:
        primary_key = CompositeKey("login", "idArtistaMusical")

# ================================================================================
# Execução de queries no banco de dados
# ================================================================================

# Cria as tabelas no banco de dados caso não existam (safe = True)
# Deixando comentado por enquanto.
##db.create_tables([Usuario, UsuarioConhece, CurtirFilme, CurtirArtistaMusical], safe=True)

# Cria os registros no banco de dados
# Deixando comentado por enquanto.
##for user in entriesPerson:
##    Usuario.create(
##        login = user["uri"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1),
##        nomeCompleto = user["name"].title(), # title() deixa a primeira letra de cada palavra maiuscula
##        cidadeNatal = user["hometown"].title()
##    )
##
##for entry in entriesKnows:
##    UsuarioConhece.create(
##        loginSujeito = entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1),
##        loginConhecido = entry["colleague"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1)
##    )
##
##for entry in entriesMusic:
##    CurtirArtistaMusical.create(
##        login = entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1),
##        idArtistaMusical = entry["bandUri"].replace("https://en.wikipedia.org/wiki/", "", 1),
##        nota = int(entry["rating"])
##    )
##
##for entry in entriesMovie:
##    movieLink = entry["movieUri"].rstrip("/")
##    CurtirFilme.create(
##        login = entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1),
##        idFilme = movieLink.replace("http://www.imdb.com/title/", "", 1),
##        nota = int(entry["rating"])
##    )

# Debug
for user in entriesPerson:
    print(user["uri"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1))
    print(user["name"].title())
    print(user["hometown"].title())
    
for entry in entriesKnows:
    print(
        entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1) + " " +
        entry["colleague"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1)
    )

for entry in entriesMusic:
    print(
        entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1) + " " +
        entry["bandUri"].replace("https://en.wikipedia.org/wiki/", "", 1) + " " +
        entry["rating"]
    )

for entry in entriesMovie:
    movieLink = entry["movieUri"].rstrip("/")
    print(
        entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1) + " " +
        movieLink.replace("http://www.imdb.com/title/", "", 1) + " " +
        entry["rating"]
    )

# Equivalente a SELECT * FROM Usuario
# result = Usuario.select()
# for user in result:
#    print(user.login)

# ================================================================================
# Fechamento da conexão com o banco de dados
# ================================================================================

if not db.is_closed():
    db.close()
