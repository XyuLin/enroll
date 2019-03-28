<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\admin\model\Enroll;
use think\Validate;

/**
 * 首页接口
 */
class Index extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     * 
     */
    public function index()
    {
        $this->success('请求成功');
    }


    public function signUp()
    {
        // 获取数据
        // 判断手机号是否唯一
        $model = new Enroll();
        $mobile = $this->request->param('mobile/s');
        if (!Validate::regex($mobile, "^1\d{10}$")) {
            $this->error('手机号不规范');
        }

        $is_exist = $model->where('mobile',$mobile)->find();
        if($is_exist) {
            $this->error('不可重复报名!');
        }

        $count = $model->count('id');
        if($count == 30) {
            $this->error('活动报名人数已满!');
        }

        $params = [
            'username'   => $this->request->param('username/s'),
            'mobile'     => $mobile,
            'createtime' => time(),
            'houses'     => $this->request->param('houses/s'),
            'premises'   => '润江城'
        ];

        
        $result = $model->create($params);

        if($result) {
            $this->success('报名成功!');
        } else {
            $this->error('报名失败! 请重新提交!');
        }
    }

}
