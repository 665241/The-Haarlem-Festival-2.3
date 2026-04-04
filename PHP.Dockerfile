FROM php:8.3-fpm

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
     curl ca-certificates gnupg2 apt-transport-https \
     unixodbc-dev \
     git unzip \
  && rm -rf /var/lib/apt/lists/*

# Microsoft repo + ODBC driver
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /usr/share/keyrings/microsoft-prod.gpg \
 && curl -fsSL https://packages.microsoft.com/config/debian/12/prod.list > /etc/apt/sources.list.d/mssql-release.list \
 && apt-get update \
 && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
 && rm -rf /var/lib/apt/lists/*

# PHP extensions for SQL Server
RUN pecl install sqlsrv pdo_sqlsrv \
 && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Install Composer inside the PHP container
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app