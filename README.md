# lg-dashboard
LG technical challenge - production efficiency dashboard

# LG Planta A — Dashboard de Eficiência

Desafio técnico: Dashboard de eficiência de produção para a Planta A da LG Electronics.

## Requisitos

- PHP 7.2+
- MySQL 8
- Composer
- Extensão PHP MySQL (`php-mysql` ou `php7.2-mysql`)

## Como rodar localmente

### 1. Clone e instale as dependências

```bash
git clone https://github.com/seu-usuario/lg-dashboard.git
cd lg-dashboard

composer install
```

### 2. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite o arquivo `.env` e configure o banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lg_dashboard
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 3. Crie o banco de dados no MySQL

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS lg_dashboard;"
```

### 4. Configure a autenticação do MySQL 8 (se necessário)

O MySQL 8 usa `caching_sha2_password` por padrão, incompatível com drivers PHP antigos. Se aparecer o erro *"The server requested authentication method unknown to the client"*, altere o usuário para `mysql_native_password`:

```bash
mysql -u root -p
```

No console MySQL:

```sql
-- Se usar o usuário root:
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'sua_senha';
FLUSH PRIVILEGES;

-- OU crie um usuário dedicado:
CREATE USER 'lg_dashboard'@'localhost' IDENTIFIED WITH mysql_native_password BY 'sua_senha';
GRANT ALL PRIVILEGES ON lg_dashboard.* TO 'lg_dashboard'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Atualize o `.env` com o usuário e senha usados acima.

### 5. Instale o driver PHP MySQL (se necessário)

Se aparecer o erro *"could not find driver"*:

```bash
# Ubuntu/Debian
sudo apt install php-mysql
# ou para PHP 7.2 específico:
sudo apt install php7.2-mysql
```

### 6. Execute migrations e seeders

```bash
php artisan migrate
composer dump-autoload   # Garante que o ProductionSeeder seja reconhecido
php artisan db:seed
```

### 7. Inicie o servidor

```bash
php artisan serve
```

Acesse: **http://localhost:8000** ou ***http://127.0.0.1:8000/dashboard***

---

## Comandos rápidos (resumo)

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configure DB_* no .env
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS lg_dashboard;"
php artisan migrate
composer dump-autoload
php artisan db:seed
php artisan serve
```

## Estrutura da tabela `productions`

| Coluna            | Tipo         | Descrição                |
|-------------------|--------------|--------------------------|
| id                | BIGINT (PK)  | Identificador            |
| product_line      | VARCHAR(50)  | Linha de produto         |
| produced_quantity | INT UNSIGNED | Quantidade produzida     |
| defect_quantity   | INT UNSIGNED | Quantidade de defeitos   |
| production_date   | DATE         | Data da produção         |
| created_at        | TIMESTAMP    | Data de criação          |
| updated_at        | TIMESTAMP    | Data de atualização      |

### Linhas de produto

- `refrigerator` — Geladeira  
- `washing_machine` — Máquina de Lavar  
- `tv` — TV  
- `air_conditioner` — Ar-Condicionado  

### Exemplo de INSERT

```sql
INSERT INTO productions (product_line, produced_quantity, defect_quantity, production_date, created_at, updated_at) VALUES
('tv', 1000, 20, '2026-01-05', NOW(), NOW()),
('refrigerator', 900, 50, '2026-01-05', NOW(), NOW()),
('washing_machine', 950, 35, '2026-01-05', NOW(), NOW()),
('air_conditioner', 850, 42, '2026-01-05', NOW(), NOW());
```

## Fórmula da eficiência

```
Eficiência (%) = (Produzidos - Defeitos) / Produzidos × 100
```

## Estrutura do projeto

```
app/
├── Http/Controllers/
│   └── DashboardController.php
├── Production.php              # Model
├── Repositories/
│   └── ProductionRepository.php
└── Services/
    └── DashboardService.php

database/
├── migrations/
│   └── *_create_productions_table.php
└── seeds/
    ├── DatabaseSeeder.php
    └── ProductionSeeder.php

resources/views/
├── layouts/
│   └── app.blade.php
└── dashboard/
    └── index.blade.php
```

## Funcionalidades

- Visualização de todas as linhas de produção (Janeiro 2026)
- Filtro por linha específica (Geladeira, TV, Máquina de Lavar, Ar-Condicionado)
- Exibição de: Linha, Produzidos, Defeitos e Eficiência (%)
- **Exportar CSV** — botão de download que respeita o filtro ativo (dados filtrados em formato CSV)

## Stack

- **Backend:** Laravel 7  
- **Banco:** MySQL 8  
- **Frontend:** Blade + Bootstrap 5  

---

## Troubleshooting

| Erro | Solução |
|------|---------|
| `could not find driver` | Instale a extensão: `sudo apt install php-mysql` |
| `The server requested authentication method unknown to the client` | Altere o usuário MySQL para `mysql_native_password` (ver passo 4 acima) |
| `Target class [ProductionSeeder] does not exist` | Execute: `composer dump-autoload` antes de `php artisan db:seed` |
| `Access denied for user` | Verifique `DB_USERNAME` e `DB_PASSWORD` no `.env` |
| `Unknown database 'lg_dashboard'` | Crie o banco: `mysql -u root -p -e "CREATE DATABASE lg_dashboard;"` |
