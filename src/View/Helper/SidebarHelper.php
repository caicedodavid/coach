<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;
use App\Controller\AppUsersController;

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
        if ($user->role === ROLE_COACH) {
            return [
                'tabs' => [
                    'profile' => [
                        'isActive' => AppUsersController::PROFILE_TABS_PROFILE === $tab,
                        'url' => ['action' => 'coachProfile', $user->id, 'controller' => 'AppUsers'],
                        'isAuthLink' => false,
                        'title' => __('Profile')
                    ],
                    'topics' => [
                        'isActive' => AppUsersController::PROFILE_TABS_TOPICS === $tab,
                        'url' => ['action' => 'coachTopics', $user->id, 'controller' => 'Topics'],
                        'isAuthLink' => false,
                        'title' => __('Topics')
                    ],
                    'sessions' => [
                        'isActive' => AppUsersController::PROFILE_TABS_SESSIONS === $tab,
                        'url' => ['action' => 'approved', $user->id, 'controller' => 'Sessions'],
                        'isAuthLink' => true,
                        'title' => __('My Sessions')
                    ],
                    'payments' => [
                        'isActive' => AppUsersController::PROFILE_TABS_LIABILITIES === $tab,
                        'url' => ['action' => 'paidSessions', $user->id, 'controller' => 'Sessions'],
                        'isAuthLink' => true,
                        'title' => __('Payments')
                    ],
                    'agenda' => [
                        'isActive' => AppUsersController::PROFILE_TABS_AGENDA === $tab,
                        'url' => ['action' => 'agenda', $user->id, 'controller' => 'AppUsers'],
                        'isAuthLink' => true,
                        'title' => __('My Agenda')
                    ],
                ],
                'user' => $user
            ];
        } else {
            return [       
                'tabs' => [
                    'profile' => [
                        'isActive' => AppUsersController::PROFILE_TABS_PROFILE === $tab,
                        'url' => ['action' => 'userProfile', $user->id, 'controller' => 'AppUsers'],
                        'isAuthLink' => false,
                        'title' => __('Profile')
                    ],
                    'sessions' => [
                        'isActive' => AppUsersController::PROFILE_TABS_SESSIONS === $tab,
                        'url' => ['action' => 'approved', $user->id, 'controller' => 'Sessions'],
                        'isAuthLink' => true,
                        'title' => __('My Sessions')
                    ],
                    'payment_infos' => [
                        'isActive' => AppUsersController::PROFILE_TABS_PAYMENT_INFOS === $tab,
                        'url' => ['action' => 'cards', $user->id, 'controller' => 'PaymentInfos'],
                        'isAuthLink' => true,
                        'title' => __('Payment Information')
                    ],
                    'purchases' => [
                        'isActive' => AppUsersController::PROFILE_TABS_PURCHASES === $tab,
                        'url' => ['action' => 'purchases', $user->id, 'controller' => 'Payments'],
                        'isAuthLink' => true,
                        'title' => __('My Purchases')
                    ],
                    'agenda' => [
                        'isActive' => AppUsersController::PROFILE_TABS_AGENDA === $tab,
                        'url' => ['action' => 'agenda', $user->id, 'controller' => 'AppUsers'],
                        'isAuthLink' => true,
                        'title' => __('My Agenda')
                    ],
                ],
                'user' => $user
            ];
        }
    }
}   