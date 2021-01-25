<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use App\Model\MenuItems\MenuItems;

class MenuItemsPresenter extends BasePresenter
{

    private $menuItemsRepository;

    public function __construct(MenuItems $menuItemsRepository)
    {
        $this->menuItemsRepository = $menuItemsRepository;
    }

    public function startup()
    {
        parent::startup();
        echo($this->buildMenu($this->menuItemsRepository->getAll()));
    }

    public function buildMenu($menuItems, $parent = 0)
    {
        $result = "<ul style='position:'>";
        foreach ($menuItems as $item) {
            if ($item['parentId'] == $parent) {
                $result .= "<li><a href='#'>{$item['itemName']}";
                if ($this->hasChildren($menuItems, $item['id']))
                    $result .= $this->buildMenu($menuItems, $item['id']);
                $result .= "</a></li>";
            }
        }
        $result .= "</ul>";

        return $result;
    }

    public function hasChildren($items, $id)
    {
        foreach ($items as $item) {
            if ($item['parentId'] == $id)
                return true;
        }
    }

    protected function createComponentAddMenuItem()
    {
        $form = new Ui\Form;
        $form->addText('itemName', 'Název položky');
        $form->addSelect('parentId', 'Nadřazená položka', [
            '0' => 'Žádná',
            $this->getSelectMenuItems()
        ]);
        $form->addSubmit('send', '+');

        $form->onSuccess[] = [$this, 'addMenuItemSucceeded'];

        return $form;
    }

    private function getSelectMenuItems()
    {
        $items = [];

        foreach ($this->menuItemsRepository->getAll() as $item) {
            $items[$item->id] = $item->itemName;
        }

        return $items;
    }

    public function addMenuItemSucceeded($form, array $values)
    {
        $this->menuItemsRepository->instertMenuItem($values);
        $this->redirect('Homepage:default');
    }
}