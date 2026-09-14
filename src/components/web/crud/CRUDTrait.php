<?php

declare(strict_types=1);

namespace app\components\web\crud;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

trait CRUDTrait
{
    private function afterSave(Model $model, array $params = []): ?Response
    {
        $redirect = $params['redirect'] ?? [];
        if ($redirect) {
            foreach ($redirect as $k => $v) {
                if (is_callable($v)) {
                    $redirect[$k] = $v($model);
                }
            }
        }

        $url = null;
        if (Yii::$app->getRequest()->post('save')) {
            $url = $redirect['save'] ?? Url::to(['index']);
        } elseif (Yii::$app->getRequest()->post('save-and-add')) {
            $url = $redirect['create'] ?? Url::to(['create']);
        } elseif (Yii::$app->getRequest()->post('save-and-edit')) {
            $url = $redirect['save-and-edit'] ?? Url::to(['update', 'id' => $model->getEntity()->getPrimaryKey()]);
        }

        return null == $url ? null : $this->redirect($url);
    }

    private function create(Model $model, array $params = []): Response
    {
        $saveParams = $params['save'] ?? [];
        $response = $this->save($model, $saveParams);
        if (null != $response) {
            if (isset($params['successMessage'])) {
                Yii::$app->getSession()->setFlash('successMessage', $params['successMessage']);
            }

            return $response;
        }

        $view = $params['view'] ?? 'create';
        $viewParams = $params['viewParams'] ?? [];

        return new Response([
            'data' => $this->render($view, ArrayHelper::merge([
                'model' => $model,
            ], $viewParams)),
        ]);
    }

    private function delete(ActiveRecord $entity, array $params = []): Response
    {
        $entity->delete();

        Yii::$app->getSession()->setFlash('successMessage', $params['successMessage']);

        return $this->redirect($params['returnUrl'] ?? Url::to(['index']));
    }

    private function details(int $id, array $params = []): Response
    {
        $model = $params['model'] ?? $this->getEnityByIdOr404($id);

        $view = $params['view'] ?? 'details';
        $viewParams = $params['viewParams'] ?? [];

        $renderMethod = 'render';
        if (Yii::$app->getRequest()->getIsAjax()) {
            $renderMethod = 'renderPartial';
        }

        return new Response([
            'data' => $this->$renderMethod($view, ArrayHelper::merge([
                'model' => $model,
            ], $viewParams)),
        ]);
    }

    private function index(array $params = []): Response
    {
        $params['dataProvider'] ??= [];

        $dataProvider = new ActiveDataProvider($params['dataProvider']);

        $view = $params['view'] ?? 'index';
        $viewParams = $params['viewParams'] ?? [];
        $viewParams = ArrayHelper::merge([
            'dataProvider' => $dataProvider,
        ], $viewParams);

        $renderMethod = 'render';
        if (Yii::$app->getRequest()->getIsAjax()) {
            $renderMethod = 'renderPartial';
        }

        return new Response([
            'data' => $this->$renderMethod($view, $viewParams),
        ]);
    }

    private function save(Model $model, array $params = []): ?Response
    {
        $attributes = Yii::$app->getRequest()->post();
        if (!$model->load($attributes) || !$model->validate()) {
            return null;
        }

        $model->save();

        return $this->afterSave($model, $params['afterSave'] ?? []);
    }

    private function update(Model $model, array $params = []): Response
    {
        $saveParams = $params['save'] ?? [];
        $response = $this->save($model, $saveParams);
        if (null != $response) {
            if (isset($params['successMessage'])) {
                Yii::$app->getSession()->setFlash('successMessage', $params['successMessage']);
            }

            return $response;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => [$model->getEntity()],
            'key' => 'id',
            'pagination' => false,
            'sort' => false,
        ]);

        $view = $params['view'] ?? 'update';
        $viewParams = ($params['viewParams']) ?? [];

        return new Response([
            'data' => $this->render($view, ArrayHelper::merge([
                'dataProvider' => $dataProvider,
                'model' => $model,
            ], $viewParams)),
        ]);
    }

    private function view(int $id, array $params = []): Response
    {
        $model = $params['model'] ?? $this->getEnityByIdOr404($id);

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'sort' => false,
            'query' => $model->find()->andWhere(['id' => $id]),
        ]);

        $view = $params['view'] ?? 'view';
        $viewParams = ($params['viewParams']) ?? [];

        return new Response([
            'data' => $this->render($view, ArrayHelper::merge([
                'dataProvider' => $dataProvider,
                'model' => $model,
            ], $viewParams)),
        ]);
    }
}
