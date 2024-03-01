FROM php:apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]