<?php
    use Cake\ORM\TableRegistry;
    use Cake\Utility\Hash;
    use Cake\Network\Request;

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
                'view',
                'MyProfile',
                'coachProfile',
                'userProfile',
            ]
        ],
        [
            'role' => ['user','coach'],
            'plugin'=> false,
            'controller' => 'AppUsers',
            'action' => [
                'edit',
            ],
            'allowed' => function (array $user, $role, Request $request) {
                $ownerUserId = Hash::get($request->params, 'pass.0');
                $sessionUserId = Hash::get($user, 'id');
                if (!empty($ownerUserId) && !empty($sessionUserId)) {
                    return $ownerUserId === $sessionUserId;
                }
                return false;
            }
        ],
        [
            'role' => ['user','coach'],
            'plugin'=> false,
            'controller' => 'Sessions',
            'action' => [
                'viewPending',
                'view',
                'cancel',
                'rate',
                'updateStartTime',
                'cancelSession'
            ]
        ],
        [
            'role' => ['user','coach'],
            'plugin'=> false,
            'controller' => 'Sessions',
            'action' => [
                'pending',
                'approved',
                'historic',
            ],
            'allowed' => function (array $user, $role, Request $request) {
                $ownerUserId = Hash::get($request->params, 'pass.0');
                $sessionUserId = Hash::get($user, 'id');
                if (!empty($ownerUserId) && !empty($sessionUserId)) {
                    return $ownerUserId === $sessionUserId;
                }
                return false;
            }
        ],
        [
            'role' => ['coach'],
            'plugin'=> false,
            'controller' => 'Sessions',
            'action' => [
                'paidSessions',
                'unpaidSessions'
            ],
            'allowed' => function (array $user, $role, Request $request) {
                $ownerUserId = Hash::get($request->params, 'pass.0');
                $sessionUserId = Hash::get($user, 'id');
                if (!empty($ownerUserId) && !empty($sessionUserId)) {
                    return $ownerUserId === $sessionUserId;
                }
                return false;
            }
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
                'viewApprovedCoach',
                'viewHistoricCoach'
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
                'viewApprovedUser',
                'viewHistoricUser'
            ]
        ],
        [
            'role' => ['coach'],
            'plugin'=> false,
            'controller' => 'Topics',
            'action' => [
                'add',
            ],
            'allowed' => function (array $user, $role, Request $request) {
                $ownerUserId = Hash::get($request->params, 'pass.0');
                $sessionUserId = Hash::get($user, 'id');
                if (!empty($ownerUserId) && !empty($sessionUserId)) {
                    return $ownerUserId === $sessionUserId;
                }
                return false;
            }
        ],
        [
            'role' => ['coach'],
            'plugin'=> false,
            'controller' => 'Topics',
            'action' => [
                'edit'
            ],
            'allowed' => function (array $user, $role, Request $request) {
                $sessionUserId = Hash::get($user, 'id');
                $topictId = Hash::get($request->params, 'pass.0');
                $ownerUserId = TableRegistry::get('Topics')->get($topictId)['coach_id']; 
                if (!empty($sessionUserId) && !empty($ownerUserId)) {
                    return $sessionUserId === $ownerUserId;
                }
                return false;
            }
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
            'role' => ['user'],
            'plugin'=> false,
            'controller' => 'PaymentInfos',
            'action' => [
                'add',
            ],

        ],
        [
            'role' => ['user'],
            'plugin'=> false,
            'controller' => 'PaymentInfos',
            'action' => [
                'edit',
                'delete'
            ],
            'allowed' => function (array $user, $role, Request $request) {
                $sessionUserId = Hash::get($user, 'id');
                $paymentInfosId = Hash::get($request->params, 'pass.0');
                $ownerUserId = TableRegistry::get('PaymentInfos')->get($paymentInfosId)['user_id'];
                if (!empty($sessionUserId) && !empty($ownerUserId)) {
                    return $sessionUserId === $ownerUserId;
                }
                return false;
            }

        ],
        [
            'role' => ['user'],
            'plugin'=> false,
            'controller' => 'PaymentInfos',
            'action' => [
                'cards'
            ],
            'allowed' => function (array $user, $role, Request $request) {
                $ownerUserId = Hash::get($request->params, 'pass.0');
                $sessionUserId = Hash::get($user, 'id');
                if (!empty($sessionUserId) && !empty($ownerUserId)) {
                    return $sessionUserId === $ownerUserId;
                }
                return false;
            }
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