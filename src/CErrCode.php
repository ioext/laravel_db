<?php

namespace ioext\laravel_db;

class CErrCode
{
    const SUCCESS = 200;

    const SUCCESS_NOTING_DATA   = 1001;//获取成功,但没有数据
    const PARAM_ERROR           = 1002;//参数错误
    const INSERT_FALSE          = 1003;//新增错误
    const DELETE_FALSE          = 1004;//删除失败
    const UPDATE_FALSE          = 1005;//更新失败
}