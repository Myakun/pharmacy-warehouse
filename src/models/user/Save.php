<?php

declare(strict_types=1);

namespace app\models\user;

use app\components\web\crud\Model;
use app\models\User;
use Yii;

abstract class Save extends Model
{
    public ?string $email = null;

    public ?string $name = null;

    public ?string $role = null;

    public function attributeLabels(): array
    {
        $labels = new User()->attributeLabels();

        return [
            'email' => 'Email',
            'name' => $labels['name'],
            'role' => Yii::t('app', 'Role'),
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'email' => $this->email,
            'name' => $this->name,
        ]);
    }

    public function getRoleOptions(): array
    {
        $options = [];

        foreach (Yii::$app->getAuthManager()->getRoles() as $role) {
            $options[$role->name] = $role->description;
        }

        asort($options);

        return $options;
    }

    public function rules(): array
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'email'],
            ['email', 'required'],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => User::NAME_MAX_LENGTH],

            ['role', 'required'],
        ];
    }
}
