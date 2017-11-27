<?php

namespace ioext\laravel_db;

use Illuminate\Database\Eloquent\Model;

class CQueryBase extends Model
{

    /**
     * 插入一条数据
     *
     * @param $arrField
     * @param array $arrRtn
     * @param string $sDesc
     * @return int
     */
    public static function add( $arrField, &$arrRtn = [], & $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;

        if( !is_array( $arrField ) || count( $arrField ) <= 0 )
        {
            $nId = self::query()->insertGetId( $arrField );
            if ( is_int( $nId ) && $nId > 0 )
            {
                $arrRtn = [
                    'id' => $nId,
                ];
            }
            else
            {
                $nErrCode = CErrCode::INSERT_FALSE;
                $sDesc = "添加失败";
            }
        }
        else
        {
            $nErrCode = CErrCode::PARAM_ERROR;
            $sDesc = "参数解析错误";
        }


        return $nErrCode;
    }


    public static function del( $arrWhere, & $bRtn, $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;

        if( is_array( $arrWhere ) && count( $arrWhere ) > 0 )
        {

        }
        else
        {

        }

        return $nErrCode;
    }


    /**
     * 获取所有数据
     *
     * @param array $arrField  所需查询的字段
     * @param array $arrRtn
     * @param string $sDesc
     * @return int
     */
    public static function getsAll( $arrField = [], & $arrRtn= [], & $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;

        if( ! is_array( $arrField ) || count( $arrField ) <= 0 )
        {
            $arrField = ['*'];
        }

        $arrRtn = self::query()->get( $arrField );

        if( $arrRtn == NULL )
        {
            $nErrCode = CErrCode::SUCCESS_NOTING_DATA;
            $sDesc = "获取成功,数据为空";
        }
        else
        {
            $arrRtn = $arrRtn->toArray();
        }

        return $nErrCode;
    }
}