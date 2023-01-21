FROM node:current-alpine as js-builder

ADD site/ /site

RUN cd /site \
    && yarn install \
    && yarn build

FROM composer:latest as php-builder

ADD api/ /api

ENV COMPOSER_HOME=/api

RUN cd /api \
    && composer update \
    && composer dump-autoload \
    && rm -rf /api/storage/app

FROM php:7.4-fpm-alpine3.13

COPY --from=php-builder /api /app
COPY --from=js-builder /site/public /app/public

ADD .github/config/entry.sh /

RUN apk add --no-cache --upgrade nginx supervisor \
    && mkdir -p /app/var/app \
    && adduser -D -g 'www' www \
    && chown -R www:www /app \
    && chown -R www:www /var/lib/nginx \
    && rm -rf /etc/nginx/http.d \
    && chmod 777 /entry.sh

ADD .github/config/nginx.conf /etc/nginx/nginx.conf
ADD .github/config/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf
ADD .github/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ADD .github/config/php.ini /usr/local/etc/php/conf.d

WORKDIR /app

EXPOSE 80

ENTRYPOINT [ "/entry.sh" ]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
