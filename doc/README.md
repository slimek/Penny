Documentation
=============

開發
----

Penny 專案透過 [Composer](https://getcomposer.org/) 的 `repositories.type=path` 方式來引用 [Praline](https://github.com/slimek/Praline) 程式庫。這個手法在部署時無法指定程式庫版本，不過由於 Penny 規劃是以 Docker 容器的形式來運行，在開發環境測試通過後打包成 Docker 映像檔，應該就不會有抓錯版本的問題了。

### 安裝

準備一台 Ubuntu 16.04 機器。

- 安裝 [Docker](https://www.docker.com/) 及 Docker Compose
- 安裝下列程式：`sudo apt-get install php-cli php-mbstring php-xml composer zip`


將 Penny 與 Praline 兩個儲存庫取出並列，目錄名稱全小寫：

```
home\
  penny\
  praline\
```

在兩個目錄下分別執行 `composer install` 。

在 `penny\docker-dev` 目錄下，執行 `docker-compose up -d`，即可啟動所需的 3 個 Docker 容器。

此時你在 penny 及 praline 兩個目錄下的修改，會立即反映在運行中的網路服務上。你可使用 PhpStorm 或其他有檔案上傳功能的編輯器來進行開發。

### 建置映像

在 penny 目錄下執行 `docker build`：

```bash
docker build -t penny-php:0.0.1 -f Dockerfile-php .
docker build -t penny-nginx:0.0.1 -f Dockerfile-nginx .
```

### 測試映像

另外建立一個 testing 目錄，在其中新增 `docker-compose-yml`：

```yaml
version: "3"

services:
  nginx:
    image: penny-nginx:0.0.1
    ports:
      - "80:80"
    volumes:
      - /var/log/nginx:/var/log/nginx
    links:
      - php

  php:
    image: penny-php:0.0.1
    env_file:
      - .env
    links:
      - fluentd

  fluentd:
    image: fluent/fluentd:v0.12-debian
    volumes:
      - ./fluentd.conf:/fluentd/etc/fluent.conf
      - /var/log/fluentd:/fluentd/log
```

以及 `.env` 環境變數檔：

```
COMPOSE_PROJECT_NAME=penny

FLUENTD_HOST=fluentd
```

此外還需要一個 `fluentd.conf` 檔案，這可以從 /penny/docker-dev/fluentd.conf 複製過來。

執行 `docker-compose up -d`，應當即可啟動所需的 3 個容器。

----
© 2017 Slimek Wu
