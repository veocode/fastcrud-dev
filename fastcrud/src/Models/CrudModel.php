<?php

namespace Veocode\FastCRUD\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Veocode\FastCRUD\Fields\FieldInt;


abstract class CrudModel extends Model {

    public static $orderBy = 'id';
    public static $orderTo = 'desc';

    protected $clearOnCopy = [];
    protected $copyRelations = []; // relation => foreign id field

    public function getTitle(){
        return $this->title ?? '';
    }

    public function getCopy(){
        $copy = $this->replicate();
        if (!empty($this->clearOnCopy)) {
            foreach ($this->clearOnCopy as $field) {
                $copy->setAttribute($field, null);
            }
        }

        return $copy;
    }

    public function copyRelationsTo($newItem) {
        $childItemsCopied = 0;
        if (empty($this->copyRelations)) {
            return $childItemsCopied;
        }

        $this->load(array_keys($this->copyRelations));
        $now = Carbon::now();

        foreach ($this->copyRelations as $relationName => $foreignIdField) {
            $items = $this->{$relationName};

            foreach($items as $childItem) {
                $childItem->getCopy()->fill([
                    $foreignIdField => $newItem->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->save();

                $childItemsCopied++;
            }
        }

        return $childItemsCopied;
    }

    public function deleteHasManyRelation($relationName) {
        $items = $this->{$relationName};
        if (empty($items)) {
            return;
        }

        foreach ($items as $item){
            $item->delete();
        }
    }

    public static function getGridItems($perPage = 10) {
        $query = self::query();
        return $query->paginate($perPage);
    }

    public static function getFields() : array {
        return [
            FieldInt::make('id', 'ID')->hideInForm()->virtual(),
        ];
    }

    public static function getFieldByName($name) {
        foreach(static::getFields() as $field){
            if ($field->getName() == $name) {
                return $field;
            }
        }
        return null;
    }

    public static function getFilterableFields() : array {
        $filterableFields = [];
        foreach(static::getFields() as $field){
            if ($field->isFilterable()) {
                $filterableFields[] = $field;
            }
        }
        return $filterableFields;
    }

    public static function hasFilterableFields() : bool {
        $filterableFields = [];
        foreach(static::getFields() as $field){
            if ($field->isFilterable()) {
                return true;
            }
        }
        return false;
    }

    public static function getValidationRules($isUpdating = false) : array {
        $rules = [];
        foreach(static::getFields() as $field){
            $rule = $field->getValidationRule($isUpdating);
            if ($rule) {
                $rules[$field->getName()] = $rule;
            }
        }
        return $rules;
    }

    public static function getValidationFieldTitles(){
        $titles = [];
        foreach(static::getFields() as $field){
            $titles[$field->getName()] = '<em>'. trim($field->getFormTitle(), ':') . '</em>';
        }
        return $titles;
    }

    public static function getFlatList($isWithEmptyOption = false, $titleAttribute = 'title') {
        $items = static::all()->pluck($titleAttribute, 'id')->toArray();
        if (!$items) { return []; }
        if ($isWithEmptyOption) {
            $items = ['' => ''] + $items;
        }
        return $items;
    }

    public function scopeEnabled($query) {
        return $query->where('is_enabled', '=', true);
    }

    public function toggle($fieldName) {
        if (!isset($this->{$fieldName})) {
            return false;
        }

        $this->{$fieldName} = !$this->{$fieldName};
        $this->save();

        return $this->{$fieldName};
    }

}
