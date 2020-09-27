FROM php:7.4-cli

LABEL maintainer="Bahadır Birsöz <github.com/bahdirbirsoz>"

RUN apt-get update && \
    apt-get install -y zlib1g-dev && \
    apt-get install -y libpng-dev && \
    apt-get install -y nano && \
    apt-get install -y libzip-dev && \
    apt-get install -y apt-utils && \
    apt-get install -y autoconf && \
    apt-get install -y mariadb-client &&\
    apt-get install -y libicu-dev && \
    apt-get install -y unzip && \
    apt-get install -y libxml2-dev && \
    apt-get install -y git  && \
    apt-get install -y libpcre3-dev  && \
    apt-get install -y build-essential && \
    apt-get install -y automake && \
    pecl install xdebug && \
    pecl install apcu && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    docker-php-ext-install soap  && \
    docker-php-ext-install zip && \
    docker-php-ext-install gd && \
    docker-php-ext-install pdo pdo_mysql  && \
    docker-php-ext-install gettext  && \
    docker-php-ext-enable apcu


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

WORKDIR /root

RUN git clone https://github.com/jbboehr/php-psr.git
RUN docker-php-ext-install ${PWD}/php-psr

RUN git clone  git://github.com/phalcon/php-zephir-parser.git
WORKDIR /root/php-zephir-parser
RUN git checkout tags/v1.3.4 ./
RUN docker-php-ext-install ${PWD}

WORKDIR /root
RUN curl -LO https://github.com/phalcon/zephir/releases/download/0.12.19/zephir.phar
RUN chmod +x zephir.phar
RUN mv zephir.phar /usr/local/bin/zephir


#ENV PATH="/root/.composer/vendor/bin:${PATH}"

#
#
#qq

WORKDIR /root
RUN git clone https://github.com/bahadirbirsoz/cphalcon.git
WORKDIR /root/cphalcon/
#RUN git checkout tags/v4.0.6 ./


#RUN git clone https://github.com/phalcon/cphalcon.git
#WORKDIR /root/cphalcon/
#RUN git checkout 4.0.x


RUN zephir fullclean
RUN zephir build


RUN docker-php-ext-enable ${PWD}/ext/modules/phalcon.so




WORKDIR /app
ADD ini/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
ADD composer.json .
RUN composer install
RUN chmod +x /app/vendor/bin/*
ENV PATH="/app/vendor/bin:/app/bin:${PATH}"

ADD src /app/src
ADD bin /app/bin

CMD php /app/bin/conv

#RUN ln -s ./vendor/bin/* /usr/local/bin/

#RUN PATH=$PATH:/apps/api/vendor/bin

#RUN apt-get clean -y



