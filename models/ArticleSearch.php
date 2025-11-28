<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\bootstrap5\LinkPager;

/**
 * ArticleSearch represents the model behind the search form of `app\models\Article`.
 */
class ArticleSearch extends Article
{
    public $pageSize; 
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pageSize'], 'integer'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Article::find()
            ->where(['status_id' => 2]) 
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 9, 
                'pageSizeParam' => 'pageSize',
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->pageSize) {
            $dataProvider->pagination->pageSize = $this->pageSize;
        }

        return $dataProvider;
    }
}