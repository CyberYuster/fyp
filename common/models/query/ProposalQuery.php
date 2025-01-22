<?php

namespace common\models\query;

use common\models\Proposal;

/**
 * This is the ActiveQuery class for [[Proposal]].
 *
 * @see Proposal
 */
class ProposalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Proposal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Proposal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function creator($userId)
    {
        return $this->andWhere(["created_by" => $userId]);
    }

    public function creatorDepartment($userId)
    {
        return $this->andWhere(["created_by" => $userId]);
    }

    public function latest()
    {
        return $this->orderBy(['created_at' => SORT_DESC]);
    }
}
