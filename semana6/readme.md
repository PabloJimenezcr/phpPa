
*********** PASOS **************
1- Instalar PHPMailer en el proyecto
Para trabajar con PHPMailer, primero se debe instalar Composer, el administrador de dependencias de PHP. Si ya está instalado, abrir la terminal integrada de Visual Studio Code y ejecutar

composer require phpmailer/phpmailer

Si no se ejecuta el comando o sale algun error de comando desconocido, deben instalarlo: 

https://getcomposer.org/download/

El comando instala PHPMailer y crea los archivos necesarios para cargar la biblioteca

2- Configurar el envío en correo.php
Este archivo contendrá la configuración SMTP y una función que enviará el correo de confirmación.

Para el ejemplo se utilizará una configuración SMTP de tipo Gmail. Si se utiliza Google Workspace, otro proveedor o un servidor de correo del hosting, se deben reemplazar los datos por los que proporcione ese servicio

3- Crear index.php
Este archivo tendrá el formulario y el procesamiento. Incluirá:
- Nombre, correo y teléfono
- Modalidad mediante radio buttons
- País mediante una lista desplegable
- Intereses mediante checkbox
- Aceptación obligatoria de las condiciones
- Una fotografía opcional
- Validación en PHP y mensajes claros
- Conservación temporal de los campos cuando existan errores
- Envío de un correo real mediante PHPMailer cuando los datos sean válidos