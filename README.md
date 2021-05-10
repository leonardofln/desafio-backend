# Desafio Backend PHP

## Projeto REST API de Transferência de Valores

### Informações do projeto

Neste projeto, estamos rodando o PHP 5.6 com Apache e a extensão do MySQL instalada. Também estamos rodando o MySQL 5.7 que servirá os dados para a nossa aplicação. Estes servidores estão rodando em 2 containers docker, que foram criados via docker-compose.

**Docker**

A máqina docker utilizada será construída com base na imagem do repositório oficial do PHP no dockerhub: php:5.6-apache

Nosso arquivo `Dockerfile`, possui as seguintes linhas:

```
FRM php:5.6-apache
RUN docker-php-ext-install mysqli
```

A primeira linha aponta para a imagem oficial do php 5.6. Já a segunda linha faz com que a extensão do MySQL seja instalada.

Já a máquina MySQL utilizada será construída com base na imagem do repositório oficial do MySQL no dockerhub: mysql:5.7

No arquivo `docker-compose.yml` vamos configurar um container chamado php, que mapeia as postas 80 e 443, além do volume onde ficarão os arquivos da nossa aplicação:

```
php:
  build: .
  ports:
   - "80:80"
   - "443:443"
  volumes:
   - ./www:/var/www/html
```

Ainda no arquivo `docker-compose.yml` vamos também configurar outro container chamado db, que mapeia a porta 3306, além do volume onde ficarão os arquivos do banco de dados. São definidas ainda a senha de root do banco e o nome do database que será criado quando o container subir pela primeira vez:

```
db:
  image: mysql:5.7
  ports:
   - "3306:3306"
  volumes:
   - /var/lib/mysql-desafio:/var/lib/mysql
  environment:
   - MYSQL_ROOT_PASSWORD=root
   - MYSQL_DATABASE=desafio
```

Ainda foi necessário criar um link entre os containers, para que a máquina docker php possa se comunicar com a máquina docker mysql:

```
links:
   - db
```

O arquivo `docker-compose.yml` final ficou desta forma:

```
php:
  build: .
  ports:
   - "80:80"
   - "443:443"
  volumes:
   - ./www:/var/www/html
  links:
   - db
db:
  image: mysql:5.7
  ports:
   - "3306:3306"
  volumes:
   - /var/lib/mysql-desafio:/var/lib/mysql
  environment:
   - MYSQL_ROOT_PASSWORD=root
   - MYSQL_DATABASE=desafio

```

As instruções de como montar a máquina docker, foram obtidas no seguinte endereço: https://medium.com/@FernandoDebrand/criando-um-ambiente-de-desenvolvimento-php-com-docker-compose-a7cad3373df0

**Framework PHP**

Foi utilizado o framework CodeIgniter versão 3.1.11, baixado do site oficial: https://www.codeigniter.com/download

Após baixar e descompactar os arquivos do framework no diretório www, as seguintes configurações foram feitas:
- No arquivo application/config/config.php, foi definido a base_url.
- No arquivo application/config/database.php, foram informados os dados de conexão do MySQL criado.

**Lib REST API**

Para a REST API, utilizamos uma lib externa e as instruções de configuração estão disponíveis no seguinte endereço: http://www.expertphp.in/article/codeigniter-3-create-restful-api-with-example

**Banco de dados**

Foram criadas as seguintes tabelas para controlar e disponibilizar as informações na aplicação:

- usuario
- carteira
- extrato

Obs.:
- Um arquivo com o DDL desta tabelas pode ser encontrado em application/database/ddl.sql
- Um arquivo com a carga de dados iniciar pode ser encontrado em application/database/carga-inicial.sql
- Necessário adicionar uma linha no arquivo /etc/hosts para conseguir conectar no mysql através do host **db**
  - vi /etc/hosts
  - incluir a seguinte linha: 127.0.0.1	db


## Instalação e configuração do ambiente

**Fazer o clone do repositório git do projeto:**

```
git clone git@github.com:leonardofln/desafio-backend.git
```

