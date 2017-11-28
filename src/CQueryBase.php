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


    /**
     * 删除一条数据(物理删除)
     *
     * @param $arrWhere
     * @param bool $bRtn
     * @param string $sDesc
     * @return int | bRtn   判断 nErrCode==200和bRtn==true
     */
    public static function del( $arrWhere, & $bRtn = false, $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;

        if( is_array( $arrWhere ) && count( $arrWhere ) > 0 )
        {
            $bRtn = self::query()->where( $arrWhere )->delete();
            if( ! $bRtn )
            {
                $nErrCode = CErrCode::DELETE_FALSE;
                $sDesc = "删除失败";
            }
        }
        else
        {
            $nErrCode = CErrCode::PARAM_ERROR;
            $sDesc = "参数解析错误";
        }

        return $nErrCode;
    }

    /**
     * 更新
     *
     * @param $arrWhere
     * @param $arrOrWhere
     * @param $arrField
     * @param $bRtn
     * @param string $sDesc
     * @return int
     */
    public static function updateByWhere( $arrField,  $arrWhere, $arrOrWhere, & $bRtn, & $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;
        if( is_array( $arrWhere ) && count( $arrWhere ) > 0 && is_array( $arrField ) && count( $arrField ) > 0 )
        {
            if( is_array( $arrOrWhere ) && count( $arrOrWhere ) > 0 )
            {
                $bRtn = self::query()->where( $arrWhere )->orWhere( $arrOrWhere )->update( $arrField );
            }
            else
            {
                $bRtn = self::query()->where( $arrWhere )->update( $arrField );
            }

            if( ! $bRtn )
            {
                $nErrCode = CErrCode::UPDATE_FALSE;
                $sDesc = "更新失败";
            }
        }
        else
        {
            $nErrCode = CErrCode::PARAM_ERROR;
            $sDesc = "参数解析错误";
        }
        return $nErrCode;
    }

    /**
     * 获取一条数据一维数组
     *
     * @param $arrWhere
     * @param $arrOrWhere
     * @param $arrField
     * @param $arrRtn
     * @param string $sDesc
     * @return int
     */
    public static function getOneByWhere( $arrField, $arrWhere,$arrOrWhere, & $arrRtn, & $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;

        if( ! is_array( $arrField ) || count( $arrField ) <= 0 )
        {
            $arrField = ['*'];
        }

        if( is_array( $arrWhere ) && count( $arrWhere ) > 0 )
        {
            if( is_array( $arrOrWhere ) && count( $arrOrWhere ) > 0 )
            {
                $arrRtn = self::query()->where( $arrWhere )->orWhere( $arrOrWhere )->first( $arrField );
            }
            else
            {
                $arrRtn = self::query()->where( $arrWhere )->first( $arrField );
            }

            if( $arrRtn == null )
            {
                $nErrCode = CErrCode::SUCCESS_NOTING_DATA;
                $sDesc = "获取成功,数据为空";
            }
            else
            {
                $arrRtn = $arrRtn->toArray();
            }
        }
        else
        {
            $nErrCode = CErrCode::PARAM_ERROR;
            $sDesc = "参数解析错误";
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

    /**
     * 根据条件获取二维数组
     * 支持or where
     * 支持order by
     * 支持分页
     * 默认传递page
     *
     * url   http://laravelacademy.org/post/6160.html
     *
     * @param $arrField     [field,field]
     * @param $arrWhere     [[field,'=',value],[field,'=',value]]
     * @param $arrOrWhere   [[field,'=',value],[field,'=',value]]
     * @param $arrOrderBy   [[field,'desc'],[field,'asc']]
     * @param int $nPerPage 15
     * @param $arrRtn
     * @param string $sDesc
     * @return int
     */
    public static function getsByWhere( $arrField, $arrWhere, $arrOrWhere, $arrOrderBy,$nPerPage=15, & $arrRtn, & $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;
        if( ! is_array( $arrField ) || count( $arrField ) <= 0 )
        {
            $arrField = ['*'];
        }

        if( is_array( $arrWhere ) && count( $arrWhere ) > 0 )
        {
            if( is_array( $arrOrWhere ) && count( $arrOrWhere ) > 0 )
            {
                if( is_array( $arrOrderBy ) && count( $arrOrderBy ) > 0 )
                {
                    $arrRtn = self::query()->where( $arrWhere )->orWhere( $arrOrWhere );
                    foreach( $arrOrderBy as $nFKey => $arrFValue )
                    {
                        foreach ( $arrFValue as $v )
                        {
                            $arrRtn->orderBy( $v[0], $v[1] );
                        }
                    }

                    if( is_int( $nPerPage ) && $nPerPage > 0 )
                    {
                        $arrRtn = $arrRtn->paginate( $nPerPage )->get( $arrField );
                    }
                    else
                    {
                        $arrRtn = $arrRtn->get( $arrField );
                    }
                }
                else
                {
                    $arrRtn = self::query()->where( $arrWhere )->orWhere( $arrOrWhere )->get( $arrField );
                }
            }
            else
            {
                $arrRtn = self::query()->where( $arrWhere )->get( $arrField );
            }

            if( $arrRtn == null )
            {
                $nErrCode = CErrCode::SUCCESS_NOTING_DATA;
                $sDesc = "获取成功,数据为空";
            }
            else
            {
                $arrRtn = $arrRtn->toArray();
            }
        }
        else
        {
            $nErrCode = CErrCode::PARAM_ERROR;
            $sDesc = "参数解析错误";
        }
        return $nErrCode;
    }


    /**
     * where in 获取二维数组
     *
     * @param $sField
     * @param $arrField
     * @param $arrWhereIn
     * @param $arrRtn
     * @param string $sDesc
     * @return int
     */
    public static function getsByWhereIn($sField, $arrField, $arrWhereIn, & $arrRtn, & $sDesc = "success")
    {
        $nErrCode = CErrCode::SUCCESS;
        if( ! is_array( $arrField ) || count( $arrField ) <= 0 )
        {
            $arrField = ['*'];
        }

        if( ! is_null( $sField ) && $sField != '' && is_array( $arrWhereIn ) && count( $arrWhereIn ) > 0  )
        {
            $arrRtn = self::query()->whereIn($sField,$arrWhereIn)->get($arrField);
            if( $arrRtn == null )
            {
                $nErrCode = CErrCode::SUCCESS_NOTING_DATA;
                $sDesc = "获取成功,数据为空";
            }
            else
            {
                $arrRtn = $arrRtn->toArray();
            }
        }
        else
        {
            $nErrCode = CErrCode::PARAM_ERROR;
            $sDesc = "参数解析错误";
        }
        return $nErrCode;
    }


    public static function getsByWhereGroupBy( $arrField, $arrWhere, $arrGroupByField, & $arrRtn, $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;

        if( ! is_array( $arrField ) || count( $arrField ) <= 0 )
        {
            $arrField = ['*'];
        }

        if( is_array( $arrWhere ) && count( $arrWhere ) > 0 )
        {
            if(  )
        }
        elseif( $arrWhere == '' || count( $arrWhere ) == 0 )
        {
            $nErrCode = CErrCode::PARAM_ERROR;
            $sDesc = "参数解析错误";
        }
        return $nErrCode;
    }
}