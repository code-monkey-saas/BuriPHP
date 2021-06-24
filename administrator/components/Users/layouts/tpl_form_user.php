<?php defined('_EXEC') or die; ?>
<div class="row">
    <div class="col-12 m-b-10">
        <div class="label">
            <label>
                <input name="name" type="text"/>
                <p class="description">Nombre</p>
            </label>
        </div>
    </div>
    <div class="col-12 m-b-10">
        <div class="label">
            <label>
                <input name="username" type="text"/>
                <p class="description">Usuario</p>
            </label>
        </div>
    </div>
    <div class="col-12 m-b-10">
        <div class="label">
            <label>
                <input name="email" type="text"/>
                <p class="description">Correo electrónico</p>
            </label>
        </div>
    </div>
    <div class="col-4 col-sm-5 p-r-10 m-b-10">
        <div class="label">
            <label>
                <select name="prefix">
                    <?php foreach ( $ladas as $value ): ?>
                        <option value="<?= $value['phone_code'] ?>" <?= ( $value['phone_code'] === '52' ) ? 'selected' : '' ?> ><?= $value['nombre'] ?> ( +<?= $value['phone_code'] ?> )</option>
                    <?php endforeach; ?>
                </select>
                <p class="description">Prefijo</p>
            </label>
        </div>
    </div>
    <div class="col-8 col-sm-7 p-l-10 m-b-10">
        <div class="label">
            <label>
                <input name="phone" type="tel" data-inputmask="'mask': '(999) 999-9999'"/>
                <p class="description">Teléfono <small>(10 Dígitos)</small></p>
            </label>
        </div>
    </div>
    <div class="col-12 m-b-10">
        <div class="label">
            <label>
                <input name="password" type="password"/>
                <p class="description">Contraseña</p>
            </label>
        </div>
    </div>
    <div class="col-12 m-b-20">
        <div class="label">
            <label>
                <select name="level">
                    <?php foreach ( $levels as $value ): ?>
                        <option value="<?= $value['id'] ?>"><?= $value['title'] ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="description">Nivel de usuario</p>
            </label>
        </div>
    </div>
    <div class="col-12">
        <p>Seleccione los permisos del usuario.</p>
    </div>
    <div class="col-12">
        <?php foreach ( $permissions as $value ): ?>
            <div class="label">
                <label class="checkbox">
                    <p class="font-16"><?= $value['title'] ?></p>
                    <input type="checkbox" class="input-primary" name="permissions[]" value="<?= $value['code'] ?>"/>
                    <div class="checkbox_indicator"></div>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
</div>
