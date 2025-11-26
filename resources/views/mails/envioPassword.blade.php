<!-- resources/views/emails/password-reset.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #1d72b8;
        }
        .content {
            font-size: 16px;
            line-height: 1.5;
        }
        .button {
            background-color: #1d72b8;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 30px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Nuevo registro</h1>
    </div>

    <div class="content">
        <p>Hola {{ $nombre}},</p>

        <p>Enviamos una contraseña temporal para que puedas acceder al sistema, una vez logueado por primera vez, debes cambiarla.</p>

        <p><strong>Contraseña Temporal: {{ $password }}</strong></p>

    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Tu Empresa. Todos los derechos reservados.</p>
    </div>
</div>

</body>
</html>
