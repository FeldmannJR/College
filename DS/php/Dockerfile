FROM php:7.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    libzip-dev \
    curl \
    tzdata \
    libmcrypt-dev \
    libxslt1-dev \
    libpq-dev \
    libz-dev \
    libssl-dev \
    libmcrypt-dev \
    cron \
    sudo

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath \
    intl \
    mcrypt \
    opcache \
    soap \
    xsl \
    pgsql \
    pdo_pgsql


RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Setando a timezone do container
ENV TZ=America/Sao_Paulo

# Add user to match user id
RUN groupmod -g 1000 www-data
RUN usermod -u 1000 -g www-data www-data



COPY cronjobs /etc/cron.d/www-data
RUN chmod 0644 /etc/cron.d/www-data
RUN crontab -u www-data /etc/cron.d/www-data


WORKDIR /sites/

COPY entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
