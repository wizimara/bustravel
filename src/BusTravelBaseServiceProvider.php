<?php

namespace glorifiedking\BusTravel;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Route;

class BusTravelBaseServiceProvider extends ServiceProvider
{
    /**
     * bootstrap package.
     */
    public function boot(Dispatcher $events)
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
        $this->registerRoutes();
        $this->registerResources();
        //create menu
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $settings_menu = [
                //        [
                    'text'    => 'Settings',
                    'url'     => '#',
                    'icon'    => 'fa fa-cog',
                    'submenu' => [
                        [
                            'text' => 'General Settings',
                            'url'  => route('bustravel.general_settings'),
                            'icon' => 'fa fa-cog',
                            'can' => 'Manage BT General Settings',

                        ],

                        [
                            'text'    => 'Company Settings',
                            'url'     => route('bustravel.company_settings'),
                            'icon'    => 'fa fa-cog',
                            'can' => 'Manage BT Operator Settings',
                            'submenu' => [
                              [
                                  'text' => 'Booking Custom Fields',
                                  'url'  => route('bustravel.company_settings.fields'),
                                  'icon' => 'clipboard',
                              ],
                            ],

                        ],
                        [
                            'text' => 'Stations',
                            'url'  => route('bustravel.stations'),
                            'icon' => 'fa fa-map-marker-alt',
                            'can' => 'Manage BT Stations',
                        ],

                    ],

            ];
            $operations_menu = [
            'text'    => 'Operations',
            'url'     => '#',
            'icon'    => 'fa fa-cube',
            'submenu' => [
                [
                    'text' => 'Operators',
                    'url'  => route('bustravel.operators'),
                    'icon' => 'fa fa-list',
                    'can' => 'Manage BT Operators',

                ],

                [
                    'text' => 'Buses',
                    'url'  => route('bustravel.buses'),
                    'icon' => 'fa fa-bus',
                    'can' => 'View BT Buses',

                ],
                [
                    'text' => 'Routes',
                    'url'  => route('bustravel.routes'),
                    'icon' => 'fa fa-route',
                    'can' => 'View BT Routes',

                ],
                [
                    'text' => 'Drivers',
                    'url'  => route('bustravel.drivers'),
                    'icon' => 'fa fa-user',
                    'can' => 'View BT Drivers',

                ],
                [
                    'text' => 'Bookings',
                    'url'  => route('bustravel.bookings'),
                    'icon' => 'fa fa-money-check',
                    'can' => 'View BT Bookings',
                ],
                [
                    'text' => 'Driver Manifest',
                    'url'  => route('bustravel.bookings.manifest'),
                    'icon' => 'fa fa-money-check',
                    'can' => 'View BT Bookings',
                ],

            ],

            ];
            $reports_menu = [
            'text'    => 'Reports',
            'url'     => '#',
            'icon'    => 'fa fa-list',
            'can' => 'View BT Reports',
            'submenu' => [
                    [
                        'text' => 'Sales',
                        'url'  => route('bustravel.reports.sales'),
                        'icon' => 'fa fa-money-bill',
                        'can' => 'View BT Sales Reports',

                    ],

                    [
                        'text' => 'Profitable Routes',
                        'url'  => route('bustravel.reports.profitroute'),
                        'icon' => 'fa fa-route',

                    ],
                    [
                        'text' => 'Passenger Traffic',
                        'url'  => route('bustravel.reports.traffic'),
                        'icon' => 'fa fa-traffic-light',

                    ],
                    [
                        'text' => 'Locations',
                      //  'url'  => route('bustravel.reports.locations'),
                        'icon'=> 'fa fa-search-location',

                    ],
                    [
                        'text' => 'Bookings',
                        'url'  => route('bustravel.reports.bookings'),
                        'icon' => 'fa fa-money-check',

                    ],
                    [
                        'text' => 'Cashier Report',
                        'url'  => route('bustravel.bookings.cashier.report'),
                        'icon' => 'fa fa-money-check',

                    ],


                ],

            ];
            $users_menu = [

                        'text'    => 'User and Profile',
                        'url'     => '#',
                        'icon'    => 'fas fa-fw fa-users',
                        'submenu' => [
                            [
                                'text' => 'Profile',
                                'url'  => route('bustravel.users.changepassword'),
                                'icon' => 'fas fa-fw fa-address-card',

                            ],
                            [
                                'text'    => 'Users',
                                'url'     => route('bustravel.testdefault'),
                                  'icon'  => 'fa fa-users',
                                  'can' => 'View BT Users',
                                'submenu' => [
                                  [
                                      'text' => 'User Accounts',
                                      'url'  => route('bustravel.users'),
                                      'icon' => 'fa fa-lock',
                                      'can' => 'View BT Users',

                                  ],
                                  [
                                      'text' => 'Roles',
                                      'url'  => route('bustravel.users.roles'),
                                      'icon' => 'fa fa-lock',
                                      'can' => 'Manage BT Permissions',

                                  ],
                                  [
                                      'text' => 'Permissions',
                                      'url'  => route('bustravel.users.permissions'),
                                      'icon' => 'fa fa-lock',
                                      'can' => 'Manage BT Permissions',

                                  ],
                                ],
                            ],

                        ],
            //        ]

                ];
                $help_menu = [

                            'text'    => 'Help',
                            'url'     => '#',
                            'icon'    => 'fas fa-fw fa-flag',
                            'submenu' => [
                                [
                                    'text' => 'Faqs',
                                    'url'  => route('bustravel.faqs'),
                                    'icon' => 'fas fa-fw fa-flag',
                                ],
                            ],

                    ];

            $event->menu->add($settings_menu);
            $event->menu->add($operations_menu);
            $event->menu->add($reports_menu);
            $event->menu->add($users_menu);
            $event->menu->add($help_menu);
        });
    }

    /**
     * register package.
     */
    public function register()
    {
        $this->app->register('JeroenNoten\LaravelAdminLte\AdminLteServiceProvider');
        $this->app->register('Spatie\Permission\PermissionServiceProvider');
        //$this->app->register("Barryvdh\DomPDF\ServiceProvider");
    }

    private function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bustravel');
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/bustravel.php' => config_path('bustravel.php'),
        ], 'bustravel-config');
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/glorifiedking'),
        ], 'bustravel-assets');
        $this->publishes([
        __DIR__.'/../database/test_migrations/' => database_path('migrations'),
    ], 'bustravel-migrations');
        $this->publishes([
    __DIR__.'/../database/factories/' => database_path('factories'),
], 'bustravel-factories');
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    private function routeConfiguration()
    {
        return [
            'prefix'    => config('bustravel.path', 'transit'),
            'namespace' => 'glorifiedking\BusTravel\Http\Controllers',
        ];
    }
}
