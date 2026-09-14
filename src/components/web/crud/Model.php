<?php

declare(strict_types=1);

namespace app\components\web\crud;

use yii\db\ActiveRecord;

abstract class Model extends \yii\base\Model
{
    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($config);
    }

    abstract protected function fillEntity(): void;

    public function getEntity(): ActiveRecord
    {
        return $this->entity;
    }

    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        if (!parent::validate($attributeNames, $clearErrors)) {
            return false;
        }

        $this->fillEntity();

        return $this->entity->validate();
    }

    public function save(): void
    {
        $this->entity->save();
    }
}
