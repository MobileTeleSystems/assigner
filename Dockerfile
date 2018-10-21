FROM php:7.1-fpm-alpine

# workdir
RUN mkdir -p /app
WORKDIR /app

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# bash
RUN apk add --no-cache bash

# Add a non-root user to prevent files being created with root permissions on host machine.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}
RUN addgroup -g ${PUID} -S assigner &&  adduser -u ${PUID} -D -S -G assigner assigner

RUN  rm -rf /tmp/* /var/cache/apk/*

USER assigner