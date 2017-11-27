<?php

namespace ioext\laravel_db;

use Illuminate\Database\Eloquent\Model;

class CQueryBase extends Model
{
    public static function getsAll( $arrField = [], & $arrRtn= [], & $sDesc = "未知错误" )
    {
        $nErrCode = CErrorCode::SUCCESS;

        if( ! is_array( $arrField ) || count( $arrField ) <= 0 )
        {
            $arrField = ['*'];
        }

        $arrRtn = self::query()->get( $arrField );

        if( $arrRtn == NULL )
        {
            $nErrCode = CErrorCode::SUCCESS_NOTING_DATA;
            $sDesc = "获取成功,数据为空";
        }
        else
        {
            $arrRtn = $arrRtn->toArray();
            $sDesc = "success";
        }

        return $nErrCode;


    }
}