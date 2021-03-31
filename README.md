Projeto

Requisitos:

Git
Docker
Postman ou outro software para fazer requisições na api.

Guia de instalação:

1º: Entrar no arquivo hosts no caminho 'C:\Windows\System32\drivers\etc\hosts' pra Windows,  '/private/etc/hosts' ou '/etc/hosts para' MAC ou execute no terminal 'cat /etc/hosts' para linux 
Adicionar 127.0.0.1 mundodamusica.com na ultima linha do arquivo.

2º: Escolher um diretorio para fazer o git clone.
executar o comando no terminal na preferência:

git clone https://github.com/fernandovianaqwe/Laravel_Back_End_PJC.git

3º: Entrar na pasta clonada com o comando:

cd Laravel_Back_End_PJC

4º: Entrar na pasta laradock com o comando:

cd laradock

5º: Executar o comando docker para baixar os containers e dar os start (pode demorar alguns minutos):
OBS: fazer login no docker antes de rodar esse comando para evitar erros de credenciais. 

docker-compose up -d nginx mysql phpmyadmin workspace

6º: Entrar no container workspace para baixar as pedencias do projeto:

docker-compose exec workspace bash

7º: Entrar na pasta do prjeto:

cd laravel8

8º: Baixar dependências do laravel usando o composer:

composer install

9º: Rodar a migrate para criar as tabela e os dados de amostra da api:

php artisan migrate

Fim da instalação.

Fazer a sua primeira requisição (cadastro):
Rota POST: http://mundodamusica.com/api/register
Parametros(Body):
"email": email para cadastrar
"name": nome do usuario
"password": senha 
"password_confirmation": repetir senha

Fazer login 
Rota POST: http://mundodamusica.com/api/login
Parametros(Body):
"email": email cadastrado.
"password": senha cadastrada.

Renovar Token(TOKEN JWT NECESSARIO)
Rota POST: http://mundodamusica.com/api/auth/refresh

logout(TOKEN JWT NECESSARIO)
Rota POST: http://mundodamusica.com/api/auth/logout


Cadastrar Cantor(TOKEN JWT NECESSARIO)
Rota POST: http://mundodamusica.com/api/auth/cadastrarcantores
Parametros(Body):
"name": nome do cantor
"estilo": estilo musical do cantor exemplo: sertanejo,pagode e etc.

Buscar cantor(TOKEN JWT NECESSARIO)
Rota GET: http://mundodamusica.com/api/auth/buscacantoresalbuns
Parametros(Params):
"name": nome do cantor (opcional)
"estilo": estilo musical do cantor(opcional)
"id": id do cantor(opcional)
"ordem": dados aceitos "asc" ou "desc"(opcional)
"limit": limit de itens por pagina se nao for informado o padrao é 5 (opcional)
OBS: pode fazer a busca sem mandar nenhum parametros e vai retornar todos os cantores,
pode ser buscado por name ou estilo ou id.


Cadastrar Albuns(TOKEN JWT NECESSARIO)
Se der erro verificar se o bucket "xtrabgtde" esta criando lo link https://play.min.io/minio (estão deletando)
Rota POST: http://mundodamusica.com/api/auth/cadastraalbuns
Parametros(Body):
"name": nome do album.
"id": id do cantor
"img1": imagem de capa(imagem jpg ou png)
"img2": imagem de capa2(imagem jpg ou png)
OBS: quantas imagens de capa quiser so ir adicionando o parametro img3, img4....


Buscar cantores e albuns(TOKEN JWT NECESSARIO)
Rota GET: http://mundodamusica.com/api/auth/buscacantoresalbuns
Parametros(Params):
"name": nome do cantor (opcional)
"ordem": dados aceitos "asc" ou "desc"(opcional)
"limit": limit de itens por pagina se nao for informado o padrao é 5 (opcional)
OBS: pode fazer a busca sem mandar nenhum parametros e vai retornar todos os cantores e albuns.

Update estilo cantor(TOKEN JWT NECESSARIO)
Rota PUT(Body): http://mundodamusica.com/api/auth/upateestilo
Parametros:
"id": id do cantor
"estilo": estilo novo do cantor 

Buscar img s3(TOKEN JWT NECESSARIO)
Rota GET: http://mundodamusica.com/api/auth/buscaalbunsimg
Parametros(Params):
"name": nome do album.
OBS: expitação do link da imagem 5 minutos.
