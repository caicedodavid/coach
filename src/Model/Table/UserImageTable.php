<?php
namespace App\Model\Table;

use Burzum\FileStorage\Model\Table\ImageStorageTable;

class UserImageTable extends ImageStorageTable 
{

    public function uploadImage($userId, $entity) 
    {

        $entity = $this->patchEntity($entity, [
            'adapter' => 'Local',
            'model' => 'AppUsers',
            'foreign_key' => $userId
        ]);
        return $this->save($entity);
    }
}