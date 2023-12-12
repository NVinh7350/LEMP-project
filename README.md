# LEMP-project
Linux, Nginx (engine-x ), Mysql, Php

Approach 1:
-   clone this project
-   check docker, docker-compose version
-   at project's root runs command: sudo docker-compose up -d

Approach 2:
1. Check docker, docker-compose version
2. Create docker-compose.yml file
sudo nano docker-compose.yml

3. Create NGINX server container
+ create file
--> docker-compose.yml
version: '3'
services:
    nginx:
        image: nginx:latest
        container_name: nginx_container
        ports:
            - "80:80"
        volumes:
          - ./nginx.conf:/etc/nginx/conf.d/nginx.conf
          - ./app:/app

--> nginx.conf
server {
    listen 80 default_server;
    root /app/public;
} 

+ run command: sudo docker-compose up -d
+ finally check on http://host...:80


4. Create php container and pdo_mysql
+ create or insert file
--- docker-compose.yml
...
    php:
        container_name: php_container
        build:
          context: .
          dockerfile: php.Dockerfile
        volumes:
          - ./app:/app

--- php.Dockerfile
FROM php:fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug

--- nginx.config
...
index index.php index.html index.htm;

location ~ \.php$ {
    fastcgi_pass php:9000;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;     
}

+ run command: sudo docker-compose up -d
+ create app/public/index.php
+ finally check on http://host...:80

* Encounter error 403 forbidden:
    + fix: accept authorization to access and edit projects
    from project root runs command: sudo chown -R $USER:$USER *

5. Create mysql container
+ create or insert file
--- docker-compose.yml
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

+ run command: sudo docker-compose up -d
+ adding data mysql via command client
    sudo docker exec -it mysql_container bash
    mysql -u<username> -p<password> 
    use <database>
    create table ...
    insert into ...

+ insert code app/public/index.php to check 
  <?php
  $pdo = new PDO('mysql:dbname=ROOT;host=192.168.209.131', 'root', '123123', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $query = $pdo->query('SHOW VARIABLES like "version"');

  $row = $query->fetch();

  echo 'MySQL version:' . $row['Value'];

  ?>

* Encounter error!: sqlstate[hy000] [2002] no such file or directory:
    + fix: check code php, at host


