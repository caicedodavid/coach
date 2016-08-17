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
            'role' => 'user',
            'controller' => 'Posts',
            'action' => ['edit', 'delete'],
            'allowed' => function (array $user, $role, Request $request) {
                $postId = Hash::get($request->params, 'pass.0');
                $post = TableRegistry::get('Posts')->get($postId);
                $userId = Hash::get($user, 'id');
                if (!empty($post->user_id) && !empty($userId)) {
                    return $post->user_id === $userId;
                }
                return false;
            }
        ],
    ]
];
return [
   'Users.SimpleRbac.permissions' => [
        [
           'role' => 'user',
           'controller' => 'Users',
           'action' => ['view'],
        ],
    ]
];