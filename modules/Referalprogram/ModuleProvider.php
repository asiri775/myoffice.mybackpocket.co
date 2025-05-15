<?php
namespace Modules\Referalprogram;
use Modules\Core\Helpers\SitemapHelper;
use Modules\ModuleServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(SitemapHelper $sitemapHelper){

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        return [
            'referal'=>[
                "position"=>51,
                'url'        => route('referal-program.admin.index'),
                'title'      => __('Referal'),
                'icon'       => 'fa fa-ticket',
                'permission' => 'coupon_view',
            ],
        ];
    }
}