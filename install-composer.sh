#!/bin/bash
set -e

# Descargar Composer
curl -sS https://getcomposer.org/installer | php

# Instalar dependencias de Laravel
php composer.phar install --no-dev --optimize-autoloader
