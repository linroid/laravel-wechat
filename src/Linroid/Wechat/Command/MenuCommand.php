<?php
namespace Linroid\Wechat\Command;

use Config;
use File;
use Illuminate\Console\Command;
use Linroid\Wechat\WechatApi;

class MenuCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wechat:menu';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '微信菜单管理';

    /**
     * Create a new command instance.
     *
     * @return \Linroid\Wechat\Command\MenuCommand
     */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $wechat = WechatApi::create();
        $menu_json = File::get(app_path().'/wechat_menu.json');
        if($wechat->setMenu($menu_json)){
            $this->info('设置菜单成功!');
        }else{
            $this->error('设置菜单失败:(');
        }
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
        return array();
	}

}
