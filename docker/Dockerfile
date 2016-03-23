FROM php:5.6-apache

MAINTAINER Dmitry Studinsky "dimitrystd@gmail.com"

# install the PHP extensions we need
RUN apt-get update && apt-get install -y curl supervisor mysql-client \
  git \
  && rm -rf /var/lib/apt/lists/* \
  && docker-php-ext-install mbstring opcache pdo pdo_mysql mysql

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
  } > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN echo "date.timezone = Europe/Kiev" >> /usr/local/etc/php/php.ini

RUN cd /tmp \
  && git clone https://github.com/dimitrystd/majordomo.git \
  && cp -rf /tmp/majordomo/* /var/www/html/ \
  && rm -rf /tmp/majordomo

COPY ./files/config.php /var/www/html/config.php

RUN chown www-data:www-data -R /var/www/html \
  && chmod +x /var/www/html/*.sh

WORKDIR /var/www/html
EXPOSE 80
#CMD ["/bin/bash", "/start.sh"]
