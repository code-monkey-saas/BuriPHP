<?php

namespace Services;

use Libraries\BuriPHP\Service;

class Register extends Service
{
    final public function register($request)
    {
        $userService = $this->serviceShared('User', 'User');
        $user = $userService->create($request);

        if (200 !== $user[0]) {
            return [$user[0], $user[1]];
        }

        $user = $user[1]['content'];

        $loginService = $this->serviceShared('Authentication', 'Login');
        $session = $loginService->setSession(
            userId: $user['userId'],
            email: $user['email'],
            name: $user['name'],
            username: $user['username'],
        );

        if (isset($request['redirect'])) {
            $session['redirect'] = base64_decode($request['redirect']);
        } else {
            $session['redirect'] = '/user/' . $user['username'];
        }

        return [200, $session];
    }
}
