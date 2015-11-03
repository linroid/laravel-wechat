<?php
namespace Linroid\Wechat\Command;

use Config;
use File;
use Illuminate\Console\Command;
use Linroid\Wechat\WechatApi;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class HandlerCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wechat:handler';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '创建用于处理微信消息的Handler';

    /**
     * Create a new command instance.
     *
     * @return \Linroid\Wechat\Command\HandlerCommand
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
        $name = $this->argument('name');
	}

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'Handler类名'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
        );
    }

}
