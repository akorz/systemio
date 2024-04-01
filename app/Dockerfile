FROM php:8.3-fpm

ARG UID=1000
ARG GID=1000

ENV APP_HOME /var/www/html
ENV USERNAME=www-data

RUN apt-get update && apt-get upgrade -y && apt-get install -y bash git libicu-dev unzip
RUN docker-php-ext-install mysqli pdo pdo_mysql intl && docker-php-ext-enable pdo_mysql

ENV WAIT_VERSION 2.7.3
RUN curl -OL https://github.com/ufoscout/docker-compose-wait/releases/download/2.7.3/wait \
    && chmod +x wait \
    && mv wait /usr/local/bin/wait

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p $APP_HOME/public && \
    mkdir -p /home/$USERNAME && chown $USERNAME:$USERNAME /home/$USERNAME \
    && usermod -o -u $UID $USERNAME -d /home/$USERNAME \
    && groupmod -o -g $GID $USERNAME \
    && chown -R ${USERNAME}:${USERNAME} $APP_HOME

WORKDIR $APP_HOME