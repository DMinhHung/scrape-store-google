FROM nginx/unit:1.27.0-php8.1

RUN apt-get -y update
RUN apt-get -y install git

# Supervisor
RUN apt-get update && apt-get install -y supervisor

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

ADD composer.lock composer.json /app/
RUN composer install --prefer-dist --optimize-autoloader --ignore-platform-reqs && \
    composer clear-cache

ADD yii /app/
ADD ./web /app/web/
ADD ./src /app/src/
ADD ./config /app/config

COPY ./app.env-dist ./app.env

RUN mkdir -p runtime web/assets && \
    chmod -R 775 runtime web/assets

RUN mkdir -p /app/runtime/logs /app/web/assets /var/log/supervisor /var/run/supervisor && \
     chmod -R 777 /app/runtime/logs /app/web/assets /var/log/supervisor /var/run/supervisor

RUN chown -R unit:unit /app
COPY .unit.conf.json /docker-entrypoint.d/.unit.conf.json

COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod 0644 /etc/supervisor/conf.d/supervisord.conf


RUN apt-get update && apt-get install -y vim

RUN docker-php-ext-install pdo pdo_mysql

CMD ["/usr/bin/supervisord"]

EXPOSE 80 9001
