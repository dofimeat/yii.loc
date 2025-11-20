<?php

namespace app\modules\account\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Article;
use Yii;

/**
 * ArticleSearch represents the model behind the search form of `app\models\Article`.
 */
class ArticleSearch extends Article
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status_id'], 'integer'],
            [['title', 'name', 'content', 'img', 'created_at', 'updated_at', 'reject_reason'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
public function search($params, $formName = null)
{
    $query = Article::find()->where(['user_id' => Yii::$app->user->id]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 9, 
        ],
        'sort' => [
            'defaultOrder' => [
                'created_at' => SORT_DESC, 
            ]
        ],
    ]);

    $this->load($params, $formName);

    if (!$this->validate()) {
        return $dataProvider;
    }

    $query->andFilterWhere([
        'id' => $this->id,
        'status_id' => $this->status_id,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ]);

    $query->andFilterWhere(['like', 'title', $this->title])
        ->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'content', $this->content])
        ->andFilterWhere(['like', 'img', $this->img])
        ->andFilterWhere(['like', 'reject_reason', $this->reject_reason]);

    return $dataProvider;
}
}