<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;

class ImgHelper extends Helper
{
    public $helpers = ['Image','Html'];
    /**
     * Display profile image method
     *
     * @param image UserImage
     * @param size string
     * @return the Users image or blank image
     */
    public function display($image,$size,array $options=[])
    {
    	if($image){
    		return $this->Image->display($image, $size,$options);
    	}
        $options['alt'] = 'CakePHP';
    	return $this->Html->image("blank_".$size.".jpg",$options);     
    }
    /**
     * Display image method
     *
     * @param image UserImage
     * @param size string
     * @return the Users image or blank image
     */
    public function displayImage($image,$size,array $options=[])
    {
        if($image){
            return $this->Image->display($image, $size,$options);
        }
        $options['alt'] = 'CakePHP';
        return $this->Html->image("blank2_".$size.".jpg",$options);     
    }
}