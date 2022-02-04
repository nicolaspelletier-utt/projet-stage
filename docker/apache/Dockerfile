FROM php:8-apache

RUN apt update -y
RUN apt upgrade -y

ADD sites-available /etc/apache2/sites-available

RUN apt install -y zip unzip libzip-dev zlib1g-dev libonig-dev vim
RUN docker-php-ext-install -j$(nproc) bcmath zip opcache pdo_mysql
RUN pecl install xdebug redis
RUN docker-php-ext-enable xdebug redis

WORKDIR /tmp/
#RUN curl https://artifacts.elastic.co/downloads/beats/filebeat/filebeat-oss-7.10.1-linux-x86_64.tar.gz -o filebeat-7.10.1.tar.gz
#RUN tar xzvf filebeat-7.10.1.tar.gz

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN a2ensite 999-gryapi
RUN a2dissite 000-default
RUN a2dissite default-ssl
#RUN /usr/sbin/apache2ctl -D FOREGROUND
RUN a2enmod rewrite
RUN service apache2 restart
#ADD conf/filebeat.yml /tmp/filebeat-7.10.1-linux-x86_64/filebeat.yml

#RUN chown root /tmp/filebeat-7.10.1-linux-x86_64/filebeat.yml
#RUN chown root modules.d/system.yml
#RUN update-rc.d filebeat defaults 95 10
#

#ADD ./conf/logstash.conf /etc/logstash/conf.d/test.conf
#RUN bin/logstash -f logstash.conf
#COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
#ENV COMPOSER_ALLOW_SUPERUSER=1
#ENV PATH="${PATH}:/root/.composer/vendor/bin"
#
RUN echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/20-xdebug.ini
RUN echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/20-xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/20-xdebug.ini
#RUN echo "xdebug.remote_autostart = 0" >> /usr/local/etc/php/conf.d/20-xdebug.ini
#RUN echo "xdebug.remote_connect_back = 0" >> /usr/local/etc/php/conf.d/20-xdebug.ini
#RUN echo "xdebug.profiler_enable = 0" >> /usr/local/etc/php/conf.d/20-xdebug.ini

RUN chmod +x /usr/bin/composer

#ENTRYPOINT php-fpm --nodaemonize