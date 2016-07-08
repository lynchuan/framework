<?php
/**
 * Created by PhpStorm.
 * User: snow
 * Date: 7/8/16
 * Time: 11:50 PM
 */

namespace App\Model\Data;
use Swoole;

class AppData extends Swoole\Model
{
    public $table = 'tbEventOccurDetail';

    public function get_key_data_by_msgId($app_id,$msg_id) : array
    {
        $sql = "
                SELECT sum(ruv) AS verify, sum(puv) as push,sum(luv) as push_online,SUM(ouv) AS click
                    FROM (
                    SELECT DATE,
                    CASE WHEN EventId =  'verify'
                    THEN EventNum
                    END AS ruv,
                    CASE WHEN EventId =  'push'
                    THEN EventNum
                    END AS puv,
                    CASE WHEN EventId =  'push_online'
                    THEN EventNum
                    END AS luv,
                    CASE WHEN EventId =  'click'
                    THEN EventNum
                    END AS ouv
                    FROM " . $this->table . "
                    WHERE AppId = {$app_id}
                    AND ArgKey = 'broadcastId'
                    AND ArgValue = {$msg_id}
                    )  U ";

        model('data/DistributeService');
        $model = new DistributeService($this->swoole,'offline');
        $result = $model->fetch_array($app_id,$sql);

        return $result;
    }
}