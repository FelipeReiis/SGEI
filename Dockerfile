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

# 💡 PADRÃO DEFINITIVO: Mantemos em /var/www para alinhar com o erro do print
WORKDIR /var/www

# Copia o build do Vite vindo do estágio anterior para ./public/build
COPY --from=frontend-builder /app/public/build ./public/build

# Instala as dependências de produção do Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader --no-dev

COPY . .
# Garante que as pastas existam em /var/www antes de dar permissões
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache

# Aplica as permissões corretas para o servidor web e uploads funcionarem
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

# Copia os arquivos de configuração do servidor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Abre a porta padrão de acessos HTTP
EXPOSE 1000

# Inicia o supervisor que cuida do Nginx + PHP-FPM ao mesmo tempo
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
