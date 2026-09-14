<?php

declare(strict_types=1);

namespace app\models\product;

use app\components\web\crud\Model;
use app\models\Producer;
use app\models\Product;
use app\models\StorageMode;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property Product $entity
 */
class Save extends Model
{
    public ?string $name = null;

    public ?string $packageVolume = null;

    public ?string $producer = null;

    public ?int $storageModeId = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        /**
         * @var Product $entity
         */

        if ($entity->getIsNewRecord()) {
            return;
        }

        $this->name = $entity->name;
        $this->packageVolume = (string) $entity->package_volume;
        $this->producer = $entity->producer->name;
        $this->storageModeId = $entity->storage_mode_id;
    }

    public function attributeLabels(): array
    {
        $labels = new Product()->attributeLabels();

        return [
            'name' => $labels['name'],
            'packageVolume' => $labels['package_volume'],
            'producer' => $labels['producer_id'],
            'storageModeId' => $labels['storage_mode_id'],
        ];
    }

    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if (!empty($this->producer)) {
            $producer = Producer::findOne(['name' => $this->producer]);
            if (!$producer) {
                $producer = new Producer();
                $producer->name = $this->producer;
                $producer->save();
                if ($producer->hasErrors()) {
                    $firstErrors = $producer->getFirstErrors();
                    $this->addError('producer', reset($firstErrors));
                    return false;
                }
            }

            $this->entity->producer_id = $producer->id;
        }

        return true;
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'name' => $this->name,
            'package_volume' => $this->packageVolume,
            'storage_mode_id' => $this->storageModeId,
        ]);
    }

    public function getStorageModeIdOptions(): array
    {
        $query = StorageMode::find()
            ->select(['id', 'name'])
            ->orderBy('name ASC')
            ->asArray();

        return ArrayHelper::map($query->all(), 'id', 'name');
    }

    public function rules(): array
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => Product::NAME_MAX_LENGTH],

            ['packageVolume', 'required'],
            ['packageVolume', 'integer', 'min' => Product::PACKAGE_VOLUME_MIN],

            ['producer', 'filter', 'filter' => 'trim'],
            ['producer', 'required'],
            ['producer', 'string', 'max' => Producer::NAME_MAX_LENGTH],

            ['storageModeId', 'required'],
            ['storageModeId', 'integer'],
            ['storageModeId', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => StorageMode::class,
            ],
        ];
    }
}
