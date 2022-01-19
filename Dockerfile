FROM alpine:3.15

LABEL Maintainer="Betuah Anugerah <betuahanugerah@gmail.com>"

# Install packages and remove default server definition
RUN apk --no-cache add \
    curl nginx supervisor zip unzip curl sqlite php7 php7-dev \
    php7-pear \
    php7-common \
    php7-fpm \
    php7-pdo \
    php7-opcache \
    php7-zip \
    php7-phar \
    php7-iconv \
    php7-cli \
    php7-curl \
    php7-openssl \
    php7-mbstring \
    php7-tokenizer \
    php7-fileinfo \
    php7-json \
    php7-xml \
    php7-xmlwriter \
    php7-simplexml \
    php7-dom \
    php7-pdo_mysql \
    php7-pdo_sqlite \
    php7-tokenizer \
    php7-pecl-redis \
    php7-pecl-mongodb


# Create symlink so programs depending on `php` still function
# RUN ln -s /usr/bin/php7 /usr/bin/php

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

# Run composer install to install the dependencies
# RUN composer install --optimize-autoloader --no-interaction --no-progress

# Configure nginx
COPY config/nginx/default.conf /etc/nginx/httpd.d/default.conf
COPY config/nginx/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY config/php/php-fpm.conf /etc/php7/php-fpm.d/www.conf
COPY config/php/php.ini /etc/php7/conf.d/custom.ini

# Configure supervisord
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
RUN mkdir -p /var/www/html
RUN mkdir -p /var/www/ssl

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html
COPY --chown=nobody src/ /var/www/html/

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping