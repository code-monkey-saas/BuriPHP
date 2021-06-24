<?php defined('_EXEC') or die; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
	<head>
		<meta charset="UTF-8" />
		<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type" />

		<base href="{$_base}">

		<title>{$_title}</title>

		<!--Adaptive Responsive-->
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
		<meta name="author" content="" />
		<meta name="description" content="" />

		<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">

		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="stylesheet" href="{$path.components}PlatformAccess/assets/valkyrie.css?v=1.0" type="text/css" media="all" />
		<link rel="stylesheet" href="{$path.components}PlatformAccess/assets/login.css?v=1.0" type="text/css" media="all" />
	</head>
	<body>
        <div class="page login">
            <form name="login">
                <a href="index.php" class="logo"><img src="{$path.images}logotype-large.svg" height="30" alt="logo"></a>

                <h2>Iniciar sesión</h2>

                <div class="label">
                    <label>
                        <input name="username" type="text" placeholder="Correo electrónico"/>
                        <p class="description"></p>
                    </label>
                </div>
                <div class="label">
                    <label>
                        <input name="password" type="password" placeholder="Contraseña"/>
                        <p class="description"></p>
                    </label>
                </div>

                <div class="button-items">
                    <button class="btn btn-block btn-primary" type="submit">Iniciar sesión</button>
                </div>
            </form>
            <small class="copy">Design by <a href="javascript:void(0);">codemonkey.com.mx</a></small>
        </div>

        <script src="{$path.components}PlatformAccess/assets/jquery-3.4.1.min.js"></script>
        <script src="{$path.components}PlatformAccess/assets/valkyrie.js?v=1.0"></script>
        <script src="{$path.components}PlatformAccess/assets/codemonkey-1.2.0.js?v=1.0"></script>
        <script src="{$path.components}PlatformAccess/assets/login.js?v=1.0"></script>
    </body>
</html>
