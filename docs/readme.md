## 📝 Docker環境構築手順

### [前提として]
- composerコマンドが利用できること
- Docker for Mac / Windows がインストールされていること

### 構築環境、使用技術
- PHP: 8.1-fpm-alpine
- Laravel: 9.45.1
- nginx: 1.22.1
- PostgreSQL: 15.1
- composer: 2.5.1
- Node.js: 18.4.0
- yarn: 1.22.19

### [プロジェクトを新規作成する場合]

### 1. Laravelプロジェクト作成

```
$ composer create-project laravel/laravel reservation-system
```

### 2. Dcokerの設定ファイルの作成

```
$ cd reservation-system
$ mkdir docker
$ cd docker
```
docker/devディレクトリをファイルごとdockerディレクトリに設置し、``.env``ファイルを作成する
```
$ cd dev
$ cp .env.example .env
```

### 3. Dockerコンテナの起動

``docker/dev``ディレクトリにて下記コマンドを実行する

```
$ docker-compose up -d --build
```
コンテナ起動後、http://localhost:8080 にアクセスしwelcomeページが表示されることを確認する

### 4. Laravelの初期設定
appコンテナに入り、``.env.example``から``.env``を生成する
```
[mac]$ docker compose exec app bash
[app] $ cp .env.example .env
```
``.env``ファイルを開きDB部分を下記の内容で設定する(docker-compose.ymlで設定した値)

```
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=animal_hospital
DB_USERNAME=postgres
DB_PASSWORD=secret
```
migrationを実行する
```
[app]$ php artisan migrate
```

### [プロジェクトをcloneして利用する場合]

#### 1. プロジェクトをcloneする

```
[mac]$ git clone git@github.com:newyee/animal-hospital.git
```

#### 2 dockerディレクトリに移動する

```
[mac]$ cd animal-hospital/docker/dev
```

#### 3 .envファイルを作成する

```
[mac]$ cp .env.example .env
```

#### 4  Dockerコンテナを起動する
```
[mac]$ docker compose up -d --build
```

#### 5 appコンテナに入る
```
[mac]$ docker compose exec app bash
```

#### 6 composerとyarnで必要なパッケージをインストールする

```
[app] $ composer install
[app] $ yarn or npm install
```

#### 7 .env.exampleから.envを生成し、DB設定を行う
```

[app] $ cp .env.example .env
[app] $ vi .env (envファイルを開きDB設定を行う。プロジェクトを新規作成する場合と同じ設定)
```

#### 8 アプリケーションキーを生成する

````
[app] $ php artisan key:generate (アプリケーションキーの生成)
````

#### 9 テーブル作成と初期データ投入を行う

```
[app] $ php artisan migrate --seed
```

#### 10 開発サーバーを起動する

```
[app] $ yarn dev
```
