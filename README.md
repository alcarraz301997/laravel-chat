# LARAVEL CHAT
## CONSTRUIR, CONFIGURAR Y EJECUTAR USANDO DOCKER
Ir al directorio:
```
cd /docker
```

luego construye el contenedor de Docker php8.1 y nginx:
```
sudo docker-compose build
```

Luego, levanta el contenedor y ejecútelo:
```
sudo docker-compose up -d
```

No olvides que debes hacer una copia del *.env.example* y renombrarlo como **.env**, modificar algunas variables según tu entorno

Si vas a utilizar los comandos de laravel en Docker, debes ingresar a su terminal:
```
sudo docker exec -it laravel-chat_php sh
```

&nbsp;

## EJECUTAR EL PORYECTO LARAVEL

Antes de iniciar el servicio debes de instalar composer en la aplicación y este procesará los archivos necesarios para cargar **Laravel v.8.1** en el navegador web.

    composer install


Para ejecutar todas sus migraciones pendientes, ejecute el comando artisan migrate:

    php artisan migrate

Puede ejecutar el comando artisan db:seed para inicializar su base de datos. De forma predeterminada, el comando db:seed ejecuta la clase `Database\Seeders\DatabaseSeeder` , que a su vez puede invocar otras clases semilla. Sin embargo, puede usar la opción --class para especificar una clase de sembradora específica para ejecutarla individualmente:

    php artisan db:seed

*Más comandos*


Actualice la base de datos y ejecute todas las semillas de la base de datos:

    php artisan migrate:fresh --seed

&ensp;

Reference:
https://laravel.com/docs/8.x/releases