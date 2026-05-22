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
# ESTÁGIO 2: Imagem de Produção Otimizada (PHP 8.3 + Nginx)
# ==========================================
FROM php:8.4-fpm-alpine

# Instala apenas as dependências de sistema para o seu ambiente (Nginx, Supervisor, Git, Zip e Postgres)
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

# Instala as extensões nativas que seu Laravel 12 e Banco de Dados precisam:
# - pdo_pgsql: conexão direta com seu banco Postgres
# - gd e exif: cruciais para o upload das imagens dos comprovantes sem quebra de rotação
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql exif gd zip opcache

# Traz o binário do Composer para gerenciar seu composer.json
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia a estrutura do Laravel
COPY . .

# Copia o resultado do "npm run build" do primeiro estágio direto para a pasta pública
COPY --from=frontend-builder /app/public/build /var/www/html/public/build

# Instala as dependências de produção do Composer (ignora require-dev como sail, breeze, pint)
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Aplica as permissões corretas para que o upload dos comprovantes funcione no storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copia os arquivos de configuração do servidor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Abre a porta padrão de acessos HTTP
EXPOSE 80

# Inicia o supervisor que cuida do Nginx + PHP-FPM ao mesmo tempo
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
