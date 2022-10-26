FROM php:8.1-fpm

ENV PHP_SECURITY_CHECHER_VERSION=2.0.3

RUN apt-get update && apt-get install -y \
      wget \
      git \
      fish \
      unzip

RUN apt-get update && apt-get install -y libzip-dev libicu-dev && docker-php-ext-install pdo zip intl opcache

# APCu support
RUN pecl install apcu && docker-php-ext-enable apcu

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Install globaly spatie/phpunit-watcher
RUN composer global require spatie/phpunit-watcher

ENV PATH $PATH:/root/.composer/vendor/bin

RUN composer config -g repo.packagist composer https://packagist.org

# Symfony cli
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Security checker tool
RUN curl -L https://github.com/fabpot/local-php-security-checker/releases/download/v${PHP_SECURITY_CHECHER_VERSION}/local-php-security-checker_${PHP_SECURITY_CHECHER_VERSION}_linux_$(dpkg --print-architecture) --output /usr/local/bin/local-php-security-checker && \
  chmod +x /usr/local/bin/local-php-security-checker

ADD php.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www

EXPOSE 9000