<?php
namespace Linroid\Wechat;

use File;
use Illuminate\Support\ServiceProvider;
use Linroid\Wechat\Command\InitCommand;
use Linroid\Wechat\Command\MenuCommand;
use Linroid\Wechat\Command\TestCommand;

class WechatServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('Linroid/WechatApi', 'wechat');
        include __DIR__.'/../../filters.php';
        include __DIR__.'/../../routes.php';
        include __DIR__.'/../../functions.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind("wechat.command.init", function()
        {
            return new InitCommand();
        });
        $this->app->bind("wechat.command.menu", function()
        {
            return new MenuCommand();
        });
        $this->app->bind("wechat.command.test", function()
        {
            return new TestCommand();
        });
        $this->commands('wechat.command.init');
        $this->commands('wechat.command.menu');
        $this->commands('wechat.command.test');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array(
            'menu.command'
        );
	}

}
