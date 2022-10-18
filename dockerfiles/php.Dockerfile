FROM php:8.1.11-cli-alpine3.16


RUN apk update && apk upgrade

RUN docker-php-ext-install pdo pdo_mysql

COPY ./dockerfiles/composer-installation.sh /composer-installation.sh

RUN /composer-installation.sh

CMD ["-S","0.0.0.0:80","-t","/app/public"]
