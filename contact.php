<?php
/*
 *  CONFIGURACIONES
 */

//Aquí puede ir la dirección de correo careers@dintdigital.com
// la dirección de correo electrónico que estará en el campo del correo electrónico
$from = 'Formulario de contacto Landing Page <admin@dominio.com>';
// la dirección de correo electrónico que recibirá el correo electrónico con el resultado del formulario
$sendTo = 'Formulario de contacto Landing Page <admin@dominio.com>';

// el asunto del correo electrónico
$subject = 'Nuevo mensaje del formulario de contacto';

// formar nombres de campo y sus traducciones.
// array nombre de la variable => Texto que aparecerá en el correo electrónico
$fields = array('nombre' => 'Nombre', 'correo' => 'Correo', 'telefono' => 'Teléfono', 'mensaje' => 'Mensaje'); 

// mensaje que se mostrará cuando todo esté bien :)
$okMessage = 'Formulario de contacto enviado con éxito. ¡Gracias!';

//Si algo sale mal, mostraremos este mensaje :(
$errorMessage = 'Hubo un error al enviar el formulario. Por favor, inténtelo de nuevo más tarde';

/*
 *  EL ENVÍO
 */

//si no está depurando y no necesita informes de errores, desactívela mediante error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);

try
{
    if(count($_POST) == 0) throw new \Exception('Formulario vacío');        
    $emailText = "Tienes un nuevo mensaje de tu formulario de contacto\n=============================\n";
    foreach ($_POST as $key => $value) {
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    );
    
    mail($sendTo, $subject, $emailText, implode("\n", $headers));

    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    header('Content-Type: application/json');
    echo $encoded;
}
else {
    echo $responseArray['message'];
}