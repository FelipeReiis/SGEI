# ==========================================
# ESTÁGIO 1: Compilar o Front-end (Vite + Vue 3)
# ==========================================
FROM node:20-alpine AS frontend-builder
WORKDIR /app

# Copia os arquivos de dependências mapeados no seu package.json
COPY package*.json ./
RUN npm install

# Copia o código fonte do projeto
COPY . .

# Cria um .env temporário baseado no seu template para o Vite não reclamar
RUN cp .env.example .env

# Executa o "vite build" exatamente como está nos seus scripts do package.json
RUN npm run build

# ==========================================
# ESTÁGIO 2: Imagem de Produção Otimizada (PHP 8.4 + Nginx)
# ==========================================
FROM php:8.4-fpm-alpine

# Instala as dependências de sistema essenciais
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    postgresql-dev

# Instala as extensões nativas do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql exif gd zip opcache

# Traz o binário do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia todo o projeto PRIMEIRO
COPY . .

# Copia o build do Vite vindo do estágio 1
COPY --from=frontend-builder /app/public/build ./public/build

# Instala as dependências de produção do Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Garante que as pastas essenciais do Laravel e Nginx existam e tenham permissão 777/775
RUN mkdir -p /var/www/storage/framework/views \
             /var/www/storage/framework/sessions \
             /var/www/storage/framework/cache \
             /var/www/storage/logs \
             /var/www/bootstrap/cache \
             /run/nginx

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /run/nginx
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache

# Copia os arquivos de configuração do servidor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Garante a permissão de execução dentro do container
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expõe a porta do Render
EXPOSE 10000

# Executa o script que roda migrations + storage:link + supervisor
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
