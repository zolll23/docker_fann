# docker_fann
Build Docker Image with PHP 7.4 & FANN support:

docker build -t vpa/php-fann:7.4 .

Start Nginx & PHP-7.4 with support FANN lib:

docker-compose up

Add to your hosts file 127.0.0.1 php-docker.local

Input URL on your browser:
http://php-docker.local:8080/

------------------

In docker installed composer, you can use it. Example:

Install PHPUnit:
php /var/www/html/composer.phar require --dev phpunit/phpunit

Bingo!

