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

----
© 2017 Slimek Wu
