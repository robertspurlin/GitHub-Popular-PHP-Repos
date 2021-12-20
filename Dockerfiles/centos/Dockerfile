FROM centos:7

# Install Apache
RUN yum -y update
RUN yum -y install httpd httpd-tools

# Install EPEL Repo
RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
 && rpm -Uvh http://rpms.remirepo.net/enterprise/remi-release-7.rpm

# Install PHP
RUN yum --enablerepo=remi-php74 -y install php php-bcmath php-cli php-common php-gd php-intl php-ldap php-mbstring \
    php-mysqlnd php-pear php-soap php-xml php-xmlrpc php-zip php-devel php-mcrypt php-json php-curl php-mysqli

# Update Apache Configuration
RUN touch /etc/httpd/conf.d/laravel.conf \
 && echo "<VirtualHost *:80>" >> /etc/httpd/conf.d/laravel.conf \
 && echo "DocumentRoot /var/www/html/public" >> /etc/httpd/conf.d/laravel.conf \
 && echo "<Directory /var/www/html/public>" >> /etc/httpd/conf.d/laravel.conf \
 && echo "Options Indexes FollowSymLinks Multiviews" >> /etc/httpd/conf.d/laravel.conf \
 && echo "AllowOverride All" >> /etc/httpd/conf.d/laravel.conf \
 && echo "Require all granted" >> /etc/httpd/conf.d/laravel.conf \
 && echo "</Directory>" >> /etc/httpd/conf.d/laravel.conf \
 && echo "</VirtualHost>" >> /etc/httpd/conf.d/laravel.conf

# Required for Xdebug compilation
RUN yum -y install make

# Install Xdebug
RUN pecl install xdebug

# Install mailparse
RUN pear install pecl/mailparse
RUN touch /etc/php.d/30-mailparse.ini \
&& echo "extension=mailparse.so" >> /etc/php.d/30-mailparse.ini

# Add Xdebug to PHP configuration
RUN echo "" >> /etc/php.ini \
 && echo "[xdebug]" >> /etc/php.ini \
 && echo "zend_extension = /usr/lib64/php/modules/xdebug.so" >> /etc/php.ini \
 && echo "xdebug.remote_enable = 1" >> /etc/php.ini \
 && echo "xdebug.remote_autostart = 1" >> /etc/php.ini \
 && echo "xdebug.remote_host = host.docker.internal" >> /etc/php.ini

# Add composer
RUN yum -y install composer

# Add Vim
RUN yum -y install vim

EXPOSE 80

# Start Apache
CMD ["/usr/sbin/httpd","-D","FOREGROUND"]