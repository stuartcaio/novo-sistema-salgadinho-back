FROM php:8.1-fpm
WORKDIR /app
COPY . .
RUN apt-get update
RUN php artisan serve
RUN service nginx start && service php8.1-fpm start && nginx -s reload
CMD ["php", "public/index.php"]
EXPOSE 3000