<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página No Encontrada - 404</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }
        .error-container {
            max-width: 500px;
            padding: 20px;
        }
        .error-code {
            font-size: 72px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
        }
        .error-message {
            font-size: 24px;
            color: #6c757d;
            margin-bottom: 20px;
        }
        .error-description {
            color: #6c757d;
            margin-bottom: 30px;
        }
        .btn-home {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn-home:hover {
            background-color: #0056b3;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Página No Encontrada</div>
        <div class="error-description">
            Lo sentimos, la página que estás buscando no existe o ha sido movida.
        </div>
        <a href="{{ url('/') }}" class="btn-home">Volver al Inicio</a>
    </div>
</body>
</html>
