<?php
namespace App\Model\Table;

use Burzum\FileStorage\Model\Table\ImageStorageTable;

class TopicImageTable extends ImageStorageTable 
{

    public function uploadImage($topicId, $entity) 
    {

        $entity = $this->patchEntity($entity, [
            'adapter' => 'Local',
            'model' => 'Topics',
            'topic_id' => $topicId
        ]);
        return $this->save($entity);
    }
}