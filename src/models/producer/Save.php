<?php

declare(strict_types=1);

namespace app\models\producer;

use app\components\web\crud\Model;
use app\models\Producer;
use yii\db\ActiveRecord;

/**
 * @property Producer $entity
 */
class Save extends Model
{
    public ?string $name = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        /**
         * @var Producer $entity
         */

        if ($entity->getIsNewRecord()) {
            return;
        }

        $this->name = $entity->name;
    }

    public function attributeLabels(): array
    {
        return [
            'name' => new Producer()->getAttributeLabel('name'),
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'name' => $this->name,
        ]);
    }

    public function rules(): array
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => Producer::NAME_MAX_LENGTH],
        ];
    }
}
