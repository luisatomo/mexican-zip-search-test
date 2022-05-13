## Backbone Challenge Senior Backend Developer

Se creo un controlador ZipController.php en app/Http/ZipController.php

Se utiliza un archivo de texto que se descargo de https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx como: ZIP_TXT_DB="/resources/files/CompleteFile.csv"
ZIP_CSV_DB="/resources/files/ZipsOnly.csv"

Un Helper, en app/Http/Helpers/DataHelper.php donde se hace el buscador y algunos metodos estaticos 
en los cuales 
- Se convierten valores a Enteros.
- Se da formato a la respuesta.
- Se quitan tildes de las cadenas.
- Al buscar directamente en los archivos txt se noto que el tiempo de ejecucion era mayor al esperado
- Se decidio importar los datos a una base de datos mariadb e indexar la columna con el zip.

- create database zips;
- se creo las migraciones con make:migrate
- php artisan migrate
- se importo la base de datos desde csv:
- mysqlimport -u root -p zips resources/files/zips.csv --ignore-lines=1 --fields-terminated-by=, --fields-optionally-enclosed-by='"'

