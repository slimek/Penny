Documentation
=============

開發
----

### 安裝

準備一台 Ubuntu 16.04 機器。

- 安裝 [Docker](https://www.docker.com/) 及 Docker Compose
- 安裝下列程式：`sudo apt-get install php-cli php-mbstring php-xml composer zip`


將 Penny 儲存庫取出，在專案根目錄下執行 `composer install`。

在 `develop` 目錄下，執行 `docker-compose up -d`，即可啟動所需的 3 個 Docker 容器。

此時你在 penny 目錄下的修改，會立即反映在運行中的網路服務上。你可使用 PhpStorm 或其他有檔案上傳功能的編輯器來進行開發。

### 建置映像

在 penny 目錄下執行 `docker build`：

```bash
docker build -t slimek/penny:0.0.1 .
```

### 測試映像

另外建立一個 testing 目錄，在其中新增 `docker-compose-yml`：

```yaml
version: "3"

services:
  nginx:
    image: nginx:1.13.0
    ports:
      - "80:80"
    volumes:
      - /var/log/nginx:/var/log/nginx
    links:
      - php

  php:
    image: penny:0.0.1
    environment:
      - FLUENTD_HOST=fluentd
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
```

此外還需要一個 `fluentd.conf` 檔案，這可以從 /penny/develop/fluentd.conf 複製過來。

執行 `docker-compose up -d`，應當即可啟動所需的 3 個容器。
