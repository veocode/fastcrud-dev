<?php

namespace App\Models;

use Veocode\FastCRUD\Field;
use Kalnoy\Nestedset\NodeTrait;


abstract class TreeCrudModel extends CrudModel {

    use NodeTrait;

    public function getLftName() {
        return 'ns_left';
    }

    public function getRgtName() {
        return 'ns_right';
    }

    public function getParentIdName() {
        return 'parent_id';
    }

    public function getIcon() {
        return 'folder-outline';
    }

    public function isFolder() {
        return false;
    }

    public static function getRootNodeId() {
        return 1;
    }

    public static function getTree($rootId = false) {
        if (!$rootId) {
            $rootId = static::getRootNodeId();
        }
        return static::enabled()->defaultOrder()->get()->toTree($rootId);
    }

    public static function getFlatTree() : array {
        $nodes = static::withDepth()->defaultOrder()->descendantsAndSelf(static::getRootNodeId());
        $tree = [];
        if ($nodes) {
            foreach ($nodes as $node) {
                $tree[$node['id']] = str_repeat('--', $node->depth) . ' ' . $node->title;
            }
        }
        return $tree;
    }

    public static function getGridItems($perPage = 10) {
        return self::withDepth()->defaultOrder()->descendantsOf(static::getRootNodeId());
    }

}
