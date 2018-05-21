<?php
namespace App\Http\Model\Group;

use Baum\Node;

class GroupModel extends Node
{

    /**
     * Get the relationships for the entity.
     *
     * @return array
     */
    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }
    protected $table = 'permission_group';

    // 'parent_id' column name
    protected $parentColumn = 'parent_id';
    // 'lft' column name
    protected $leftColumn = 'left';
    // 'rgt' column name
    protected $rightColumn = 'rgt';
    // 'depth' column name
    protected $depthColumn = 'depth';


}