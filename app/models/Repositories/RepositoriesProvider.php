<?php
/**

 */

namespace SzentirasHu\models\Repositories;


use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('SzentirasHu\Models\Repositories\BookRepository', 'SzentirasHu\Models\Repositories\BookRepositoryEloquent');
        $this->app->bind('SzentirasHu\Models\Repositories\TranslationRepository', 'SzentirasHu\Models\Repositories\TranslationRepositoryEloquent');
    }
}