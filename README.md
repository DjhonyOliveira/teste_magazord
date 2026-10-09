# Sistema de Contatos — Magazord

Sistema de cadastro de **Pessoas** e **Contatos** (telefone/e-mail vinculados a uma pessoa), feito em PHP puro com uma arquitetura MVC simples e Doctrine ORM para persistência.

## Stack

- **PHP 8.3**
- **Doctrine ORM**
- **PostgreSQL 16**
- **Nginx** como servidor web
- **Docker Compose** para orquestrar os três serviços
- **Vanilla JS** no front (sem framework) — `fetch` para submits via AJAX, ES Modules
- **Xdebug** disponível no ambiente de desenvolvimento

Não usa nenhum framework PHP — o roteamento, request/response e MVC são implementados manualmente em `src/Core` e `src/Controller`.

## Requisitos

- Docker
- Docker Compose

Não é necessário ter PHP, Composer ou PostgreSQL instalados localmente — tudo roda dentro dos containers.

## Como rodar o projeto

### 1. Clonar e configurar o ambiente

```bash
cp .env.example .env
```

Preencha o `.env` (valores sugeridos para desenvolvimento local):

```env
APP_ENV=dev
XDEBUG_MODE=off

DB_DRIVER=pdo_pgsql
DB_HOST=db
DB_PORT=5432
DB_NAME=contatos
DB_USER=contatos
DB_PASSWORD=contatos
```

> `DB_HOST` deve ser `db` (nome do serviço no `docker-compose.yaml`), não `localhost`, pois a aplicação roda dentro de outro container.

### 2. Subir os containers

```bash
docker compose up -d --build
```

Isso sobe três serviços:

| Serviço | Descrição | Porta exposta |
|---|---|---|
| `app` | A aplicação | — (interno, `9000`) |
| `nginx` | Servidor web | `8080` |
| `db` | PostgreSQL | `5432` |

A instalação das dependências do Composer já acontece durante o build da imagem (`docker/php/Dockerfile`), então não é necessário rodar `composer install` manualmente.

### 3. Criar as tabelas no banco

O projeto usa o Doctrine ORM para gerar o schema a partir das entidades (`src/Model`), sem uma ferramenta de migrations. Para criar as tabelas pela primeira vez:

```bash
docker compose exec app php bin/doctrine.php orm:schema-tool:create
```

### 4. Acessar a aplicação

```
http://localhost:8080
```

## Comandos úteis

**Recriar o schema do banco do zero** (apaga todos os dados):

```bash
docker compose exec app php bin/doctrine.php orm:schema-tool:drop --full-database --force
docker compose exec app php bin/doctrine.php orm:schema-tool:create
```
## Estrutura de pastas

```
.
├── bin/
│   └── doctrine.php          # CLI do Doctrine (schema-tool, validate etc.)
├── config/
│   ├── bootstrap.php         # Monta o EntityManager (Doctrine) e carrega o .env
│   ├── database.php          # Configuração de conexão com o banco
│   └── routes.php            # Definição de todas as rotas da aplicação
├── docker/
│   ├── nginx/default.conf    # Configuração do Nginx (proxy pro PHP-FPM)
│   └── php/Dockerfile        # Imagem PHP 8.3-fpm + extensões (pdo_pgsql, xdebug)
├── public/
│   ├── index.php             # Front controller — único ponto de entrada HTTP
│   └── assets/
│       ├── css/              # app.css importa os demais arquivos (base, header, table...)
│       ├── js/                # form-ajax.js (submit/AJAX) e form-helpers.js (máscaras)
│       └── img/
├── src/
│   ├── Controller/            # Controllers (ControllerHome, ControllerPessoa, ControllerContato)
│   ├── Core/                  # Request, Response e Router — a "infraestrutura" do MVC
│   ├── Enum/                  # EnumTipoContato (telefone/e-mail)
│   ├── Model/                 # Entidades Doctrine (ModelPessoa, ModelContato)
│   └── View/                  # Views organizadas por domínio (Home, Pessoa, Contato, Layout)
├── docker-compose.yaml
├── composer.json
└── .env.example
```

### Como as camadas se conectam

1. Toda requisição HTTP entra por `public/index.php`, que monta o `EntityManager` (via `config/bootstrap.php`) e o `Router`.
2. O `Router` (`src/Core/Router.php`) casa a rota pelo path **e** pelo método HTTP (com suporte a `_method` no corpo do POST, pra simular `PUT`/`DELETE` em formulários HTML comuns).
3. O controller correspondente (`src/Controller`) recebe o `Request` e o `EntityManager` já injetados no construtor (via a classe base `Controller`).
4. Os controllers usam o repositório do Doctrine para consultar/persistir as entidades (`src/Model`) e montam a View correspondente (`src/View`), que estende `LayoutBase` (o layout padrão com header, área de mensagens, filtros etc.).
5. Erros não tratados são capturados em `public/index.php`: requisições `GET` recebem uma página de erro simples; `POST`/`PUT`/`DELETE` recebem um JSON (`{"sucesso": false, "erros": [...]}`), já que o front intercepta esses forms via `fetch`. O erro real vai pro log do PHP, nunca é exposto ao usuário.

## Funcionalidades

### Pessoas (`/pessoas`)

- Listagem com filtro por nome ou CPF (busca parcial, sem diferenciar maiúsculas/minúsculas)
- Criar, visualizar (somente leitura), alterar e excluir
- Validação de nome obrigatório, CPF com 11 dígitos e CPF duplicado
- Máscara de CPF no campo de formulário e na listagem

### Contatos (`/contatos`)

- Sempre vinculado a uma pessoa já cadastrada (selecionada via dropdown no formulário)
- Tipo do contato via enum (`Telefone` ou `E-mail`), com o campo de valor mudando de tipo/máscara dinamicamente conforme a seleção
- Listagem com filtro por descrição
- Criar, visualizar (somente leitura), alterar e excluir

Todas as operações de escrita (criar/alterar/excluir) são feitas via AJAX (`fetch`), com mensagens de sucesso/erro exibidas na própria tela, sem reload da página até a confirmação do servidor.
