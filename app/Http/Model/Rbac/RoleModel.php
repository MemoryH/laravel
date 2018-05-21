<?php
namespace App\Http\Model\Rbac;

use Zizaco\Entrust\EntrustRole;

class RoleModel extends EntrustRole{

    /**
     * Get the relationships for the entity.
     *
     * @return array
     */
    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }

    /**
     * update
     * @param array $PermissionsId
     */
    public function givePermissionsTo(array $PermissionsId){
        $this->detachPermissions($this->perms);
        $this->attachPermissionToId($PermissionsId);
    }

    /**
     * Attach multiple $PermissionsId to a user
     *
     *
     */
    public function attachPermissionToId($PermissionsId)
    {
        foreach ($PermissionsId as $pid) {
            $this->attachPermission($pid);
        }
    }
}