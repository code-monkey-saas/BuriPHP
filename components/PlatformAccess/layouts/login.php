<?php
defined('_EXEC') or die;

// Alertify
$this->dependencies->add(['css', '{$path.plugins}alertify/css/alertify.css']);
$this->dependencies->add(['js', '{$path.plugins}alertify/js/alertify.js']);

// Page
$this->dependencies->add(['css', '{$path.components}PlatformAccess/assets/styles.css']);
$this->dependencies->add(['js', '{$path.components}PlatformAccess/assets/login.js']);
?>
<div id="page">
    %{header}%

    <main id="main-content">
        <section class="p-t-10 p-b-50 gradient-primary-gray">
            <div class="container">
                <form id="user-login" name="user-login" class="form-access bx-shadow m-t-20 m-b-20">
                    <header>
                        <div class="icon-user">
                            <i class="ti-user"></i>
                        </div>

                        <h3 class="title-page">Inicio de sesión</h3>
                    </header>
                    <main>
                        <div class="row">
                            <div class="col-12 m-b-10">
                                <div class="label">
                                    <label>
                                        <input name="email" type="text" />
                                        <p class="description">Correo electrónico</p>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 m-b-10">
                                <div class="label">
                                    <label>
                                        <input name="password" type="password" />
                                        <p class="description">Contraseña</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </main>
                    <footer>
                        <button type="submit" class="btn btn-block">Iniciar sesión</button>
                        <a href="/register" class="btn btn-link btn-block">No tengo cuenta, registrarme.</a>
                    </footer>
                </form>
            </div>
        </section>
    </main>

    %{footer}%
</div>