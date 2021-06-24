<?php
defined('_EXEC') or die;

use \BuriPHP\Administrator\Libraries\{Dashboard};
use \BuriPHP\System\Libraries\{Session};
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
	<head>
		<meta charset="UTF-8" />
		<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type" />
		{$dependencies.meta}

		<base href="{$_base}">

		<title>{$_title}</title>

		<!--Adaptive Responsive-->
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
		<meta name="author" content="" />
		<meta name="description" content="" />

		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="stylesheet" href="{$path.css}valkyrie.css?v=1.0" type="text/css" media="all" />
		<link rel="stylesheet" href="{$path.css}icons.css" type="text/css" media="all" />
		<link rel="stylesheet" href="{$path.css}styles.css?v=1.0" type="text/css" media="all" />

		<link rel="stylesheet" href="{$path.plugins}alertify/css/alertify.css" type="text/css" media="all" />
		<link rel="stylesheet" href="{$path.plugins}sweet-alert2/sweetalert2.min.css" type="text/css" media="all" />
		{$dependencies.css}
	</head>
	<body>
		<!-- Loader -->
		<div id="preloader"><div id="status"><div class="spinner"></div></div></div>

		<!-- Navigation Bar-->
		<header class="topnav d-print-none">
			<div class="topbar-main">
				<div class="container-fluid">
					<figure class="logo">
						<a href="javascript:void(0);">
							<img src="{$path.images}logotype-sm-white.svg" alt="Logotipo" height="50" class="logo-sm">
							<img src="{$path.images}logotype-large-white.svg" alt="Logotipo" height="50" class="logo-large">
						</a>
					</figure>

					<div class="topbar-custom">
						<ul class="list-inline">
							<!-- User -->
							<li class="list-inline-item">
								<div class="dropmenu menu-right">
									<button class="btn btn-b-none waves-effect waves-light"><span class="status_session"></span> <?= Session::get_value('__user') ?></button>
									<div class="dropdown">
										<?php if ( in_array('{users_read}', Session::get_value('session_permissions')) ): ?>
											<a href="index.php/users">Usuarios</a>
										<?php endif; ?>
										<?php if ( in_array('{help_development}', Session::get_value('session_permissions')) ): ?>
											<a href="index.php/help">Ayuda</a>
										<?php endif; ?>
										<span class="space"></span>
										<a class="waves-effect" href="index.php/logout">Cerrar sesión</a>
									</div>
								</div>
							</li>
							<!-- Mobile menu toggle -->
							<li class="list-inline-item menu-item">
								<button id="trigger-nav-mobile" type="button" class="btn btn-b-none waves-effect waves-light menu-wrapper">
									<div class="hamburger-menu"></div>
								</button>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<nav class="navbar-custom">
				<div class="container-fluid">
					<ul class="navigation-menu list-inline">
						<?php foreach ( Dashboard::main_menu() as $value ): ?>
							<li <?= ( isset( $value['submenu'] ) ) ? 'class="has-submenu"' : '' ?>>
								<a href="<?= $value['url'] ?>" <?= ( isset($value['target']) ) ? "target=\"{$value['target']}\"" : "" ?>><?= ( isset($value['icon']) ) ? "<i class='{$value['icon']}'></i>" : "" ?> <?= $value['name'] ?></a>
								<?php if ( isset( $value['submenu'] ) ): ?>
									<ul class="submenu">
										<?php foreach ( $value['submenu'] as $submenu ): ?>
											<li>
												<a href="<?= $submenu['url'] ?>"><?= $submenu['name'] ?></a>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</nav>
		</header>
		<!-- End Navigation Bar-->
