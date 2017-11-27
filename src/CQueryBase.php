<?php

namespace ioext\laravel_db;

class CQueryBase extends Model
{
    public static function getsAll( $arrField = [], & $arrRtn= [], & $sDesc = "未知错误" )
    {
        $nErrCode = CErrorCode::SUCCESS;

        if( ! is_array( $arrField ) || count( $arrField ) <= 0 )
        {
            $arrField = ['*'];
        }


        return $nErrCode;


    }
}