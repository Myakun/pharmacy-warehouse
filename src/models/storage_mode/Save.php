<?php

declare(strict_types=1);

namespace app\models\storage_mode;

use app\components\web\crud\Model;
use app\models\StorageMode;
use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveRecord;

/**
 * @property StorageMode $entity
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
         * @var StorageMode $entity
         */

        if ($entity->getIsNewRecord()) {
            return;
        }

        $this->name = $entity->name;
    }

    #[ArrayShape(['name' => 'string'])]
    public function attributeLabels(): array
    {
        return [
            'name' => new StorageMode()->getAttributeLabel('name'),
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
            ['name', 'string', 'max' => StorageMode::NAME_MAX_LENGTH],
        ];
    }
}
