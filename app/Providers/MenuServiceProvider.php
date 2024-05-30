<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;


class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {

    view()->composer('*', function ($view) 
    {

      if (auth()->check()) {
        $menu = '';

        if(auth()->user()->hasRole('admin')){
            $menu = $this->get_menu('admin');
        }
        if(auth()->user()->hasRole('user')){
            $menu = $this->get_menu('user');
        }

        

        $view->with([
          'menuData' => $menu ,
        ]);  
      }

    }); 

  }


  private function get_menu($role){
      $verticalMenuJson = file_get_contents(base_path('resources/menu/'.$role.'/verticalMenu_'.$role.'.json'));
      $verticalMenuData = json_decode($verticalMenuJson);
      $horizontalMenuJson = file_get_contents(base_path('resources/menu/'.$role.'/horizontalMenu_'.$role.'.json'));
      $horizontalMenuData = json_decode($horizontalMenuJson);

      return [$verticalMenuData, $horizontalMenuData];
    
  }
}
