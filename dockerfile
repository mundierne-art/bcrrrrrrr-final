FROM php:8.2-cli
WORKDIR .
COPY . 
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]