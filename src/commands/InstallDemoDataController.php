<?php

declare(strict_types=1);

namespace app\commands;

use app\models\Customer;
use app\models\Producer;
use app\models\Product;
use app\models\StorageCell;
use app\models\StorageMode;
use app\models\Supplier;
use app\models\User;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class InstallDemoDataController extends Controller
{
    private array $producers = [];

    public function actionIndex(): void
    {
        $this->installUsers();
        //$this->installCustomers();
        $this->installProducers();
        $this->installProducts();
        $this->installStorageCells();
        $this->installSuppliers();
    }

    private function installCustomers(): void
    {
        for ($i = 1; $i < 30; $i++) {
            $name = 'Клиент ' . $i;
            $customer = new Customer();
            $customer->getBehavior('blameable')->value = 1;
            $customer->address = $name . ' адрес';
            $customer->contact_person = $name . ' контактное лицо';
            $customer->contract_date = date('Y-m-d');
            $customer->contract_number = rand(1, 1000000);
            $customer->phone = (string) rand(1111111111, 9999999999);
            $customer->name = $name;
            $customer->save();
        }
    }

    private function installProducts(): void
    {
        $storageModes = ArrayHelper::map(StorageMode::find()->all(), 'name', 'id');

        $data = json_decode(file_get_contents(__DIR__ . '/../data/demo-products.json'), false);
        foreach ($data as $item) {
            if (isset($producers[$item->producer])) {
                continue;
            }

            $product = new Product();
            $product->getBehavior('blameable')->value = 1;
            $product->name = $item->name;
            $product->package_volume = str_replace(',', '', (string) $item->volume);
            $product->producer_id = $this->producers[$item->producer];
            $product->storage_mode_id = $storageModes[$item->mode];
            $product->save();
        }
    }

    private function installProducers(): void
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../data/demo-products.json'), false);
        foreach ($data as $item) {
            if (isset($this->producers[$item->producer])) {
                continue;
            }

            $producer = new Producer();
            $producer->getBehavior('blameable')->value = 1;
            $producer->name = $item->producer;
            $producer->save();

            $this->producers[$item->producer] = $producer->id;
        }
    }

    private function installStorageCells(): void
    {
        $storageModes = ArrayHelper::map(StorageMode::find()->all(), 'name', 'id');

        for ($row = StorageCell::ROW_NUMBER_MIN; $row <= StorageCell::ROW_NUMBER_MAX; $row++) {
            for ($rack = StorageCell::RACK_NUMBER_MIN; $rack <= StorageCell::RACK_NUMBER_MAX; $rack++) {
                foreach (StorageCell::getShelfNumberOptions() as $shelf) {
                    $cell = new StorageCell();
                    $cell->getBehavior('blameable')->value = 1;
                    $cell->rack_number = $rack;
                    $cell->row_number = $row;
                    $cell->shelf_number = $shelf;
                    $cell->storage_mode_id = $storageModes['Не выше 25°С'];
                    $cell->volume = 100000;
                    $cell->save();

                    if ($row > 1 || $rack > 2) {
                        continue;
                    }

                    $cell = new StorageCell();
                    $cell->getBehavior('blameable')->value = 1;
                    $cell->rack_number = $rack;
                    $cell->row_number = $row;
                    $cell->shelf_number = $shelf;
                    $cell->storage_mode_id = $storageModes['СД'];
                    $cell->volume = 100000;
                    $cell->save();

                    $cell = new StorageCell();
                    $cell->getBehavior('blameable')->value = 1;
                    $cell->rack_number = $rack;
                    $cell->row_number = $row;
                    $cell->shelf_number = $shelf;
                    $cell->storage_mode_id = $storageModes['СК'];
                    $cell->volume = 100000;
                    $cell->save();

                    if ($rack > 1) {
                        continue;
                    }

                    $cell = new StorageCell();
                    $cell->getBehavior('blameable')->value = 1;
                    $cell->rack_number = $rack;
                    $cell->row_number = $row;
                    $cell->shelf_number = $shelf;
                    $cell->storage_mode_id = $storageModes['Холодильник'];
                    $cell->volume = 10000;
                    $cell->save();
                }
            }
        }
    }

    private function installSuppliers(): void
    {
        $suppliers = 'Авеста-Курск Адванта Армаирская БФ Арсвитал ВекторФарм Гротекс Здравсервис Ирвин-2 Катрен Медипал-онко';
        $suppliers .= 'Медлайн Миракл-Фарм Надежда-фарм Натива Норман Озон Промомед Протек Пульс Р-Фарм СИА-Воронеж';
        $suppliers .= 'Сириус-фарм Сотекс Техфарм Уралбиофарм Фарм-Модуль Фармтехнологии';

        foreach (explode(' ', $suppliers) as $name) {
            $supplier = new Supplier();
            $supplier->getBehavior('blameable')->value = 1;
            $supplier->address = $name . ' адрес';
            $supplier->contact_person = $name . ' контактное лицо';
            $supplier->contract_date = date('Y-m-d');
            $supplier->contract_number = rand(1, 1000000);
            $supplier->phone = (string) rand(1111111111, 9999999999);
            $supplier->name = $name;
            $supplier->save();
        }
    }

    private function installUsers(): void
    {
        $authManager = Yii::$app->getAuthManager();

        foreach ($authManager->getRoles() as $role) {
            if ($role->name == User::ROLE_GENERAL_DIRECTOR) {
                continue;
            }

            $nickname = str_replace(' ', '_', $role->name);
            $nickname = mb_strtolower($nickname, 'utf-8');

            $user = new User();
            $user->setAttributes([
                'email' => "$nickname@example.com",
                'name' => $role->description,
                'password' => $nickname,
            ]);
            $user->save();

            $authManager->assign($role, $user->id);
        }
    }
}
