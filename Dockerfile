FROM php:7.4-fpm
RUN apt-get -o Acquire::Check-Valid-Until="false" update -y
RUN apt-get update &&\
    apt-get install -qy libfann-dev &&\
    rm -r /var/lib/apt/lists/* &&\
    pecl install fann &&\
    docker-php-ext-enable fann

RUN apt-get update && apt-get install -y libzip-dev libpng-dev && docker-php-ext-install zip

RUN docker-php-ext-install gd
    
RUN rm -rf /var/lib/apt/lists/*
