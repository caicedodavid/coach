<?php
    use Cake\ORM\TableRegistry;
    use Cake\Utility\Hash;

return [
    'Users.SimpleRbac.permissions' => [
        [
            'role' => 'coach',
            'controller' => 'Pqges',
            'action' => ['display'],
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
            'role' => ['user','coach'],
            'plugin'=> false,
            'controller' => 'AppUsers',
            'action' => [
                'edit',
                'view',
                'MyProfile',
                'coachProfile',
                'userProfile',
            ]
        ],
        [
            'role' => ['user'],
            'plugin'=> false,
            'controller' => 'AppUsers',
            'action' => [
                'coaches',
            ]
        ],
        [
            'role' => ['user','coach'],
            'plugin'=> false,
            'controller' => 'Sessions',
            'action' => [
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
                'rateCoach',
                'viewHistoric',
                'viewPendingCoach',
                'approvedCoach'
            ]
        ],
        [
            'role' => ['user'],
            'plugin'=> false,
            'controller' => 'Sessions',
            'action' => [
                'rateUser',
                'viewPendingUser',
                'add',
                'approvedUser'
            ]
        ],
        [
            'role' => ['coach'],
            'plugin'=> false,
            'controller' => 'Topics',
            'action' => [
                'add',
                'edit'
            ]
        ],
        [
            'role' => ['coach','user'],
            'plugin'=> false,
            'controller' => 'Topics',
            'action' => [
                'coachTopics',
                'view'
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