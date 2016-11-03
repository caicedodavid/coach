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
                $userId1 = Hash::get($request->params, 'pass.0');
                $userId2 = Hash::get($user, 'id');
                if (!empty($userId1) && !empty($userId2)) {
                    return $userId1 === $userId2;
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
                $userId1 = Hash::get($request->params, 'pass.0');
                $userId2 = Hash::get($user, 'id');
                if (!empty($userId1) && !empty($userId2)) {
                    return $userId1 === $userId2;
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
                $userId1 = Hash::get($user, 'id');
                $topictId = Hash::get($request->params, 'pass.0');
                $userId2 = TableRegistry::get('Topics')->get($topictId)['coach_id']; 
                if (!empty($userId1) && !empty($userId2)) {
                    return $userId1 ===$userId2;
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