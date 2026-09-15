<?php

declare(strict_types=1);

namespace app\models\user;

use app\models\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property User $entity
 */
class Create extends Save
{
    public const PASSWORD_MIN_LENGTH = 8;

    public ?string $password = null;

    public function attributeLabels(): array
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'password' => Yii::t('app', 'Password'),
        ]);
    }

    protected function fillEntity(): void
    {
        parent::fillEntity();

        $this->entity->setAttributes([
            'password' => $this->password,
        ]);
    }

    public function getRoleOptions(): array
    {
        $options = parent::getRoleOptions();

        $authManager = Yii::$app->getAuthManager();
        foreach ($options as $role => $label) {
            if (in_array($role, User::UNIQUE_ROLES) && !empty($authManager->getUserIdsByRole($role))) {
                unset($options[$role]);
            }
        }

        asort($options);

        return $options;
    }

    public function rules(): array
    {
        return ArrayHelper::merge(parent::rules(), [
            ['password', 'filter', 'filter' => 'trim'],
            ['password', 'required'],
            ['password', 'string', 'min' => self::PASSWORD_MIN_LENGTH],
        ]);
    }

    public function save(): void
    {
        parent::save();

        $authManager = Yii::$app->getAuthManager();
        $authManager->assign($authManager->getRole($this->role), $this->entity->id);
    }
}
