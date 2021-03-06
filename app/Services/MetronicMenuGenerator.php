<?php

namespace App\Service;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lavary\Menu\Builder;
use Lavary\Menu\Item;
use Menu as LavaryMenu;

class MetronicMenuGenerator extends MenuGenerator
{
    /**
     * @var MenuService
     */
    protected $menuService;

    /**
     * SublimeMenuGenerator constructor.
     */
    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    function generateMenu(Request $request)
    {
        $user = Auth::user();
        $menus = $this->getMenuByUserId($user->id);

        $menu = LavaryMenu::make('MyNavBar', function($theMenu) use ($menus, $request) {
            foreach ($menus as $menu) {
                // get the 1st level
                if ($menu->parent_id == '0') {
                    $theMenu->add($menu->display)
                        ->nickname($menu->name)
                        ->data(['icon_class' => $menu->icon_class, 'menu_link' => $menu->link]);
                } else {
                    $parentMenu = $this->menuService->getMenuById($menu->parent_id);
                    $theMenu->get($parentMenu->name)
                        ->add($menu->display)
                        ->nickname($menu->name)
                        ->data(['icon_class' => $menu->icon_class, 'menu_link' => $menu->link]);
                }

                if ($request->is($menu->link)) {
                    $theMenu->get($menu->name)->data('activated', 'active');
                    $this->activateItem($theMenu, $menu->id);
                }
            }
        });

        // var_dump($menu);

        return $menu;
    }

    private function getMenuByUserId($userId)
    {
        return $this->menuService->getMenuByUser($userId);
    }

    function activateItem(Builder $item, $menuId, $first = true)
    {
        $menu = $this->menuService->getMenuById($menuId);

        if (!$first) {
            $item->get($menu->name)->data('activated', 'active');
        }

        if ($menu->parent_id != '' && $menu->parent_id != '0') {
            $this->activateItem($item, $menu->parent_id, false);
        }
    }
}
