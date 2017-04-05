<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;
use App\Controller\AppUsersController;

class RatingHelper extends Helper
{
    const MAX_RATING = 5;
    const TOTAL_PIXELS = 53;

    /**
     * retrun the respective tabs
     *
     * @param user user entity
     * @return array
     */
    public function rating($rating)
    {
        return (($rating / self::MAX_RATING) * self::TOTAL_PIXELS);
    }
}   