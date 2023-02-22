


FROM nginx:alpine

RUN apk add --no-cache bash

COPY docker/vhost.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html
