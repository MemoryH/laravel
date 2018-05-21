<?php
namespace App\Http\Model\Rbac;


use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission{

    /**
     * Get the relationships for the entity.
     *
     * @return array
     */
    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }
}