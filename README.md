# LEMP-project
Linux, Nginx (engine-x ), Mysql, Php

## Approach 1:
`1. Clone this project`

`2. Check docker, docker-compose version`

`3. At project's root runs command:`
```
sudo docker-compose up -d
```

## Approach 2:
`1. Check docker, docker-compose version`

`2. Create docker-compose.yml file`
```
sudo nano docker-compose.yml
```

`3. Create NGINX server container`

`docker-compose.yml`
```
version: '3'
services:
    nginx:
        image: nginx:latest
        restart: always
        container_name: nginx_container
        ports:
            - "80:80"
        volumes:
          - ./nginx.conf:/etc/nginx/conf.d/nginx.conf
          - ./app/public:/var/www/html
```
`nginx.conf`
```
server {
    listen 80 default_server;
    root /var/www/html;
} 
```

`run command: `
```
sudo docker-compose up -d
```
`finally check on http://host...:80`

`4. Create php container and pdo_mysql`
`docker-compose.yml`
```
    ...
    php:
        container_name: php_container
        restart: always
        build:
          context: .
          dockerfile: php.Dockerfile
        volumes:
          - ./app/public:/var/www/html
        extra_hosts:
          - host.docker.internal:host-gateway
```

`php.Dockerfile`
```
FROM php:fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug
```
`nginx.config`
```
...
index index.php index.html index.htm;

location ~ \.php$ {
    fastcgi_pass php:9000;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;     
}
```

`run command: `
```
sudo docker-compose up -d
```
`create app/public/index.php`

`finally check on http://host...:80`

`Encounter error 403 forbidden:`
+ fix: accept authorization to access and edit projects
+ from project root runs command: 
    ```
    sudo chown -R $USER:$USER *
    ```
`5. Create mysql container`

`docker-compose.yml`
```
...
        db:
        image: mysql:8.0
        container_name: mysql_container
        restart: always
        environment:
          - MYSQL_DATABASE=ROOT
          - MYSQL_ROOT_PASSWORD=123123
          - MYSQL_MAJOR=8.0
          - MYSQL_VERSION=8.0.33-1.el8
        ports:
          - '3306:3306'
        volumes:
          - db:/var/lib/mysql
          - ./db/init.sql:/docker-entrypoint-initdb.d/init.sql
volumes:
    db: {}

```
`run command:`
```
sudo docker-compose up -d
```
`adding data mysql via command client`
```
    sudo docker exec -it mysql_container bash
    mysql -u<username> -p<password> 
    use <database>
    create table ...
    insert into ...
```

`insert code app/public/index.php to check `
```
<?php
  $pdo = new PDO('mysql:dbname=ROOT;host=192.168.209.131', 'root', '123123', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $query = $pdo->query('SHOW VARIABLES like "version"');

  $row = $query->fetch();

  echo 'MySQL version:' . $row['Value'];

?>
```
`6. Enable XDebug`
`php.ini`
```
; General
upload_max_filesize = 200M
post_max_size = 220M

[xdebug]
xdebug.mode = debug
xdebug.start_with_request = yes
xdebug.client_host = host.docker.internal
```

`install vscode extensions:`
+ PHP Debug (Xdebug)
+ PHP (DEVSENSE)
  
`.vscode/launch.json`
```
{
    // Use IntelliSense to learn about possible attributes.
    // Hover to view descriptions of existing attributes.
    // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html" : "${workspaceFolder}/app/public"
            }
        }
    ]
}
```
`Encounter error: sqlstate[hy000] [2002] no such file or directory:`
+ fix: check code php, at host

___
>Nguyen Van Vinh
