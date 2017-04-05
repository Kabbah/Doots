from urllib.request import urlopen
from bs4 import BeautifulSoup
import peewee as pw
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

db = pw.PostgresqlDatabase(
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
class BaseModel(pw.Model):
    class Meta:
        database = db

# Classes referentes a cada tabela
# Observação: Para esse exercício, algumas classes foram simplificadas,
# e classes não utilizadas foram comentadas.
class Usuario(BaseModel):
    login = pw.CharField(primary_key = True)
    nomeCompleto = pw.CharField()
    cidadeNatal = pw.CharField()

class UsuarioConhece(BaseModel):
    loginSujeito = pw.ForeignKeyField(Usuario, related_name = "conhecidos", db_column = "loginSujeito")
    loginConhecido = pw.ForeignKeyField(Usuario, db_column = "loginConhecido")
    class Meta:
        primary_key = pw.CompositeKey("loginSujeito", "loginConhecido")

##class UsuarioBloqueia(BaseModel):
##    loginSujeito = pw.ForeignKeyField(Usuario, related_name = "bloqueados")
##    loginBloqueado = pw.ForeignKeyField(Usuario)
##    razaoSpam = pw.BooleanField()
##    razaoAbusivo = pw.BooleanField()
##    razaoPessoal = pw.BooleanField()
##    razaoOutra = pw.CharField()
##    class Meta:
##        primary_key = pw.CompositeKey("loginSujeito", "loginBloqueado")

##class ArtistaCinema(BaseModel):
##    id = pw.PrimaryKeyField()
##    endereco = pw.CharField()
##    telefone = pw.CharField()

##class Filme(BaseModel):
##    id = pw.PrimaryKeyField()
##    nome = pw.CharField()
##    dataLancamento = pw.DateField()
##    idDiretor = pw.ForeignKeyField(ArtistaCinema, related_name = "filmes")

##class AtorFilme(BaseModel):
##    idFilme = pw.ForeignKeyField(Filme, related_name = "atores")
##    idAtor = pw.ForeignKeyField(ArtistaCinema)
##    class Meta:
##        primary_key = pw.CompositeKey("idFilme", "idAtor")

##class Categoria(BaseModel):
##    id = pw.PrimaryKeyField()
##    nome = pw.CharField()
##    idSupercategoria = pw.ForeignKeyField("self", null = True, related_name = "subcategorias")

##class ClassificacaoFilme(BaseModel):
##    idFilme = pw.ForeignKeyField(Filme, related_name = "categorias")
##    idCategoria = pw.ForeignKeyField(Categoria, related_name = "filmes")
##    class Meta:
##        primary_key = pw.CompositeKey("idFilme", "idCategoria")

# Essa classe foi simplificada, pois não há necessidade de ter uma tabela Filme para esse exercício.
##class CurtirFilme(BaseModel):
##    login = pw.ForeignKeyField(Usuario, related_name = "filmescurtidos")
##    idFilme = pw.ForeignKeyField(Filme, related_name = "usuariosquecurtem")
##    nota = pw.IntegerField()
##    class Meta:
##        primary_key = pw.CompositeKey("login", "idFilme")
class CurtirFilme(BaseModel):
    login = pw.ForeignKeyField(Usuario, related_name = "filmescurtidos", db_column = "login")
    idFilme = pw.CharField()
    nota = pw.IntegerField()
    class Meta:
        primary_key = pw.CompositeKey("login", "idFilme")

##class ArtistaMusical(BaseModel):
##    id = pw.PrimaryKeyField()
##    pais = pw.CharField()
##    genero = pw.CharField()
##    nomeArtistico = pw.CharField()

##class Banda(BaseModel):
##    id = pw.ForeignKeyField(ArtistaMusical, primary_key = True)

##class Musico(BaseModel):
##    nomeReal = pw.CharField(primary_key = True)
##    estiloMusical = pw.CharField()
##    idBanda = pw.ForeignKeyField(Banda, related_name = "musicos")
##    dataNascimento = pw.DateField()

##class Cantor(BaseModel):
##    id = pw.ForeignKeyField(ArtistaMusical, related_name = "cantor")
##    nomeMusico = pw.ForeignKeyField(Musico, related_name = "cantorRel")
##    class Meta:
##        primary_key = pw.CompositeKey("id", "nomeMusico")

# Essa classe foi simplificada, pois as informações do XML não são suficientes para
# justificar a necessidade das classes ArtistaMusica, Banda, Musico e Cantor.
##class CurtirArtistaMusical(BaseModel):
##    login = pw.ForeignKeyField(Usuario, related_name = "artistascurtidos")
##    idArtistaMusical = pw.ForeignKeyField(ArtistaMusical, related_name = "usuariosquecurtem")
##    nota = pw.IntegerField()
##    class Meta:
##        primary_key = pw.CompositeKey("login", "idArtistaMusical")
class CurtirArtistaMusical(BaseModel):
    login = pw.ForeignKeyField(Usuario, related_name = "artistascurtidos", db_column = "login")
    idArtistaMusical = pw.CharField()
    nota = pw.IntegerField()
    class Meta:
        primary_key = pw.CompositeKey("login", "idArtistaMusical")

# ================================================================================
# Execução de queries no banco de dados
# ================================================================================

# Cria as tabelas no banco de dados caso não existam (safe = True)
db.create_tables([Usuario, UsuarioConhece, CurtirFilme, CurtirArtistaMusical], safe=True)

# Cria os registros no banco de dados
print("Iniciando registro de usuários...")
for user in entriesPerson:
    try:
        Usuario.create(
            login = user["uri"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1),
            nomeCompleto = user["name"].title(), # title() deixa a primeira letra de cada palavra maiuscula
            cidadeNatal = user["hometown"].title()
        )
    except pw.IntegrityError as erro:
        print(erro)
        print("O erro ocorreu ao tentar adicionar o seguinte registro:")
        print("Tabela: Usuario")
        print("login = " + user["uri"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1))
        print("nomeCompleto = " + user["name"].title())
        print("cidadeNatal = " + user["hometown"].title())
        print("Este registro foi ignorado e a inserção dos demais registros prosseguirá normalmente.")
        print()
        db.rollback()
print("Registro de usuários finalizado.")

print("Iniciando registro de conhecidos...")
for entry in entriesKnows:
    try:
        UsuarioConhece.create(
            loginSujeito = entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1),
            loginConhecido = entry["colleague"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1)
        )
    except pw.IntegrityError as erro:
        print(erro)
        print("O erro ocorreu ao tentar adicionar o seguinte registro:")
        print("Tabela: UsuarioConhece")
        print("loginSujeito = " + entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1))
        print("loginConhecido = " + entry["colleague"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1))
        print("Este registro foi ignorado e a inserção dos demais registros prosseguirá normalmente.")
        print()
        db.rollback()
