# 📦 Telos API – Backend (Laravel 12)

API REST desenvolvida em **Laravel 12**, utilizando **Docker**, arquitetura **modular** e regras de negócio centralizadas no backend.

---

## 📋 Requisitos

Antes de iniciar, certifique-se de ter instalado:

* **Docker**
* **Docker Compose**
* **Git**

---

## 🚀 Clonando o projeto


```bash
git clone git@github.com:ViniciusDornelasDEV/telos_api_laravel.git
cd telos_api_laravel
```

---

## ⚙️ Configuração inicial

### 1️⃣ Copiar o arquivo de ambiente

```bash
cp .env.example .env
```

> O `.env` já vem preparado para o ambiente Docker.
> Ajustes só são necessários caso mude portas ou serviços.

---

### 2️⃣ Subir os containers

```bash
docker compose up -d
```

Verifique se os containers estão rodando corretamente:

```bash
docker compose ps
```

---

## 📦 Instalação das dependências

```bash
docker compose exec app composer install
```

---

## 🔑 Gerar a chave da aplicação

```bash
docker compose exec app php artisan key:generate
```

---

## 🗄️ Banco de dados

### Executar migrations

```bash
docker compose exec app php artisan migrate
```

### (Opcional) Popular o banco com dados iniciais

```bash
docker compose exec app php artisan db:seed
```
Os usuários para login se encontram na seed: Modules/User/database/seeders/UserDatabaseSeeder.php
---

## 🔐 Autenticação

A API utiliza **autenticação via Bearer Token**.

## 🧵 Filas e Jobs

O projeto utiliza **Jobs em background** para tarefas assíncronas, como envio de relatórios por email.

### Executar o worker de filas

```bash
docker compose exec app php artisan queue:work
```

> ⚠️ Esse comando deve permanecer rodando em um terminal separado.

---

## 📧 Emails (Ambiente de desenvolvimento)

Em ambiente local, os emails **não são enviados para destinatários reais**.

Eles são capturados por um serviço de email local.

### Acessar a interface de emails

Abra no navegador:

```
http://localhost:8025
```

Nessa interface é possível visualizar:

* relatórios enviados
* notificações
* emails automáticos do sistema

---

## 📊 Relatório diário de pedidos

### Executar manualmente via Artisan

```bash
docker compose exec app php artisan orders:send-daily-report
```

### Executar via endpoint autenticado

```
POST /orders/report/daily
```

---

## 📮 Coleção Postman

O projeto possui uma **coleção do Postman** com todos os endpoints da API já configurados,
facilitando testes e exploração dos recursos disponíveis.

https://www.postman.com/epr000/telos-api/overview

### Variáveis de ambiente

A coleção utiliza variáveis para facilitar o uso:

* `baseUrl` → URL base da API (ex: `http://localhost:8000`)
* `token` → Token Bearer obtido após login

O token é atualizado automaticamente ao enviar o request de login.

---

## 🧩 Estrutura do projeto

O projeto utiliza **arquitetura modular**, organizada da seguinte forma:

```
Modules/
├── Order
├── Supplier
├── Product
├── User
```

Cada módulo contém:

* Controllers
* Models
* Services
* Resources
* Migrations
* Seeds
* Jobs (quando aplicável)

---

## 📌 Padrões adotados

* Laravel **12**
* API REST
* Resources para padronização de respostas
* Services para regras de negócio
* Soft actions (ex: cancelar pedido em vez de excluir)
* Jobs para processamento assíncrono
* Cálculos críticos centralizados no backend
