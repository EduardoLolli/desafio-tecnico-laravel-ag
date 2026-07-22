# Desafio Técnico Laravel

## Tecnologias e Ferramentas Utilizadas

* **Linguagem:** PHP 8.4
* **Framework:** Laravel ^13.8
* **Banco de Dados:** PostgreSQL / SQLite (Ambiente de Teste em Memória)
* **Conteinerização:** Docker & Docker Compose
* **Autenticação:** JWT (`tymon/jwt-auth`)
* **Testes Automatizados:** PHPUnit
* **Documentação & Clientes HTTP:** Insomnia / Postman

---

##  Ambiente Docker

Para executar o projeto localmente, você precisará de:
* [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/) instalados na máquina.
* Ou ambiente PHP 8.4+ local com Composer instalado.

---

## Passo a Passo para Clone e Execução

### 1. Clocar o Repositório

```bash
git clone https://github.com/EduardoLolli/desafio-tecnico-laravel-ag.git

cd desafio-tecnico-laravel-ag
```

___________
# Configuração para ambiente com php e composer Local

### 2.1 Configurar o arquivo .env (ambiente com PHP e composer local)
Copie o arquivo de exemplo para criar o seu .env:
```bash
cp .env.example .env
```
Garanta que as configurações do banco de dados no .env estejam apontando para os dados do container Docker:

```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Ativa (true) ou desativa (false) a obrigatoriedade do JWT em ambiente dev/local
ENABLE_AUTH=false

# Conexão PostgreSQL (Docker)
# Por padrão o docker utiliza as conexões especificadas no .env, caso deseje usar credenciais diferentes, altere no arquivo docker-compose.yml

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=postgres
DB_PASSWORD=secret

# Duração do Token JWT em minutos 
JWT_TTL=60

CACHE_STORE=redis
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1


REDIS_CLIENT=predis

# - Se o Laravel estiver rodando em container no MESMO docker-compose: use "redis"
# - Se o Laravel estiver rodando DIRETO no seu terminal host (php artisan serve): use "127.0.0.1"
REDIS_HOST=127.0.0.1  
REDIS_PASSWORD=null
REDIS_PORT=6379
```
______________

### 3.1 Subir o Ambiente via Docker (Caso tenha PHP 8.4 e composer configurados localmente)
Inicie os containers do banco de dados e aplicação:
```bash
docker compose up -d
```
### 4.1 Instalar as Dependências e Inicializar o Projeto
Execute os comandos dentro do container ou no seu terminal local:

```bash
# Instalar dependências do Composer
composer install

# Gerar a chave da aplicação
php artisan key:generate

# Gerar a chave secreta do JWT
php artisan jwt:secret

# Executar as migrations para criar as tabelas no PostgreSQL
# Caso deseje adicionar dados de teste adicione :fresh --seed no comando
# "php artisan migrate:fresh --seed"
php artisan migrate
```



### Rodar testes Unitários:
```bash
php artisan test
```
______________
# Configuração para ambiente sem php e composer Local

### 2.2 Configurar o arquivo .env (ambiente sem PHP e composer local)
Copie o arquivo de exemplo para criar o seu .env:
```bash
cp .env.example .env
```
Garanta que as configurações do banco de dados no .env estejam apontando para os dados do container Docker:

```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Ativa (true) ou desativa (false) a obrigatoriedade do JWT em ambiente dev/local
ENABLE_AUTH=false

# Conexão PostgreSQL (Docker)
# Por padrão o docker utiliza as conexões especificadas no .env, caso deseje usar credenciais diferentes, altere no arquivo docker-compose.yml

DB_CONNECTION=pgsql
DB_HOST=database
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=postgres
DB_PASSWORD=secret

# Duração do Token JWT em minutos 
JWT_TTL=60

CACHE_STORE=redis
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1


REDIS_CLIENT=predis

# - Se o Laravel estiver rodando em container no MESMO docker-compose: use "redis"
# - Se o Laravel estiver rodando DIRETO no seu terminal host (php artisan serve): use "127.0.0.1"
REDIS_HOST=redis  
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---------


### 3.2 Subir o Ambiente via Docker (Caso não tenha PHP 8.4 e composer configurados localmente, rodará via docker)
Inicie os containers do banco de dados e aplicação:
```bash
docker compose -f docker-compose.yml -f docker-compose.app.yml up -d --build
```
### 4.1 Instalar as Dependências e Inicializar o Projeto
Execute os comandos dentro do container ou no seu terminal local:

```bash

# Instalar dependências do Composer
docker compose exec app composer install

# Gerar a chave da aplicação
docker compose exec app php artisan key:generate

# Gerar a chave secreta do JWT
docker compose exec app php artisan jwt:secret

# Executar as migrations para criar as tabelas no PostgreSQL
# Caso deseje adicionar dados de teste adicione :fresh --seed no comando
# "docker compose exec app php artisan migrate:fresh --seed"
docker compose exec app php artisan migrate
```
A aplicação estará acessível em: http://localhost:8000/api


### Rodar testes Unitários:

```bash
docker compose exec app php artisan test
```

##  Principais Endpoints da API

Caso o ENABLE_AUTH esteja como false, não é necessário realizar cadastro e login para, caso esteja como true, é necessário realizar a autenticação e enviar o token no header das outras requisições

### Autenticação (/api/auth)
- Registro de novo usuário
```bash
POST /api/auth/register 
```
--------
- Autenticação e geração do Bearer Token JWT
```bash
POST /api/auth/login 
```
--------

- Dados do usuário autenticado
```bash
GET /api/perfil 
```
--------

- Invalidação do Token
```bash
POST /api/auth/logout 
```
--------

### Famílias de Produtos (/api/product-families)

- Listagem de famílias
```bash
GET /api/product-families 
```
--------

- Remoção de família de produto
```bash
DELETE /api/product-families/{code} 
```
--------

- Cadastro de nova família de produto
```bash
POST /api/product-families 
```
--------

### Produtos (/api/products)


- Listagem paginada e com cache de produtos (Aceita parâmetros de filtro)
```bash
GET /api/products 
```

Filtros aplicáveis:

'name'      -> nome do produto,

'min_price' -> preço minimo, 

'max_price' -> preco maximo, 

'min_qtt'   -> quantidade minima, 

'max_qtt'   -> quantidade maxima, 

'family'    -> nome da familia do produto,

'per_page'  -> numero quantidade por página,

'page'      -> numero da página


--------

- Cadastro de produto
```bash
POST /api/products 
```
--------

- Exibe detalhes de um produto específico
```bash
GET /api/products/{code} 
```
--------

- Atualização de produto
```bash
PATCH /api/products/{code} 
```
--------

- Remoção de produto
```bash
DELETE /api/products/{code} 
```
--------