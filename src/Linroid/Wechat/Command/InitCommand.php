<?php
namespace Linroid\Wechat\Command;

use Config;
use File;
use Illuminate\Console\Command;
use Linroid\Wechat\WechatApi;

class InitCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wechat:init';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '微信初始化配置命令';

    /**
     * Create a new command instance.
     *
     * @return \Linroid\Wechat\Command\InitCommand
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
        $handlers_dir = app_path().'/handlers';
        $menu_json_file = app_path().'/wechat_menu.json';
        $wechat_loader_file = app_path().'/wechat.php';

        if(!File::exists($menu_json_file)){
            $wechat = WechatApi::create();
            $menu = $wechat->getMenu();
            $json = json_encode($menu, JSON_UNESCAPED_UNICODE);
            File::put($menu_json_file, $json);
            $this->info("获取菜单成功,已保存到:\n{$menu_json_file} :)");
        }
        if(!File::exists($handlers_dir)){
            File::makeDirectory($handlers_dir);
            $this->info("创建目录:\n{$handlers_dir}");
        }
        if(!File::exists($wechat_loader_file)){
            File::put($wechat_loader_file, '');
            $this->info("创建文件:\n{$wechat_loader_file}");
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
