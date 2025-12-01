<p>Has recibido un nuevo mensaje desde el formulario de contacto:</p>

<ul>
    <li><strong>Nombre:</strong> {{ $data['name'] ?? '' }}</li>
    <li><strong>Correo:</strong> {{ $data['email'] ?? '' }}</li>
    <li><strong>Asunto:</strong> {{ $data['subject'] ?? 'Sin asunto' }}</li>
</ul>

<p><strong>Mensaje:</strong></p>
<p>{{ nl2br(e($data['message'] ?? '')) }}</p>
