<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Duration]].
 *
 * @see \app\models\Duration
 */
class DurationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    public function init()
    {
        $this->orderBy([
            'time' => SORT_ASC,
        ]);
    }

    public function byDate($date)
    {
        return $this->byDateRange($date, $date);
    }

    public function byDateRange($start, $end)
    {
        $start = strtotime($start);
        $end = strtotime('+1day', strtotime($end));

        $this->andWhere('time > :start AND time < :end', [':start' => $start, ':end' => $end]);
        return $this;
    }

    public function byProject($name)
    {
        $this->andWhere(['project' => $name]);
        return $this;
    }

    public function byBranch($name)
    {
        $this->andWhere(['branch' => $name]);
        return $this;
    }


    /**
     * @inheritdoc
     * @return \app\models\Duration[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Duration|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
