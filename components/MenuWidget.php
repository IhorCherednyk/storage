<?php

namespace app\components;

use yii\base\Widget;
use app\models\Category; // покдлючаем модель таблицы для выборки данных

class MenuWidget extends Widget {

    public $tpl; // указываем тот ключ который мы указуем в виде  <?= MenuWidget::widget(['tpl' => 'menu'])
    public $data; // свойства хранения записей категории из базы данных
    public $tree; // хранится результат работы функции в который строится масив дерева
    public $menuHtml; // готовый html в заисимости от шаблона $this->tpl

    public function init() {  // создаем инит функцию
        parent::init();  // обязательный метод
        if ($this->tpl === null) { //Проверяем если в наш  tpl ничего не пришло то по умолчанию он menu
            $this->tpl = 'menu';
        }
        $this->tpl .= '.php'; // иначе задаем то что пришло что то пришло например <?= MenuWidget::widget(['tpl' => 'blabla'])
    }

    public function run() {
        $this->data = Category::find()->indexBy('id')->asArray()->all(); // sql запрос к базе  запрос гвороит (верни мне массивы массивов где (indexBy('id') - ключи масива будут совпадать с индикаторами id ))
       
        $this->tree = $this->getTree(); // создаем дерево масивов
        
        $this->menuHtml = $this->getMenuHtml($this->tree);
        
        return $this->menuHtml; // Поитогу возвращаем то что нам пришло <?= MenuWidget::widget(['tpl' => 'blabla'])
        
    }

    protected function getTree() { // Функция берет и свзяывает как дерево данные которые мы получили из таблицы 
        $tree = [];
        foreach ($this->data as $id => &$node) {
            
            if (!$node['parent_id']){
                $tree[$id] = &$node;
                
            }
            else
                $this->data[$node['parent_id']]['childs'][$node['id']] = &$node;
        }
       
        return $tree;
    }

    protected function getMenuHtml($tree) {
        $str = '';
        foreach ($tree as $category) {
             D($category);
            $str .= $this->catToTemplate($category);
        }
        
        return $str;
    }

    protected function catToTemplate($category) {
        ob_start(); // используем буферизацию которая записует html 
        include __DIR__ . '/menu_tpl/' . $this->tpl;
        
        return ob_get_clean(); // этот метод возвращает забуферизированый html
    }

}
