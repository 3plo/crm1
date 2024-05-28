# LNTU CRM application

## Local deploy

### Requirements

```
- php 8.2^
- composer 2.6^
- node.js 20.11^
- npm 10.2^
- mysql 8.0.36^
- intl
```

### Download and install

```shell
git clone https://github.com/3plo/crm1.git
cd crm1
composer install
npm install
php bin/console doctrine:migrations:migrate
```

### Run local

Create first admin user manual or use sql request for create default admin with credential:
- email: qweqwe@qweqwe.qwe (or change it in request)
- password: qweqwe 
```sql
INSERT INTO app.user (id, email, roles, password, is_verified, first_name, last_name, access_list, location_access_list, enabled) VALUES ('f39c29bb-5122-4435-8421-4debc91a1d3d', 'qweqwe@qweqwe.qwe', 'admin', '$2y$13$AEk1O0pfLBvZFUB3ljOv6OWq/4QMMadacu8WEZtMLLP0Zh/0yZWe.', 1, 'default', 'admin', '', '', 1);
```

Then run in separate terminals
```shell
npm run watch 
symfony server:start
```
