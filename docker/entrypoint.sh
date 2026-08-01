#!/bin/sh
set -e

echo "🚀 Iniciando preparação do ambiente Laravel..."

# 1. Garante a criação do link simbólico da pasta storage
php artisan storage:link --force || true

# 2. Executa as migrations automaticamente no PostgreSQL do Render
echo "📦 Executando migrations..."
php artisan migrate --force

# 3. Limpa e recria os caches de configuração para produção
echo "🧹 Otimizando caches do Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Inicialização concluída! Subindo Nginx e PHP-FPM..."

# Executa o Supervisor para manter os serviços rodando
exec /usr/bin/supervisord -c /etc/supervisord.conf
