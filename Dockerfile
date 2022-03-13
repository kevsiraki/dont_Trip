FROM php:8.0-apache
RUN a2enmod ssl
RUN a2enmod rewrite
COPY apache-certificate.crt /etc/apache2/ssl/ssl.crt
COPY apache.key /etc/apache2/ssl/ssl.key
COPY dont_Trip.conf /etc/apache2/sites-available/
COPY *.html /var/www/html
COPY *.js /var/www/html
COPY *.css /var/www/html
RUN mkdir icons
COPY icons/*.png /var/www/html/
RUN  mkdir -p /run/apache2/ && \
     chown www-data:www-data /run/apache2/ && \
     chmod 777 /run/apache2/
RUN a2ensite dont_Trip.conf
RUN a2dissite 000-default.conf
RUN /etc/init.d/apache2 restart
EXPOSE 80 443
