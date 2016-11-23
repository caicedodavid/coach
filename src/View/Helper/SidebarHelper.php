<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;

class SidebarHelper extends Helper
{
    public $helpers = ['Image','Html'];

    /**
     * retrun the respective tabs
     *
     * @param user user entity
     * @return array
     */
    public function tabs($user)
    {
        if ($user->role === ROLE_COACH){
            return [
                'tabs' => [
                    'Profile' => [
                        'null', ['action' => 'coachProfile', $user->id, 'controller' => 'AppUsers'], true
                    ],
                    'Topics' => [
                        'null', ['action' => 'coachTopics', $user->id, 'controller' => 'Topics'], true
                    ],
                    'My Sessions' => [
                        'null', ['action' => 'approved', $user->id, 'controller' => 'Sessions'], false
                    ]
                ],
                'user' => $user
            ];
        } else {
            return [       
                'tabs' => [
                    'Profile' => [
                        'null', ['action' => 'userProfile', $user->id, 'controller' => 'AppUsers'], true
                    ],
                    'My Sessions' => [
                        'null', ['action' => 'approved', $user->id, 'controller' => 'Sessions'], false
                    ],
                    'Payment Information' => [
                        'null', ['action' => 'cards', $user->id, 'controller' => 'PaymentInfos'], false
                    ]
                ],
                'user' => $user
            ];
        }
    }
}   