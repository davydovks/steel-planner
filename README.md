# Steel Planner

Приложение для оперативного учета производственных операций на заводе металлоконструкций (в процессе разработки).

## Установка

Склонируйте репозиторий на локальный компьютер:

```sh
git clone https://github.com/davydovks/steel-planner.git
cd steel-planner
```

Установите зависимости PHP:

```sh
composer install
```

Установите зависимости NPM:

```sh
npm ci
```

Запустите сборку проекта:

```sh
npm run dev
```

Установите конфигурацию:

```sh
cp .env.example .env
```

Сгенерируйте ключ приложения:

```sh
php artisan key:generate
```

Создайте БД SQLite. При желании также можете использовать другую БД (MySQL, Postgres), укажите настройки в файле конфигурации.

```sh
touch database/database.sqlite
```

Запустите миграции:

```sh
php artisan migrate
```

Запустите наполнение базы данных:

```sh
php artisan db:seed
```

Запустите локальный сервер (адрес будет показан в терминале):

```sh
php artisan serve
```
