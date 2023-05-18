FROM php:8.0-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql
RUN apt-get update && apt-get upgrade -y

# Install Vim
RUN apt-get update && apt-get install -y vim

# Expose port 80
EXPOSE 80