print("Registro de conhecidos finalizado.")

print("Iniciando registro de curtidas de artistas musicais...")
for entry in entriesMusic:
    try:
        CurtirArtistaMusical.create(
            login = entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1),
            idArtistaMusical = entry["bandUri"].replace("https://en.wikipedia.org/wiki/", "", 1),
            nota = int(entry["rating"])
        )
    except pw.IntegrityError as erro:
        print(erro)
        print("O erro ocorreu ao tentar adicionar o seguinte registro:")
        print("Tabela: CurtirArtistaMusical")
        print("login = " + entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1))
        print("idArtistaMusical = " + entry["bandUri"].replace("https://en.wikipedia.org/wiki/", "", 1))
        print("nota = " + entry["rating"])
        print("Este registro foi ignorado e a inserção dos demais registros prosseguirá normalmente.")
        print()
        db.rollback()
print("Registro de curtidas de artistas musicais finalizado.")

print("Iniciando registro de curtidas de filmes...")
for entry in entriesMovie:
    movieLink = entry["movieUri"].rstrip("/")
    try:
        CurtirFilme.create(
            login = entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1),
            idFilme = movieLink.replace("http://www.imdb.com/title/", "", 1),
            nota = int(entry["rating"])
        )
    except pw.IntegrityError as erro:
        print(erro)
        print("O erro ocorreu ao tentar adicionar o seguinte registro:")
        print("Tabela: CurtirFilme")
        print("login = " + entry["person"].replace("http://utfpr.edu.br/CSB30/2017/1/", "", 1))
        print("idFilme = " + movieLink.replace("http://www.imdb.com/title/", "", 1))
        print("nota = " + entry["rating"])
        print("Este registro foi ignorado e a inserção dos demais registros prosseguirá normalmente.")
        print()
        db.rollback()
print("Registro de curtidas de filmes finalizado.")

# ================================================================================
# Fechamento da conexão com o banco de dados
# ================================================================================

print("Desconectando-se do banco de dados...")
if not db.is_closed():
    db.close()
