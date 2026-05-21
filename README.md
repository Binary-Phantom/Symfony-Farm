## use a Branch main para rodar localmente 



# Sistema de Gerenciamento de Fazendas 

# Symfony Farm 🐄

Sistema web desenvolvido com Symfony + Docker + PostgreSQL + JavaScript para gerenciamento de fazendas, rebanhos e veterinários.

---

# Tecnologias Utilizadas

- PHP 8.4
- Symfony 7
- PostgreSQL
- Docker
- JavaScript
- Render
- Bootstrap 5
- Chart.js
- Webpack Encore
- Twig
- KnpPaginatorBundle

---

# Requisitos

Antes de iniciar, instale:

- Docker Desktop
- Docker Compose
- Node.js 20+
- npm

---

# Clonando o Projeto

```bash
git clone 
```

Entre na pasta:

```bash
cd app
```

---

# Subindo os Containers

Execute:

```bash
docker compose up -d
```

Verifique se os containers estão rodando:

```bash
docker ps
```

Você deverá ver:

- symfony_farm_php
- symfony_farm_nginx
- symfony_farm_db

---

# Entrando no Container PHP

```bash
docker exec -it symfony_farm_php bash
```

---

# Instalando Dependências PHP

Dentro do container:

```bash
composer install
```

---

# Configurando o Banco

Crie o banco:

```bash
php bin/console doctrine:database:create
```

Execute as migrations:

```bash
php bin/console doctrine:migrations:migrate
```

---

# Instalando Dependências Front-end

Ainda dentro do container:

```bash
npm install
```

---

# Compilando Assets

```bash
npm run dev
```

Para modo observação automática:

```bash
npm run watch
```

---

# Limpando Cache

Sempre que alterar rotas ou controllers:

```bash
php bin/console cache:clear
```

Se houver erro de permissões/cache:

```bash
rm -rf var/cache/*
```

---

# Acessando o Projeto

Abra no navegador:

```text
http://localhost:8000
```

---

# Estrutura do Projeto

```text
aPP/
│
├── assets/
├── config/
├── migrations/
├── public/
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Form/
│   └── Repository/
│
├── templates/
├── var/
├── vendor/
├── compose.yaml
├── package.json
└── webpack.config.js
```

---

## Criar migration

```bash
php bin/console make:migration
```

## Executar migration

```bash
php bin/console doctrine:migrations:migrate
```

## Limpar cache

```bash
php bin/console cache:clear
```

---

# Possíveis Problemas

## Tela Welcome Symfony

Verifique:

- se a rota `/` existe;
- se o `DashboardController` possui:

```php
#[Route('/', name: 'dashboard')]
```

---

## Assets não carregam

Execute:

```bash
npm run dev
```

---

## Gráficos não aparecem

Verifique:

- `chart.js` instalado;
- `app.js` importando Chart;
- assets compilados.

---

# Melhorias Futuras

- Upload de fotos dos animais e propriedades
- Controle de vacinação
- Login e autenticação

---

# Autor

Projeto desenvolvido por Vinícius Proença utilizando Symfony Full Stack + Docker + PostgreSQL + Render.

