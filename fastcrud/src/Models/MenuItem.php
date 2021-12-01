<?php

namespace App\Models;

use Veocode\FastCRUD\Fields\FieldFlag;
use Veocode\FastCRUD\Fields\FieldInt;
use Veocode\FastCRUD\Fields\FieldList;
use Veocode\FastCRUD\Fields\FieldString;
use Illuminate\Support\Facades\Route;

class MenuItem extends TreeCrudModel {

    protected $guarded = [];

    public function getIcon() {
        return $this->icon;
    }

    public function isFolder() {
        return $this->depth == 1;
    }

    public static function getFlatTree() : array {
        $nodes = static::withDepth()->defaultOrder()->get();
        $tree = [];
        if ($nodes) {
            foreach ($nodes as $node) {
                $tree[$node['id']] = str_repeat('--', $node->depth) . ' ' . $node->title;
            }
        }
        return $tree;
    }

    public static function getGridItems($perPage = 10) {
        return self::withDepth()->defaultOrder()->hasParent()->get();
    }

    public static function getRouteList(){
        $list = [];
        foreach(Route::getRoutes() as $route){
            if (in_array('POST', $route->methods)) { continue; }
            if ($route->hasParameters()) { continue; }
            if (strstr($route->uri, '{')) { continue; }
            $list[$route->getName()] = $route->getName();
        }
        asort($list);
        return $list;
    }

    public static function getFields() : array {
        return [
            FieldInt::make('id', 'ID')
                ->hideInForm()
                ->hideInGrid()
                ->virtual(),

            FieldList::make('parent_id', 'Родительский пункт')
                ->hideInGrid()
                ->setItems(self::getFlatTree())
                ->validate('required'),

            FieldString::make('title', 'Заголовок пункта меню')
                ->validate('required'),

            FieldString::make('icon', 'Иконка пункта меню')
                ->setFormHint('Имя иконки указывать без префикса <b>mdi-</b><br><a href="https://materialdesignicons.com/" target="_blank">Полный список и поиск иконок</a>')
                ->hideInGrid(),

            FieldList::make('route', 'Маршрут пункта меню')
                ->setItems(['url' => 'Внешний URL'] + static::getRouteList())
                ->validate('required'),

            FieldString::make('url', 'Внешний URL')
                ->setFormHint('Указывать с протоколом, например <code>https://yandex.ru</code><br>Подстановки: <code>{HTTP_HOST}</code> - текущий хост')
                ->showWhen('route', 'url')
                ->validate('required_if:route,url'),

            FieldFlag::make('is_enabled', 'Вкл')
                ->setFormTitle('Пункт меню включен')
                ->toggleableInGrid(),

            FieldFlag::make('is_new_tab', 'Новая вкладка')
                ->setFormTitle('Открывать в новой вкладке браузера')
                ->hideInGrid(),

            FieldFlag::make('is_admin_only', 'Только админ')
                ->setFormTitle('Пункт меню доступен только администраторам')
                ->setFormHint('Необязательно включать эту опцию, если она включена на любом из родителей')
                ->toggleableInGrid(),
        ];
    }

}
