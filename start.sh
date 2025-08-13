#!/bin/bash
set -e

echo " Esperando a que MongoDB Atlas esté listo..."
until nc -z mycluster-shard-00-00.6tlbp.mongodb.net 27017; do
    sleep 2
done

echo "✅ MongoDB listo. Ejecutando migraciones..."
php artisan migrate --force

echo " Optimizando Laravel..."
php artisan optimize
php artisan storage:link

echo " Iniciando servidor Laravel..."
php artisan serve --host=0.0.0.0 --port=8000
