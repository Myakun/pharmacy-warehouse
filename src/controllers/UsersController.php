<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\User;
use app\models\user\Create;
use app\models\user\Update;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UsersController extends Controller
{
    use CRUDTrait;

    public function actionCreate(): Response
    {
        return $this->create(new Create(new User()), [
            'successMessage' => Yii::t('app', 'User created'),
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $user = User::findOne($id);
        if (null == $user) {
            throw new NotFoundHttpException();
        }

        return $this->delete($user, [
            'successMessage' => Yii::t('app', 'User deleted'),
        ]);
    }

    public function actionIndex(): Response
    {
        return $this->index([
            'dataProvider' => [
                'query' => User::find()
                    ->with(['createdBy'])
                    ->orderBy('name ASC'),
            ],
        ]);
    }

    public function actionUpdate(int $id): Response
    {
        $user = User::findOne($id);
        if (null == $user) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Update(User::findOne($id)), [
            'successMessage' => Yii::t('app', 'User updated'),
        ]);
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => [User::ROLE_ASSOCIATE_DIRECTOR, User::ROLE_GENERAL_DIRECTOR],
                ],
            ],
        ];

        return $behaviors;
    }
}
