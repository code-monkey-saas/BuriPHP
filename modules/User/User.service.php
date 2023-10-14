<?php

namespace Services;

use Libraries\BuriPHP\Helpers\HelperArray;
use Libraries\BuriPHP\Helpers\HelperCrypt;
use Libraries\BuriPHP\Helpers\HelperNumber;
use Libraries\BuriPHP\Helpers\HelperString;
use Libraries\BuriPHP\Helpers\HelperValidate;
use Libraries\BuriPHP\Service;
use Libraries\Functions;

class User extends Service
{
    public $session;

    public function __init()
    {
        $this->session = Functions::getSession();
    }

    private function verifyLabels($request, $update = false)
    {
        $labels = [];

        if (
            !isset($request['email']) ||
            HelperValidate::isEmpty($request['email']) ||
            !HelperValidate::isEmail($request['email'])
        ) {
            $labels = HelperArray::append($labels, [
                'label' => 'email',
                'message' => 'El correo electrónico no es válido.'
            ]);
        }

        if (!$update && isset($request['email']) && count($this->repository->read(where: ['email' => $request['email']])) >= 1) {
            $labels = HelperArray::append($labels, [
                'label' => 'email',
                'message' => 'El correo electrónico ya está registrado.'
            ]);
        }

        if (!$update || $update && !empty($request['password'])) {
            if (
                !isset($request['password']) ||
                HelperValidate::isEmpty($request['password']) ||
                strlen($request['password']) < 8
            ) {
                $labels = HelperArray::append($labels, [
                    'label' => 'password',
                    'message' => 'La contraseña no es válida.'
                ]);
            }

            if (
                !$update &&
                !isset($request['rpassword']) ||
                HelperValidate::isEmpty($request['rpassword']) ||
                $request['rpassword'] !== $request['password']
            ) {
                $labels = HelperArray::append($labels, [
                    'label' => 'rpassword',
                    'message' => 'Las contraseñas, no coinciden.'
                ]);
            }
        }

        if (
            !isset($request['name']) ||
            HelperValidate::isEmpty($request['name'])
        ) {
            $labels = HelperArray::append($labels, [
                'label' => 'name',
                'message' => 'Por favor, escribe tu nombre.'
            ]);
        }

        if (!HelperValidate::isEmpty($labels)) {
            return ['labels_errors' => $labels];
        } else {
            return false;
        }
    }

    /**
     * Create user
     * 
     * @param array $request
     */
    final public function create($request)
    {
        $verifyLabels = $this->verifyLabels($request);

        if ($verifyLabels != false) {
            return [400, $verifyLabels];
        }

        $userObject = [
            'email' => $request['email'],
            'password' => HelperCrypt::createPassword($request['password']),
            'name' => $request['name'],
            'username' => preg_replace('/[^a-z0-9]/', '', HelperString::toLower(explode('@', $request['email'])[0])),
            'permission_id' => (isset($request['permissionId']) && !empty($request['permissionId'])) ? (int) $request['permissionId'] : null,
            'status' => 'active'
        ];

        $id = $this->repository->create($userObject);

        if (!is_numeric($id) || $id <= 0) {
            return [502, ['message' => 'Error al registrar el usuario.']];
        }

        return [200, ['content' => $this->getUser($id, 'id')[1]['content'], 'redirect' => '/dashboard/users']];
    }

    /**
     * Get list users
     */
    final public function getUsers()
    {
        return [200, ['content' => $this->repository->read(where: ['order' => ['name' => 'ASC']])]];
    }

    /**
     * Get user by id default
     * 
     * @param int|string $user
     * @param string $by options available id, username, email
     */
    final public function getUser($user, $by, $password = false)
    {
        $object = [];

        if (true == $password) {
            $object = ['password'];
        }

        if ('id' === $by) {
            $response = $this->repository->read(id: $user, object: $object);
        }

        if ('username' === $by || 'email' === $by) {
            $response = $this->repository->read(where: [$by => $user], object: $object);
        }

        if (!isset($response)) {
            return [400, []];
        }

        return [200, ['content' => isset($response[0]) ? $response[0] : $response]];
    }

    /**
     * Update user by id default
     * 
     * @param array $request
     * @param int|string $user
     * @param string $by options available id, username, email
     */
    final public function update($request, $user, $by)
    {
        $object = [];
        $user = $this->getUser($user, $by, true)[1]['content'];

        if (array_key_exists('name', $request) && array_key_exists('email', $request) && array_key_exists('phone', $request) && array_key_exists('permissionId', $request)) {
            $verifyLabels = $this->verifyLabels($request, true);

            if ($verifyLabels != false) {
                return [400, $verifyLabels];
            }

            if (isset($request['email'])) {
                $object['email'] = $request['email'];
            }
        }

        if (array_key_exists('lastPassword', $request) && array_key_exists('newPassword', $request) && array_key_exists('newRPassword', $request)) {
            $password = explode(':', $user['password']);
            $checkPassword = (HelperCrypt::createHash('sha1', $request['lastPassword'] . $password[1]) === $password[0]) ? true : false;

            if (false == $checkPassword) {
                return [400, ['message' => "La contraseña anterior no coincide."]];
            }

            if ($request['newPassword'] != $request['newRPassword']) {
                return [400, ['message' => "Las nuevas contraseñas deben coincidir."]];
            }

            $object['password'] = HelperCrypt::createPassword($request['newPassword']);
        }

        if (array_key_exists('password', $request) && !empty($request['password'])) {
            $object['password'] = HelperCrypt::createPassword($request['password']);
        }

        if (array_key_exists('name', $request)) {
            $object['name'] = $request['name'];
        }

        if (array_key_exists('phone', $request)) {
            $object['phone'] = (int) HelperNumber::getNumbers($request['phone']);

            $object['phone'] = ($object['phone'] <= 0) ? null : $object['phone'];
        }

        if (array_key_exists('permissionId', $request)) {
            $object['permissionId'] = (empty($request['permissionId']) || is_null($request['permissionId'])) ? null : (int) $request['permissionId'];
        }

        $this->repository->update($object, $user['userId']);

        return [200, ['content' => $this->getUser($user['userId'], $by)[1]['content']]];
    }

    /**
     * Delete user by id default
     * 
     * @param int|string $user
     * @param string $by options available id, username, email
     */
    final public function delete($user, $by)
    {
        $user = $this->getUser($user, $by)[1]['content'];

        if (count($user) <= 0) {
            return [400, ['message' => 'El usuario no existe.']];
        }

        if (is_numeric($user['permissionId']) && $user['permissionId'] == 1 || $user["userId"] === $this->session["decode"]["user"]["userId"]) {
            return [403, ['message' => 'No puedes eliminar este usuario.']];
        }

        $this->repository->delete($user['userId']);

        return [200, []];
    }
}
