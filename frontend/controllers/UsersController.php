<?php

namespace frontend\controllers;

use app\models\Bookmarks;
use frontend\models\forms\UserFilterForm;
use frontend\models\UsersFiles;
use frontend\models\UsersSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use frontend\models\Users;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UsersController extends SecuredController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@', '?']
                    ],
                    [
                        'actions' => ['add-bookmark'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    return $this->goHome();
                },
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $query = Users::find()->where(['is_executor' => 1])
            ->andWhere(['hide_profile' => 0]);
        $filterForm = new UserFilterForm();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5
            ]
        ]);

        if ($filterForm->load(Yii::$app->request->get())) {
            $usersSearch = new UsersSearch();
            $provider = $usersSearch->search($filterForm);
        }

        return $this->render('index', [
            'dataProvider' => $provider,
            'filterForm' => $filterForm
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $user = Users::find()->where(['id' => $id])->andWhere(['hide_profile' => 0])->one();

        if (!$user) {
            throw new NotFoundHttpException("Пользователь с id $id не найден");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => UsersFiles::find()->where(['user_id' => $user->id]),
            'pagination' => [
                'pageSize' => 3
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        return $this->render('view', compact(
            'user', 'dataProvider'
        ));
    }

    /**
     * @param int $favorite_id
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionAddBookmark(int $favorite_id): Response
    {
        $bookmarks = Bookmarks::find()->where(['follower_id' => Yii::$app->user->id])->all();

        foreach ($bookmarks as $bookmark) {
            if ($bookmark->favorite->id === $favorite_id) {
                if ($bookmark->delete()) {
                    Yii::$app->session->setFlash('bookmark-delete', 'Пользователь удален из избранного');
                }
                return $this->redirect(['users/view', 'id' => $favorite_id]);
            }
        }
        $bookmark = new Bookmarks();
        $bookmark->favorite_id = $favorite_id;
        $bookmark->follower_id = Yii::$app->user->id;
        if ($bookmark->save()) {
            Yii::$app->session->setFlash('bookmark-add', 'Пользователь добавлен в избранное');
        }

        return $this->redirect(['users/view', 'id' => $favorite_id]);
    }

}