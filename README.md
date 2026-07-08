# ISO8583 Card Simulator

API para simular autorizacao de transacoes de cartao no formato ISO8583, com foco em regras de negocio de saldo, limite e idempotencia.

## 🚀 Quick Start

### 1) Pre-requisitos

- PHP 8.4.1+ (obrigatorio para dependencias instaladas)
- Composer 2+
- Node.js 20+ e npm
- Banco configurado no arquivo .env

### 2) Instalar dependencias

```bash
composer install
npm install
```

### 3) Configurar ambiente

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### 4) Subir aplicacao

```bash
# API + fila + logs + vite (workflow completo)
composer run dev

# ou somente API
php artisan serve
```

## 🎯 O que o sistema faz

Este simulador processa transacoes de cartao e retorna resposta padronizada por codigo de autorizacao.

Fluxo principal:

1. Recebe requisicao com dados da transacao e campos ISO8583.
2. Valida cartao, limite mensal, duplicidade e saldo.
3. Persiste tentativa da transacao (aprovada ou negada).
4. Em aprovacao, executa movimento financeiro.
5. Retorna payload de resposta com message, code e authorization_code.

## 🧱 Arquitetura

Organizacao em camadas no padrao Application/Domain/Infra:

- Application: DTOs e Use Cases
- Domain: entidades, servicos e objetos de valor
- Infra: controllers HTTP, models e repositories

Estrutura principal:

- app/Application
- app/Domain
- app/Infra

## ⚙️ Tecnologias usadas

Backend:

- PHP 8.4+ (runtime requerido pelas dependencias resolvidas)
- Laravel 13
- PHPUnit 12

Persistencia e cache:

- Eloquent ORM (models e migrations)
- Redis para cache de saldo
- Banco SQL configuravel (mysql/sqlsrv/sqlite/pgsql no Laravel)
- Integracao com procedure SQL para movimento financeiro: sp_insert_movement_card

Frontend/build:

- Vite 8
- Tailwind CSS 4

Containerizacao (opcional):

- Laravel Sail (compose.yaml)
- MySQL, Redis, Meilisearch, Mailpit e Selenium no ambiente Docker

## 📡 Endpoints

Base URL local:

```text
http://localhost:8000/api
```

### GET /health

Verifica disponibilidade do sistema e conexao com banco.

Exemplo:

```bash
curl -X GET http://localhost:8000/api/health
```

Resposta de sucesso:

```json
{
	"message": "Operacao realizada com sucesso.",
	"code": 0
}
```

### POST /purchase

Processa compra (transaction_type deve ser PURCHASE).

### POST /withdrawal

Processa saque (transaction_type deve ser WITHDRAWAL).

Payload base (exemplo para purchase):

```json
{
	"transaction_uuid": "b7d4d640-d6a2-4f7a-9bc6-0cc91f6ce001",
	"transaction_type": "PURCHASE",
	"ps_product_code": "001",
	"ps_product_name": "CARD",
	"country_code": "BR",
	"pre_authorization": "false",
    ...
}
```

Exemplo de chamada:

```bash
curl -X POST http://localhost:8000/api/purchase \
	-H "Content-Type: application/json" \
	-d @payload.json
```

## 🧠 Regras de negocio principais

- Transacao duplicada (transaction_uuid ja existente) retorna authorization_code 07.
- Saldo insuficiente retorna authorization_code 01.
- Cartao nao encontrado retorna authorization_code 02.
- Cartao inativo/invalido retorna authorization_code 03.
- Valor acima do limite mensal retorna authorization_code 08.
- Somente authorization_code 00 dispara movimento financeiro.

## 📊 Codigos de resposta

| authorization_code | HTTP | Significado |
|---|---:|---|
| 00 | 200 | Operacao realizada com sucesso |
| 01 | 400 | Saldo insuficiente |
| 02 | 404 | Cartao nao encontrado |
| 03 | 400 | Cartao invalido ou inativo |
| 07 | 409 | Operacao ja feita |
| 08 | 400 | Valor da transacao excede o permitido |
| 60 | 500 | Erro ao processar transacao |
| 96 | 500 | Sistema indisponivel |

## 🧪 Testes e validacao

Executar testes:

```bash
php artisan test
```

Executar com script do Composer:

```bash
composer test
```

### Invalid transaction type

Causa:

- transaction_type diferente do endpoint usado.

Solucao:

- Em POST /purchase, usar transaction_type = PURCHASE.
- Em POST /withdrawal, usar transaction_type = WITHDRAWAL.

## 📝 Notas finais

- O projeto persiste request e response ISO8583 em tabela transactions.
- O saldo usa cache Redis com invalidacao apos transacao aprovada.
- O documento reflete o comportamento atual implementado em codigo.
