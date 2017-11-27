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


        return $nErrCode;


    }
}