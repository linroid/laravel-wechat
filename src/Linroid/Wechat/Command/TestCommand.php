<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-7-26
 * Time: 下午3:31
 */

namespace Linroid\Wechat\Command;
use Config;
use DB;
use Illuminate\Console\Command;
use Linroid\Wechat\Push\MusicPush;
use Linroid\Wechat\Push\TextPush;
use Linroid\Wechat\WechatApi;

class TestCommand extends Command{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'wechat:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '本地测试';

    /**
     * Create a new command instance.
     *
     * @return \Linroid\Wechat\Command\TestCommand
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
        $this->line('当前access_token:<info>'.$wechat->access_token.'</info>');
        /**
         * @var \Business $business
         */
        $business = \Business::find(1);
        DB::transaction(function() use($wechat, $business){
            $wechatQrcode = new \WechatQrcode();
            $wechatQrcode->save();

            $qrcode = $wechat->createQrcode($wechatQrcode->scene_id);
            $wechatQrcode->ticket = $qrcode['ticket'];
            $wechatQrcode->type = 0;
            $wechatQrcode->expire_seconds = $qrcode['expire_seconds'];
            $wechatQrcode->save();

            $consumption = new \Consumption();
            $consumption->scene_id = $wechatQrcode->scene_id;
            //test
            $consumption->price = 100;
            $consumption->business_id = $business->business_id;
            $consumption->save();

            $url = $wechat->getQrcodeUrl($qrcode['ticket']);
            $this->line('<info>URL: </info>'.$url);

        });
//        $push = new MusicPush('o0O2ft0DaiwTkpl6Y-eSlWTOzBHE');
//        $push->setMusic('四川 成都', '来自你是个好人啦,点击查看详情', 'http://xtuers.com', 'http://xtuers.com/question', 'MrsLzpypMg3fv-0ihK_UEvIrSC9KdlT6s7MXbscGNlLBtNAHkmLoTz5D1-qFlEMY');
//        $r = $wechat->sendPush($push);
//        if(!$r){
//            print_r($r);
//            $this->error('失败');
//        }else{
//            print_r($r);
//        }

    }
    private  function getDaily(){
        $weather = geo2weather(40.403725, 115.518066);
        $weatherData = $weather['weather_data'][0];
        $daily_sentence = daily_sentence();
        $content = <<<CONTENT
{$weather['currentCity']} {$weatherData['date']}
今天的天气： {$weatherData['weather']}
pm25:{$weather['pm25']}
风向:{$weatherData['wind']}
温度:{$weatherData['temperature']}

{$daily_sentence['content']}
{$daily_sentence['note']}

   --{$daily_sentence['translation']}

CONTENT;
        echo $content;
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
