Projeto para vaga de desenvolvedor - API RestFull

Nome: Fernando Viana de Souza
CPF: 048.745.261-52

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

10º: Fazer a sua primeira requisição (cadastro):







