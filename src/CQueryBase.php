<?php

namespace ioext\laravel_db;

use ioext\tool\CLib;

class CQueryBase extends CQueryBaseExtension
{

    /**
     * 插入一条数据
     *
     * @param $arrField
     * @param array $arrRtn
     * @param string $sDesc
     * @return int
     */
    public static function add( $arrField, $bIsGetId = true, & $nId = -1 , & $sDesc = "success" )
    {
        $nId = -1;
        if( CLib::IsArrayWithKeys( $arrField ) )
        $nErrCode = CErrCode::SUCCESS;

        if(  CLib::IsArrayWithKeys( $arrField ) )
        {
            $nId = -1;
            try
            {
                if( $bIsGetId )
                {
                    $nId = self::query()->insertGetId( $arrField );
                }
                else
                {
                    $nId = self::query()->insert($arrField);
                }
            }
            catch ( \Exception $e )
            {
                $sDesc = "数据表:[ " . self::getTableName() . " ]查询记录SQL异常\n[ " . $e->getMessage() ."]";
                $nErrCode = CErrCode::DB_SEL_EXCEPTION;
            }

            if ( $nId > 0)
            {
                $sDesc = "添加成功";
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
     * @return int | bRtn   判断 nErrCode==200和bRtn===true
     */
    public static function del( $arrWhere, & $bRtn = false, & $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;

        if( CLib::IsArrayWithKeys( $arrWhere ) )
        {
            try
            {
                $bRtn = self::query()->where( $arrWhere )->delete();
                if( $bRtn === false )
                {
                    $nErrCode = CErrCode::DELETE_FALSE;
                    $sDesc = "删除失败";
                }
            }
            catch ( \Exception $e )
            {
                $sDesc = "数据表:[ ".self::getTableName() ." ]查询记录SQL异常\n[" . $e->getMessage() ."]";
                $nErrCode = CErrCode::DB_SEL_EXCEPTION;
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
        if( CLib::IsArrayWithKeys( $arrWhere ) && CLib::IsArrayWithKeys( $arrField ) )
        {
            try
            {
                if( CLib::IsArrayWithKeys($arrOrWhere) )
                {
                    $bRtn = self::query()->where( $arrWhere )->orWhere( $arrOrWhere )->update( $arrField );
                }
                else
                {
                    $bRtn = self::query()->where( $arrWhere )->update( $arrField );
                }

                if( $bRtn === false  )
                {
                    $nErrCode = CErrCode::UPDATE_FALSE;
                    $sDesc = "更新失败";
                }
            }
            catch ( \Exception $e )
            {
                $sDesc = "数据表:[ ". self::getTableName() ." ]查询记录SQL异常\n[" . $e->getMessage() ."]";
                $nErrCode = CErrCode::DB_SEL_EXCEPTION;
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

        if( ! CLib::IsArrayWithKeys( $arrField ) )
        {
            $arrField = ['*'];
        }

        if( CLib::IsArrayWithKeys( $arrWhere ) )
        {
            try
            {
                if( CLib::IsArrayWithKeys( $arrOrWhere ) )
                {
                    $arrRtn = self::query()->where( $arrWhere )->orWhere( $arrOrWhere )->first( $arrField );
                }
                else
                {
                    $arrRtn = self::query()->where( $arrWhere )->first( $arrField );
                }

                if( $arrRtn === null )
                {
                    $nErrCode = CErrCode::SUCCESS_NOTING_DATA;
                    $sDesc = "查询成功,数据为空";
                }
                else
                {
                    $arrRtn = $arrRtn->toArray();
                }
            }
            catch ( \Exception $e )
            {
                $sDesc = "数据表:[ ". self::getTableName() ." ]查询记录SQL异常\n[" . $e->getMessage() ."]";
                $nErrCode = CErrCode::DB_SEL_EXCEPTION;
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

        if( ! CLib::IsArrayWithKeys( $arrField ) )
        {
            $arrField = ['*'];
        }

        try
        {
            $arrRtn = self::query()->get( $arrField );
            if( $arrRtn === null )
            {
                $nErrCode = CErrCode::SUCCESS_NOTING_DATA;
                $sDesc = "获取成功,数据为空";
            }
            else
            {
                $arrRtn = $arrRtn->toArray();
            }
        }
        catch ( \Exception $e )
        {
            $sDesc = "数据表:[ ". self::getTableName() ." ]查询记录SQL异常\n[" . $e->getMessage() ."]";
            $nErrCode = CErrCode::DB_SEL_EXCEPTION;
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
        if( ! CLib::IsArrayWithKeys( $arrField ) )
        {
            $arrField = ['*'];
        }

        if( CLib::IsArrayWithKeys( $arrWhere ) )
        {
            if( CLib::IsArrayWithKeys( $arrOrWhere )  )
            {
                if( CLib::IsArrayWithKeys($arrOrderBy) )
                {
                    $arrRtn = self::query()->where( $arrWhere )->orWhere( $arrOrWhere );
                    foreach( $arrOrderBy as $nFKey => $arrFValue )
                    {
                        $arrRtn->orderBy( $arrFValue[0], $arrFValue[1] );
                    }

                    if( CLib::SafeIntVal( $nPerPage ) )
                    {
                        $arrRtn = $arrRtn->paginate( $nPerPage );
                    }

                }
                else
                {
                    $arrRtn = self::query()->where( $arrWhere )->orWhere( $arrOrWhere );
                }
            }
            else
            {
                $arrRtn = self::query()->where( $arrWhere );
            }
            try
            {
                $arrRtn = $arrRtn->get( $arrField );
                if( $arrRtn === null )
                {
                    $nErrCode = CErrCode::SUCCESS_NOTING_DATA;
                    $sDesc = "获取成功,数据为空";
                }
                else
                {
                    $arrRtn = $arrRtn->toArray();
                }
            }
            catch ( \Exception $e )
            {
                $sDesc = "数据表:[ ". self::getTableName() ." ]查询记录SQL异常\n[" . $e->getMessage() ."]";
                $nErrCode = CErrCode::DB_SEL_EXCEPTION;
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
        if( ! CLib::IsArrayWithKeys( $arrField ) )
        {
            $arrField = ['*'];
        }

        if( CLib::SafeStringVal( $sField )  && CLib::IsArrayWithKeys($arrWhereIn)  )
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


    /**
     * 分组获取,多维数组
     *
     * @param $arrField
     * @param $arrWhere
     * @param $arrGroupByField
     * @param $arrRtn
     * @param string $sDesc
     * @return int
     */
    public static function getsByWhereGroupBy( $arrField, $arrWhere, $arrGroupByField, & $arrRtn,& $sDesc = "success" )
    {
        $nErrCode = CErrCode::SUCCESS;

        if( CLib::IsArrayWithKeys( $arrField ) )
        {
            $arrField = ['*'];
        }

        $arrRtn = self::query();

        if( is_array( $arrWhere ) && count( $arrWhere ) > 0 )
        {
            if( is_array( $arrGroupByField ) && count( $arrGroupByField ) > 0 )//todo
            {
                $arrRtn = $arrRtn->where($arrWhere)->get( $arrField );
                foreach ( $arrGroupByField as $v )
                {
                    $arrRtn = $arrRtn->groupBy( $v );
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
                $nErrCode = self::getsByWhere( $arrField,$arrWhere,[],[],0,$arrRtn,$sDesc );
            }
        }
        elseif( $arrWhere == '' || count( $arrWhere ) == 0 || $arrWhere == null )
        {
            if( is_array( $arrGroupByField ) && count( $arrGroupByField ) > 0 )
            {
                $arrRtn = $arrRtn->get( $arrField );
                foreach ( $arrGroupByField as $v )
                {
                    $arrRtn = $arrRtn->groupBy( $v );
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
        }
        else
        {
            $nErrCode = CErrCode::PARAM_ERROR;
            $sDesc = "参数解析错误";
        }

        return $nErrCode;
    }
}