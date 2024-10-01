<?php
// Datos de conexión al servidor de correo
$server = 'mail.contaweb.ec';
$username = 'correos@contaweb.ec';
$password = '$0$N8.M}2*?m';



// Conexión al servidor IMAP
// $inbox = imap_open('{'.$server.':993/imap/ssl}INBOX', $username, $password);
$inbox = imap_open('{'.$server.':995}INBOX', $username, $password);

// Verificación de la conexión
if (!$inbox) {
    die('No se pudo conectar al servidor de correo: ' . imap_last_error());
}

// Obtener el número total de correos electrónicos en la bandeja de entrada
$total_emails = imap_num_msg($inbox);

// Iterar a través de cada correo electrónico en la bandeja de entrada

// echo $server;

for ($i = 1; $i <= $total_emails; $i++) {
    // Obtener información del correo electrónico
    $header = imap_headerinfo($inbox, $i);
    $attachments = [];

    // Verificar si el correo electrónico tiene adjuntos
    if ($header->parts) {
        foreach ($header->parts as $part) {
            // Verificar si es un adjunto
            if ($part->disposition == 'attachment') {
                // Obtener el nombre del archivo adjunto
                $filename = $part->dparameters[0]->value;

                // Verificar si la extensión es .xml
                if (pathinfo($filename, PATHINFO_EXTENSION) === 'xml') {
                    // Leer el archivo adjunto
                    $attachment_data = imap_fetchbody($inbox, $i, $part->part_number);

                    // Guardar la información del archivo adjunto
                    $attachments[] = [
                        'filename' => $filename,
                        'data' => $attachment_data
                    ];
                }
            }
        }
    }

    // Realizar acciones adicionales con los adjuntos, si es necesario
    // ...

    // Imprimir la información de los archivos adjuntos leídos
    if (!empty($attachments)) {
        echo 'Información de archivos adjuntos XML del correo '.$i.':<br>';
        foreach ($attachments as $attachment) {
            echo 'Nombre: '.$attachment['filename'].'<br>';
            echo 'Contenido:<br>';
            echo $attachment['data'].'<br>';
        }
    }
}

// Cerrar la conexión
imap_close($inbox);
?>
