<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horarios sugeridos para tu consulta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            color: #333;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            max-width: 600px;
            margin: 0 auto;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #1a365d;
            text-align: center;
        }

        .text {
            font-size: 15px;
            line-height: 1.6;
        }

        .highlight {
            font-weight: bold;
            color: #2c5282;
        }

        .footer {
            margin-top: 25px;
            font-size: 13px;
            text-align: center;
            color: #718096;
        }

    </style>
</head>
<body>

    <div class="card">
        <div class="title">Estado actual del tratamiento</div>

        <p class="text">
            El médico {{ $nombre }} {{ $apellido }} te informa que tu tratamiento ha avanzado a la etapa de transferencia. Para continuar con el proceso, por favor solicitá un turno a la brevedad.
        </p>

        <p class="text">
            Estamos a tu disposición para ayudarte en lo que necesites.
        </p>

        <div class="footer">
            Fertilia · Clínica de Fertilidad  
            <br>"Juntos, creamos futuros"
        </div>
    </div>

</body>
</html>
