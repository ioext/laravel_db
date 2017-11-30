# LARAVEL ORM DB
============
[![Total Downloads]]
> laravel 数据库底层操作

* 1、composer require ioext/laravel_db 


```
 public function test()
    {
        $arrField = ['*'];
        $arrWhere = [
            [MV3RgnChannel::CHANNEL_PID,'=',1]
        ];
        $arrOrWhere = [];
        $arrOrderBy = [
            ['sort','desc'],
            ['show_start_dt','asc']
        ];
        $nErrCode = MV3RgnChannel::getOneByWhere( $arrField,$arrWhere,$arrOrWhere,$arrRtn,$sDesc);

        $nErrCode = MV3RgnChannel::getsAll( $arrField,$arrRtn,$sDesc );

        $nErrCode = MV3RgnChannel::getsByWhere( $arrField,$arrWhere,$arrOrWhere,$arrOrderBy,'',$arrRtn,$sDesc);

        $arrWherrIn = [
            1,2,3
        ];
        $sField = MV3RgnChannel::ID;
        $nErrCode = MV3RgnChannel::getsByWhereIn( $sField,$arrField,$arrWherrIn,$arrRtn,$sDesc );

        $arrGroupByField = [
            MV3RgnChannel::CHANNEL_PID
        ];
        $nErrCode = MV3RgnChannel::getsByWhereGroupBy( $arrField,$arrWhere,$arrGroupByField,$arrRtn,$sDesc );

        var_dump($nErrCode,$arrRtn);
    }
```