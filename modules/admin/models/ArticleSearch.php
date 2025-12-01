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
    public $authorName;
    public $searchQuery;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status_id', 'pageSize'], 'integer'], 
            [['title', 'name', 'content', 'img', 'created_at', 'updated_at', 'reject_reason', 'sortBy', 'authorName', 'searchQuery'], 'safe'],
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
            'authorName' => 'Автор',
            'searchQuery' => 'Общий поиск',
            'id' => 'ID',
            'user_id' => 'ID автора',
            'status_id' => 'Статус',
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
        $query = Article::find()->joinWith(['author', 'status']);

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
                    'created_at' => [
                        'label' => 'По дате',
                        'asc' => ['created_at' => SORT_ASC],
                        'desc' => ['created_at' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'title' => [
                        'label' => 'По названию',
                        'asc' => ['title' => SORT_ASC],
                        'desc' => ['title' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'author.login' => [
                        'label' => 'По автору',
                        'asc' => ['user.login' => SORT_ASC],
                        'desc' => ['user.login' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'status.name' => [
                        'label' => 'По статусу',
                        'asc' => ['status.name' => SORT_ASC],
                        'desc' => ['status.name' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->searchQuery)) {
            $query->andWhere([
                'or',
                ['like', 'article.title', $this->searchQuery],
                ['like', 'article.name', $this->searchQuery],
                ['like', 'article.content', $this->searchQuery],
                ['like', 'user.name', $this->searchQuery],
                ['like', 'user.login', $this->searchQuery],
                ['like', 'article.reject_reason', $this->searchQuery],
            ]);
        }

        $query->andFilterWhere(['like', 'article.title', $this->title])
            ->andFilterWhere(['like', 'article.name', $this->name]);

        if (!empty($this->authorName)) {
            $query->andWhere(['like', 'user.name', $this->authorName]);
        }

        $query->andFilterWhere([
            'article.id' => $this->id,
            'article.user_id' => $this->user_id,
            'article.status_id' => $this->status_id,
            'article.created_at' => $this->created_at,
            'article.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'article.content', $this->content])
            ->andFilterWhere(['like', 'article.img', $this->img])
            ->andFilterWhere(['like', 'article.reject_reason', $this->reject_reason]);

        if ($this->sortBy) {
            list($attribute, $direction) = explode('-', $this->sortBy);
            $dataProvider->sort->defaultOrder = [$attribute => $direction == 'asc' ? SORT_ASC : SORT_DESC];
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