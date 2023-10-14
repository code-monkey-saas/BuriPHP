<?php

namespace Services;

use Libraries\BuriPHP\Service;

class Permission extends Service
{
    public function listPermissions()
    {
        return [200, ['content' => $this->repository->getPermission()]];
    }
}
