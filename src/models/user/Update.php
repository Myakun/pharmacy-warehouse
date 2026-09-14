<?php

declare(strict_types=1);

namespace app\models\user;

use app\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property User $entity
 */
class Update extends Save
{
    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        /**
         * @var User $entity
         */

        $this->email = $entity->email;
        $this->name = $entity->name;

        $roles = Yii::$app->getAuthManager()->getRolesByUser($entity->id);
        $this->role = $roles[array_key_first($roles)]->name;
    }

    public function getRoleOptions(): array
    {
        $options = parent::getRoleOptions();

        $authManager = Yii::$app->getAuthManager();
        foreach ($options as $role => $label) {
            if (
                in_array($role, User::UNIQUE_ROLES)
                &&
                !empty($usersIds = $authManager->getUserIdsByRole($role))
                &&
                !in_array($this->entity->id, $usersIds)
            ) {
                unset($options[$role]);
            }
        }

        return $options;
    }

    public function save(): void
    {
        parent::save();

        $authManager = Yii::$app->getAuthManager();

        $roles = $authManager->getRolesByUser($this->entity->id);
        $role = $roles[array_key_first($roles)];

        if ($role->name != $this->role) {
            $authManager->revoke($role, $this->entity->id);
            $authManager->assign($authManager->getRole($this->role), $this->entity->id);
        }
    }
}
