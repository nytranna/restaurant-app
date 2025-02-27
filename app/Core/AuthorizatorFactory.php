<?php

namespace App\Core;

use Nette\Security\Permission;

class AuthorizatorFactory {

    public static function create(): Permission {
        $acl = new Permission;

        $acl->addRole('guest');
        $acl->addRole('staff', 'guest');
        $acl->addRole('admin', 'staff');

        $acl->addResource('Admin:Sign');
        $acl->addResource('Admin:Dashboard');
        $acl->addResource('Admin:Users');
        $acl->addResource('Admin:Profile');
        $acl->addResource('Admin:Reset');
        $acl->addResource('Admin:News');
        $acl->addResource('Admin:Info');

        $acl->allow('admin');

        $acl->allow('guest', 'Admin:Sign');
        $acl->allow('guest', 'Admin:Reset');

        $acl->allow('staff', 'Admin:Dashboard');
        $acl->allow('staff', 'Admin:Users', ['default']);
        $acl->allow('staff', 'Admin:Profile');
        $acl->allow('staff', 'Admin:Info', ['default']);
        $acl->allow('staff', 'Admin:News', ['default']);

        return $acl;
    }
}