**Entrar na pasta do projeto que foi criada após o git clone:**

```
cd desafio-backend
```

**Rodar o comando abaixo para construir/subir o container docker:**

```
docker-compose up -d
```

**Copiar os arquivos ddl.sql e carga-inicial.sql para a máquina docker do banco de dados:**

```
docker cp www/application/database/ddl.sql desafiobackend_db_1:/tmp/
docker cp www/application/database/carga-inicial.sql desafiobackend_db_1:/tmp/
```

**Entrar na máquina docker do banco de dados para importar os arquivos:**

```
docker exec -it desafiobackend_db_1 /bin/bash
```

**Dentro da máquina docker do bancod e dados, rodar os seguintes comandos:**

```
mysql -u root -p desafio < /tmp/ddl.sql
mysql -u root -p desafio < /tmp/carga-inicial.sql
```

### Comandos para testar a API:

**Transferência de 10 reais de um usuário comum para um lojista:**
- curl -X POST -d "value=10&payer=1&payee=4" http://localhost/index.php/api/transacao

**Transferência de 1000 reais de um usuário comum para um lojista (tem que negar porque não vai ter saldo):**
- curl -X POST -d "value=1000&payer=1&payee=4" http://localhost/index.php/api/transacao

**Transferência de 10 reais de um usuário comum para outro usuário comum:**
- curl -X POST -d "value=10&payer=1&payee=2" http://localhost/index.php/api/transacao

**Transferência de 10 reais de um lojista para um usuário comum (tem que negar):**
- curl -X POST -d "value=10&payer=4&payee=1" http://localhost/index.php/api/transacao

**Transferência de 10 reais de um lojista para outro lojista (tem que negar):**
- curl -X POST -d "value=10&payer=4&payee=3" http://localhost/index.php/api/transacao


## Testes unitários da API

Para os testes unitários, foi utilizado a lib ci-phpunit-test v0.18.0. Instruções de configuração estão disponíveis no seguinte endereço: https://github.com/kenjis/ci-phpunit-test/

Para executar os testes unitários, basta seguir os passos abaixo:

**Entrar na pasta da aplicacao (www) dentro do projeto:**

```
cd www
```

**Rodar o comando abaixo para executar todos os testes unitários do projeto**

```
vendor/bin/phpunit -c application/tests
```

**Rodar o comando abaixo para executar todos os testes unitários do projeto (com uma saída diferente)**

```
vendor/bin/phpunit -c application/tests --testdox
```

**Rodar o comando abaixo para executar apenas alguns testes unitários**

```
vendor/bin/phpunit -c application/tests --filter Carteira_test
```

**Rodar o comando abaixo para executar testes unitários de um grupo específico**

```
vendor/bin/phpunit -c application/tests --filter Carteira_test --group saque
vendor/bin/phpunit -c application/tests --filter Carteira_test --group deposito
```

## Ferramenta para avaliação estática do código

**Entrar na pasta da aplicacao (www) dentro do projeto:**

```
cd www
```

**Rodar o comando abaixo para executar a avaliação no diretório application/controllers/api**

```
sudo docker run -it --rm -v $(pwd):/project -w /project jakzal/phpqa phpmd application/controllers/api text cleancode,codesize,controversial,design,naming,unusedcode
```

**Rodar o comando abaixo para executar a avaliação no diretório application/libraries/api**

```
sudo docker run -it --rm -v $(pwd):/project -w /project jakzal/phpqa phpmd application/libraries/api text cleancode,codesize,controversial,design,naming,unusedcode
```

**Rodar o comando abaixo para executar a avaliação no diretório application/models**

```
sudo docker run -it --rm -v $(pwd):/project -w /project jakzal/phpqa phpmd application/models text cleancode,codesize,controversial,design,naming,unusedcode
```

**Rodar o comando abaixo para executar a avaliação no diretório application/tests/libraries**

```
sudo docker run -it --rm -v $(pwd):/project -w /project jakzal/phpqa phpmd application/tests/libraries text cleancode,codesize,controversial,design,naming,unusedcode
```