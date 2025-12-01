<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArticleSearch represents the model behind the search form of `app\models\Article`.
 */
class ArticleSearch extends Article
{
    public $pageSize;
    public $sortBy;
    public $title; 
    public $authorName; 
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pageSize'], 'integer'],
            [['sortBy', 'title', 'authorName'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pageSize' => 'Количество на странице',
            'sortBy' => 'Сортировка',
            'title' => 'Заголовок',
            'authorName' => 'Автор',
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
            ->where(['article.status_id' => 2])
            ->joinWith(['author']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 9,
                'pageSizeParam' => 'pageSize',
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
                'attributes' => [
                    'created_at' => [
                        'label' => 'По дате',
                        'asc' => ['article.created_at' => SORT_ASC],
                        'desc' => ['article.created_at' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'title' => [
                        'label' => 'По названию',
                        'asc' => ['article.title' => SORT_ASC],
                        'desc' => ['article.title' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'author' => [
                        'label' => 'По автору',
                        'asc' => ['user.name' => SORT_ASC],
                        'desc' => ['user.name' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->title)) {
            $query->andWhere(['like', 'article.title', $this->title]);
        }

        if (!empty($this->authorName)) {
            $query->andWhere(['like', 'user.name', $this->authorName]);
        }

        if ($this->sortBy) {
            list($attribute, $direction) = explode('-', $this->sortBy);
            
            if (isset($dataProvider->sort->attributes[$attribute])) {
                $order = $direction === 'asc' ? SORT_ASC : SORT_DESC;
                
                $sortParam = ($direction === 'desc' ? '-' : '') . $attribute;
                $_GET['sort'] = $sortParam;
                
                $dataProvider->sort->params = $_GET;
            }
        }

        if ($this->pageSize) {
            $dataProvider->pagination->pageSize = $this->pageSize;
        }

        return $dataProvider;
    }
}