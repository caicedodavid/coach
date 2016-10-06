<?php
    use Cake\ORM\TableRegistry;
    use Cake\Utility\Hash;

return [
    'Users.SimpleRbac.permissions' => [
        [
            'role' => 'user',
            'controller' => 'Posts',
            'action' => ['view'],
        ],
        [
            'role' => ['user','coach'],
            'plugin'=> 'CakeDC/Users',
            'controller' => 'Users',
            'action' => ['logout',
                'requestResetPassword',
                'resendTokenValidation'
            ]
        ],

        [
            'role' => ['user'],
            'plugin'=> false,
            'controller' => 'AppUsers',
            'action' => [
                'edit',
                'coaches',
                'view'
            ]
        ],
        [
            'role' => ['coach'],
            'plugin'=> false,
            'controller' => 'AppUsers',
            'action' => [
                'edit'
            ]
        ],
        [
            'role' => ['user','coach'],
            'plugin'=> false,
            'controller' => 'Sessions',
            'action' => [
                'add',
                'pending',
                'viewPending',
                'historic',
                'approved',
                'view',
                'cancel',
                'rate',
                'updateStartTime',
                'cancelSession'
            ]
        ],
        [
            'role' => ['coach'],
            'plugin'=> false,
            'controller' => 'Sessions',
            'action' => [
                'rejectSession',
                'approveSession',
            ]
        ],
        [
            'role' => 'admin',
            'prefix' => 'Admin',
            'controller' => '*',
            'action' => '*',
        ],
        [
            'role' => 'admin',
            'plugin'=> 'CakeDC/Users',
            'controller' => 'Users',
            'action' => [
                'logout',
                'requestResetPassword',
                'resendTokenValidation',
            ]
        ]
    ]
];