FROM php:apache

RUN docker-php-ext-install mysqli

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
