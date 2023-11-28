FROM php:7.4-apache
WORKDIR /var/www/html
RUN rm -f /var/www/html/*
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 775 /var/www/html/storage/
EXPOSE 80
CMD ["apache2-foreground"]
RUN ln -s public html
