<?php

// This file is part of the Moodle block "Remlab Manager"
//
// Remlab Manager is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Remlab Manager is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// The GNU General Public License is available on <http://www.gnu.org/licenses/>
//
// Remlab Manager has been developed by:
//  - Luis de la Torre (1): ldelatorre@dia.uned.es
//
//  (1): Computer Science and Automatic Control, Spanish Open University (UNED),
//       Madrid, Spain


/**
 * Spanish strings
 *
 * @package    block
 * @subpackage remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
//block_remlab_manager.php:
$string['pluginname'] = 'Gestor de laboratorios remotos';
$string['block_title'] = 'Gestor de Laboratorios Remotos';
$string['configure_existing_experience'] = 'Configurar la experiencia';
$string['delete_existing_experience'] = 'Borrar la experiencia';
$string['or'] = 'o';
$string['configure_new_experience'] = 'Configurar nueva experiencia';

//settings.php:
$string['default_communication_set'] = "Opciones de comunicaci&oacute;n. (Importante s&oacute;lo si tambi&eacute;n usa Sarlab";
$string['sarlab_IP'] = "Nombre y direcci&oacute;n IP del servidor Sarlab";
$string['sarlab_IP_description'] = "Si usa Sarlab (un sistema que gestiona las conexiones a recursos de laboratorios remotos), debe proporcionar la direcci&oacute;n IP del servidor que ejecuta el sistema Sarlab que desea utilizar. En caso contrario, esta variable no se usa, de modo que puede dejar el valor por defecto. Si tiene m&aacute;s de un servidor Sarlab (por ejemplo, uno en 127.0.0.1 y otro en 127.0.0.2), inserte las direcciones IP separadas por puntos y comas: 127.0.0.1;127.0.0.2. Adem&aacute;s, puede proporcionar un nombre para identificar cada servidor Sarlab: 'Sarlab Madrid'127.0.0.1;'Sarlab Huelva'127.0.0.2";
$string['sarlab_port'] = "Puerto(s) de comunicaci&oacute; con Sarlab";
$string['sarlab_port_description'] = "Si usa Sarlab (un sistema que gestiona las conexiones a recursos de laboratorios remotos), debe proporcionar un puerto v&aacute;lido para establecer las comunicaciones necesarias con el servidor de Sarlab. En caso contrario, esta variable no se usa, de modo que puede dejar el valor por defecto. Si tiene m&aacute;s de un servidor Sarlab (por ejemplo, uno usando el puerto 443 y un segundo usando tambi&eacute;n el puerto 443), inserte los valores separados por puntos y comas: 443;443";
$string['sarlab_enc_key'] = "Clave de encriptaci&oacute;n para comunicarse con Sarlab";
$string['sarlab_enc_key_description'] = "Si usa Sarlab (un sistema que gestiona las conexiones a recursos de laboratorios remotos), debe proporcionar la clave de 16 caracteres para encriptar/desencriptar las comunicaciones con el servidor Sarlab (esta clave debe ser la misma que la configurada en el servidor Sarlab). En caso contrario, esta variable no se usa, de modo que puede dejar el valor por defecto.";

//edit_form.php:
$string['sarlab_header'] = 'Configurar Sarlab para esta instancia del bloque';
$string['use_sarlab'] = 'Usar Sarlab en sesiones colaborativas?';

//Capabilities
$string['remlab_manager:addinstance'] = 'Añadir un nuevo bloque Gestor Remlab';
$string['remlab_manager:myaddinstance'] = 'Añadir un nuevo bloque Gestor Remlab';

//view.php
$string['inserterror'] = 'Error al grabar la configuración de la experiencia de laboratorio remoto';
$string['confirm_deletion'] = '¿Está seguro de que desea borrar la experiencia seleccionada?';
$string['confirm_delete_button'] = 'Sí';
$string['cancel_delete_button'] = 'No';

//simplehtml_form.php
$string['configure_lab'] = 'Configurar laboratorio remoto';

$string['sarlab'] = "Usar Sarlab?";
$string['sarlab_help'] = "Seleccionar 'ss&iacute;' unicamente si se esta usando Sarlab; un sistema que gestiona las conexiones a recursos de laboratorios remotos";

$string['sarlab_instance'] = "Servidor Sarlab para este laboratorio";
$string['sarlab_instance_help'] = "El orden se corresponde con aquel usado para los valores en las variables sarlab_IP y sarlab_port fijados en la p&aacute;gina de configuraci&oacute;n de ejsapp";

$string['sarlab_collab'] = "Usar acceso colaborativo de Sarlab?";
$string['sarlab_collab_help'] = "Si deseas que Sarlab ofrezca la opci&oacute;n de acceso colaborativo a este laboratorio remoto o no";

$string['practiceintro'] = 'Identificador de la pr&aacute;ctica';
$string['practiceintro_help'] = 'El identificador de la pr&aacute;ctica con el que desea etiquetar esta configuraci&oacute;n.';
$string['practiceintro_required'] = 'ATENCI&Oacute;N: Debe especificar una pr&aacute;ctica.';

$string['ip_lab'] = 'Direcci&oacute;n IP';
$string['ip_lab_help'] = 'Direcci&oacute;n IP del sistema experimental.  Si est&aacute; usando Sarlab, no tiene que preocuparse de este par&aacute;metro.';
$string['ip_lab_required'] = 'ATENCI&Oacute;N: Debe proporcionar una direcci&oacute;n IP valida.';

$string['port'] = 'Puerto';
$string['port_help'] = 'El puerto a usar para establecer la comunicaci&oacute;n. Si est&aacute; usando Sarlab, no tiene que preocuparse de este par&aacute;metro.';
$string['port_required'] = 'ATENCI&Oacute;N: Debe proporcionar un puerto v&aacute;lido.';

$string['active'] = 'Disponible';
$string['active_help'] = 'Si este laboratorio remoto se encuentra operativo en este momento o no.';

$string['free_access'] = 'Acceso libre';
$string['free_access_help'] = 'Habilitar el acceso libre (sin necesidad de realizar reservas) a este laboratorio remoto.';

$string['slotsduration'] = 'Duraci&oacute;n de las franjas (minutos)';
$string['slotsduration_help'] = 'Duraci&oacute;n de las franjas de tiempo (en minutos) en las que los usuarios podr&aacute;n trabajar con el laboratorio.';

$string['totalslots'] = 'Franjas de trabajo totales';
$string['totalslots_help'] = 'Cantidad total de franjas m&aacute;ximas que se le permitir&aacute; usar a cada alumno para trabajar con este laboratorio.';
$string['weeklyslots'] = 'Franjas de trabajo semanales';
$string['weeklyslots_help'] = 'Cantidad semanal de franjas m&aacute;ximas que se le permitir&aacute; usar a cada alumno para trabajar con este laboratorio.';
$string['dailyslots'] = 'Franjas de trabajo diarias';
$string['dailyslots_help'] = 'Cantidad diaria de franjas m&aacute;ximas que se le permitir&aacute; usar a cada alumno para trabajar con este laboratorio. Adem&aacute;s, si el laboratorio es abierto, determina el n&uacute;mero m&aacute;ximo de franjas de tiempo consecutivas que se permite trabajar con el mismo.';

$string['reboottime'] = 'Tiempo de inactividad (minutos)';
$string['reboottime_help'] = 'Espacio m&iacute;nimo de tiempo (en minutos) desde que alguien deja de usar el laboratorio remoto hasta que otra persona puede empezar a usarlo. &Uacute;til para darle tiempo al laboratorio remoto de resetearse o volver a su estado inicial.';

$string['sarlab_exp_conf'] = 'Configuraci&oacute;n de la experiencia de Sarlab';

$string['ip_server'] = 'Dirección IP del servidor del laboratorio';
$string['ip_server_help'] = 'Establezca la dirección IP del laboratorio a la que se conecta.';

$string['ip_client'] = 'Dirección IP del cliente';
$string['ip_client_help'] = 'Establezca la dirección IP desde la cual el cliente se conecta.';

$string['port_server'] = 'Puerto del servidor del laboratorio';
$string['port_server_help'] = 'Establezca el puerto en el lado del servidor del laboratorio.';

$string['port_client'] = 'Puerto del cliente';
$string['port_client_help'] = 'Establezca el puerto en el lado del cliente.';

$string['lab_power_board'] = 'Unidad de alimentación';
$string['lab_power_board_help'] = 'El dispositivo que alimenta los equipos usados por este laboratorio.';

$string['lab_power_outputs'] = 'Salidas de alimentación';
$string['lab_power_outputs_help'] = 'Las salidas de la unidad de alimentación que necesitan activación.';