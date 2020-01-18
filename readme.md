## Requerimientos

-Php 5.6.40
-Composer

## Instalación
**Desde la consola**
- **Clona el repositorio**
git clone https://github.com/Javier-Aceros/evertec.git

- **Instalar dependencias**
composer install

- **Configure el archivo .env**
- Cambie el nombre del archivo .env.example a .env
- Configure los datos de conexión a la base de datos dentro del archivo .env con las variables en formato DB_XXX
- Ajuste las rutas de retorno por defecto bajo el nombre PLACETOPAY_RETURNURL y PLACETOPAY_CANCELURL

- **Agregue la llave de la aplicación**
php artisan key:generate

- **Ejecute las migraciones y seeders necesarios para el funcionamiento de la aplicación**
php artisan migrate
php artisan db:seed
