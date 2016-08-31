<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;

class ImgHelper extends Helper
{
    public $helpers = ['Image','Html'];
    /**
     * Display image method
     *
     * @param image UserImage
     * @param size string
     * @return the Users image or blank image
     */
    public function display($image,$size)
    {
    	if($image){
    		return $this->Image->display($image, $size);
    	}
    	return $this->Html->image("blank_".$size.".jpg",['alt' => 'CakePHP']);     
    }
}