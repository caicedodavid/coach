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
    public function tabs($user, $tab =  null)
    {
        if($user->role === ROLE_COACH) {
            return [
                'tabs' => [
                    'profile' => [
                        'isActive' => PROFILE_TABS_PROFILE === $tab,
                        'url' => ['action' => 'coachProfile', $user->id, 'controller' => 'AppUsers'],
                        'authLink' => false,
                        'title' => __('Profile')
                    ],
                    'topics' => [
                        'isActive' => PROFILE_TABS_TOPICS === $tab,
                        'url' => ['action' => 'coachTopics', $user->id, 'controller' => 'Topics'],
                        'authLink' => false,
                        'title' => __('Topics')
                    ],
                    'sessions' => [
                        'isActive' => PROFILE_TABS_SESSIONS === $tab,
                        'url' => ['action' => 'approved', $user->id, 'controller' => 'Sessions'],
                        'authLink' => true,
                        'title' => __('My Sessions')
                    ],
                    'payments' => [
                        'isActive' => PROFILE_TABS_LIABILITIES === $tab,
                        'url' => ['action' => 'paidSessions', $user->id, 'controller' => 'Sessions'],
                        'authLink' => true,
                        'title' => __('Payments')
                    ],
                ],
                'user' => $user
            ];
        } else {
            return [       
                'tabs' => [
                    'profile' => [
                        'isActive' => PROFILE_TABS_PROFILE === $tab,
                        'url' => ['action' => 'userProfile', $user->id, 'controller' => 'AppUsers'],
                        'authLink' => false,
                        'title' => __('Profile')
                    ],
                    'sessions' => [
                        'isActive' => PROFILE_TABS_SESSIONS === $tab,
                        'url' => ['action' => 'approved', $user->id, 'controller' => 'Sessions'],
                        'authLink' => true,
                        'title' => __('My Sessions')
                    ],
                    'payment_infos' => [
                        'isActive' => PROFILE_TABS_PAYMENT_INFOS === $tab,
                        'url' => ['action' => 'cards', $user->id, 'controller' => 'PaymentInfos'],
                        'authLink' => true,
                        'title' => __('Payment Information')
                    ],
                ],
                'user' => $user
            ];
        }
    }
}   