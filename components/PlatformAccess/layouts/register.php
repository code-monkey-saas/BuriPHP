<?php
defined('_EXEC') or die;

// Alertify
$this->dependencies->add(['css', '{$path.plugins}alertify/css/alertify.css']);
$this->dependencies->add(['js', '{$path.plugins}alertify/js/alertify.js']);

// Sweet Alert
$this->dependencies->add(['css', '{$path.plugins}sweet-alert2/sweetalert2.min.css']);
$this->dependencies->add(['js', '{$path.plugins}sweet-alert2/sweetalert2.min.js']);

// Bootstrap-inputmask
$this->dependencies->add(['js', '{$path.plugins}bootstrap-inputmask/jquery.inputmask.min.js']);

// Page
$this->dependencies->add(['css', '{$path.components}PlatformAccess/assets/styles.css']);
$this->dependencies->add(['js', '{$path.components}PlatformAccess/assets/register.js']);
?>
<div id="page">
    %{header}%

    <main id="main-content">
        <section class="p-t-10 p-b-50 gradient-primary-gray">
            <div class="container">
                <form id="user-register" name="user-register" class="form-access bx-shadow m-t-20 m-b-20">
                    <header>
                        <div class="icon-user">
                            <i class="ti-user"></i>
                        </div>

                        <h3 class="title-page">Registrarme</h3>
                    </header>
                    <main>
                        <div class="row">
                            <div class="col-12 m-b-10">
                                <div class="label">
                                    <label>
                                        <input name="name" type="text" required />
                                        <p class="description">Nombre</p>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 m-b-10">
                                <div class="label">
                                    <label>
                                        <input name="email" type="email" required />
                                        <p class="description">Correo electrónico</p>
                                    </label>
                                </div>
                            </div>
                            <div class="col-4 col-sm-5 p-r-10 m-b-10">
                                <div class="label">
                                    <label>
                                        <select name="prefix">
                                            <?php foreach ($ladas as $value) : ?>
                                            <option value="<?= $value['phone_code'] ?>"
                                                <?= ($value['phone_code'] === '52') ? 'selected' : '' ?>>
                                                <?= $value['nombre'] ?> ( +<?= $value['phone_code'] ?> )</option>
                                            <?php endforeach; ?>
                                        </select>
                                        <p class="description">Prefijo</p>
                                    </label>
                                </div>
                            </div>
                            <div class="col-8 col-sm-7 p-l-10 m-b-10">
                                <div class="label">
                                    <label>
                                        <input name="phone" type="tel" required />
                                        <p class="description">Teléfono <small>(10 Dígitos)</small></p>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 m-b-10">
                                <div class="label">
                                    <label>
                                        <input name="password" type="password" required />
                                        <p class="description">Contraseña</p>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 m-b-10">
                                <div class="label">
                                    <label>
                                        <input name="r-password" type="password" required />
                                        <p class="description">Repetir contraseña</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </main>
                    <footer>
                        <button type="submit" class="btn btn-block">Registrarme</button>
                        <a href="/login" class="btn btn-link btn-block">Ya tengo cuenta, iniciar sesión.</a>
                    </footer>
                </form>
            </div>
        </section>
    </main>

    %{footer}%
</div>