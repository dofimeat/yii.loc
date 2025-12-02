<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Article;
use yii\db\Query;

/**
 * ArticleSearch represents the model behind the search form of `app\models\Article`.
 */
class ArticleSearch extends Article
{
    public $pageSize; 
    public $sortBy; 
    public $author_name;
    public $status_name;
    public $created_at_from; 
    public $created_at_to;   
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status_id', 'pageSize'], 'integer'], 
            [['title', 'name', 'content', 'img', 'created_at', 'updated_at', 'reject_reason', 'sortBy', 'author_name', 'status_name', 'created_at_from', 'created_at_to'], 'safe'],
            [['created_at_from', 'created_at_to'], 'date', 'format' => 'php:Y-m-d'], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'pageSize' => 'Количество на странице',
            'sortBy' => 'Сортировка',
            'author_name' => 'Автор',
            'status_name' => 'Статус',
            'created_at_from' => 'Дата создания (от)', 
            'created_at_to' => 'Дата создания (до)',   
            'id' => 'ID',
            'user_id' => 'ID автора',
            'status_id' => 'ID статуса',
            'title' => 'Заголовок',
            'name' => 'Название',
            'content' => 'Содержание',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ]);
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
            ->joinWith([
                'author' => function($query) {
                    $query->from(['author' => 'user']);
                },
                'status' => function($query) {
                    $query->from(['status' => 'status']);
                }
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 10, 
                'pageSizeParam' => 'pageSize',
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
                'attributes' => [
                    'id' => [
                        'asc' => ['article.id' => SORT_ASC],
                        'desc' => ['article.id' => SORT_DESC],
                        'label' => 'ID',
                    ],
                    'title' => [
                        'asc' => ['article.title' => SORT_ASC],
                        'desc' => ['article.title' => SORT_DESC],
                        'label' => 'Заголовок',
                    ],
                    'created_at' => [
                        'asc' => ['article.created_at' => SORT_ASC],
                        'desc' => ['article.created_at' => SORT_DESC],
                        'label' => 'Дата создания',
                        'default' => SORT_DESC,
                    ],
                    'updated_at' => [
                        'asc' => ['article.updated_at' => SORT_ASC],
                        'desc' => ['article.updated_at' => SORT_DESC],
                        'label' => 'Дата обновления',
                    ],
                ],
            ],
        ]);

        $dataProvider->sort->attributes['author.name'] = [
            'asc' => ['author.name' => SORT_ASC],
            'desc' => ['author.name' => SORT_DESC],
            'label' => 'Автор',
        ];

        $dataProvider->sort->attributes['status.status'] = [
            'asc' => ['status.name' => SORT_ASC], 
            'desc' => ['status.name' => SORT_DESC],
            'label' => 'Статус',
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->created_at_from) && !empty($this->created_at_to)) {
            $query->andFilterWhere(['between', 'DATE(article.created_at)', $this->created_at_from, $this->created_at_to]);
        } elseif (!empty($this->created_at_from)) {
            $query->andFilterWhere(['>=', 'DATE(article.created_at)', $this->created_at_from]);
        } elseif (!empty($this->created_at_to)) {
            $query->andFilterWhere(['<=', 'DATE(article.created_at)', $this->created_at_to]);
        }

        $query->andFilterWhere([
            'article.id' => $this->id,
            'article.user_id' => $this->user_id,
            'article.status_id' => $this->status_id,
            'article.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'article.title', $this->title])
            ->andFilterWhere(['like', 'article.name', $this->name])
            ->andFilterWhere(['like', 'article.content', $this->content])
            ->andFilterWhere(['like', 'article.img', $this->img])
            ->andFilterWhere(['like', 'article.reject_reason', $this->reject_reason]);

        if (!empty($this->author_name)) {
            $query->andFilterWhere(['like', 'author.name', $this->author_name]);
        }

        if (!empty($this->status_name)) {
            $query->andFilterWhere(['like', 'status.name', $this->status_name]);
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

    public static function getStatusList()
    {
        return (new Query())
            ->select(['name', 'id'])
            ->from('status')
            ->indexBy('id')
            ->column();
    }
}