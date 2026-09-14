<?php

declare(strict_types=1);

namespace app\models\storage_cell;

use app\components\web\crud\Model;
use app\models\StorageCell;
use app\models\StorageMode;
use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property StorageCell $entity
 */
class Save extends Model
{
    public ?int $rackNumber = null;

    public ?int $rowNumber = null;

    public ?string $shelfNumber = null;

    public ?int $storageModeId = null;

    public ?string $volume = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        /**
         * @var StorageCell $entity
         */

        if ($entity->getIsNewRecord()) {
            return;
        }

        $this->rackNumber = $entity->rack_number;
        $this->rowNumber = $entity->row_number;
        $this->shelfNumber = $entity->shelf_number;
        $this->storageModeId = $entity->storage_mode_id;
        $this->volume = (string) $entity->volume;
    }

    #[ArrayShape([
        'rackNumber' => 'string',
        'rowNumber' => 'string',
        'shelfNumber' => 'string',
        'storageModeId' => 'string',
        'volume' => 'string',
    ])]
    public function attributeLabels(): array
    {
        $labels = new StorageCell()->attributeLabels();

        return [
            'rackNumber' => $labels['rack_number'],
            'rowNumber' => $labels['row_number'],
            'shelfNumber' => $labels['shelf_number'],
            'storageModeId' => $labels['storage_mode_id'],
            'volume' => $labels['volume'],
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'rack_number' => $this->rackNumber,
            'row_number' => $this->rowNumber,
            'shelf_number' => $this->shelfNumber,
            'storage_mode_id' => $this->storageModeId,
            'volume' => $this->volume,
        ]);
    }


    public function getRackNumberOptions(): array
    {
        $options = [];

        for ($i = StorageCell::RACK_NUMBER_MIN; $i <= StorageCell::RACK_NUMBER_MAX; $i++) {
            $options[$i] = $i;
        }

        return $options;
    }

    public function getRowNumberOptions(): array
    {
        $options = [];

        for ($i = StorageCell::ROW_NUMBER_MIN; $i <= StorageCell::ROW_NUMBER_MAX; $i++) {
            $options[$i] = $i;
        }

        return $options;
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
            ['rackNumber', 'required'],
            ['rackNumber', 'integer', 'min' => StorageCell::RACK_NUMBER_MIN, 'max' => StorageCell::RACK_NUMBER_MAX],

            ['rowNumber', 'required'],
            ['rowNumber', 'integer', 'min' => StorageCell::ROW_NUMBER_MIN, 'max' => StorageCell::ROW_NUMBER_MAX],

            ['shelfNumber', 'required'],
            ['shelfNumber', 'in', 'range' => StorageCell::getShelfNumberOptions()],

            ['storageModeId', 'required'],
            ['storageModeId', 'integer'],
            ['storageModeId', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => StorageMode::class,
            ],

            ['volume', 'required'],
            ['volume', 'integer', 'min' => StorageCell::VOLUME_MIN],
        ];
    }
}
