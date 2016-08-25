<?php
namespace App\Model\Table;

use Burzum\FileStorage\Model\Table\ImageStorageTable;

class UserImagesTable extends ImageStorageTable 
{

    public function uploadImage($userId, $entity) 
    {

        $entity = $this->patchEntity($entity, [
            'adapter' => 'Local',
            'model' => 'UserImage',
            'foreign_key' => $userId
        ]);
        return $this->save($entity);
    }
}

