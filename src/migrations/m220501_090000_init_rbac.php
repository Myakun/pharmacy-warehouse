<?php

declare(strict_types=1);

use app\models\Customer;
use app\models\Producer;
use app\models\Product;
use app\models\ProductStorageCell;
use app\models\Receipt;
use app\models\Shipment;
use app\models\StorageCell;
use app\models\StorageMode;
use app\models\Supplier;
use app\models\User;
use yii\db\Migration;

class m220501_090000_init_rbac extends Migration
{
    public function safeUp(): bool
    {
        $authManager = Yii::$app->getAuthManager();

        // Customers
        $listCustomers = $authManager->createPermission(Customer::PERMISSION_LIST);
        $authManager->add($listCustomers);

        $manageCustomers = $authManager->createPermission(Customer::PERMISSION_MANAGE);
        $authManager->add($manageCustomers);
        $authManager->addChild($manageCustomers, $listCustomers);

        // Producers
        $listProducers = $authManager->createPermission(Producer::PERMISSION_LIST);
        $authManager->add($listProducers);

        $manageProducers = $authManager->createPermission(Producer::PERMISSION_MANAGE);
        $authManager->add($manageProducers);
        $authManager->addChild($manageProducers, $listProducers);

        // Products
        $listProducts = $authManager->createPermission(Product::PERMISSION_LIST);
        $authManager->add($listProducts);

        $manageProducts = $authManager->createPermission(Product::PERMISSION_MANAGE);
        $authManager->add($manageProducts);
        $authManager->addChild($manageProducts, $listProducts);

        // ProductsStorageCells
        $listProductsStorageCells = $authManager->createPermission(ProductStorageCell::PERMISSION_LIST);
        $authManager->add($listProductsStorageCells);

        // Receipts
        $listReceipts = $authManager->createPermission(Receipt::PERMISSION_LIST);
        $authManager->add($listReceipts);

        $manageReceipts = $authManager->createPermission(Receipt::PERMISSION_MANAGE);
        $authManager->add($manageReceipts);
        $authManager->addChild($manageReceipts, $listReceipts);

        // Shipments
        $listShipments = $authManager->createPermission(Shipment::PERMISSION_LIST);
        $authManager->add($listShipments);

        $manageShipments = $authManager->createPermission(Shipment::PERMISSION_MANAGE);
        $authManager->add($manageShipments);
        $authManager->addChild($manageShipments, $listShipments);

        // Statistics
        $statistics = $authManager->createPermission('statistics');
        $authManager->add($statistics);

        // StorageCells
        $listStorageCells = $authManager->createPermission(StorageCell::PERMISSION_LIST);
        $authManager->add($listStorageCells);

        $manageStorageCells = $authManager->createPermission(StorageCell::PERMISSION_MANAGE);
        $authManager->add($manageStorageCells);
        $authManager->addChild($manageStorageCells, $listStorageCells);

        // StorageModes
        $listStorageModes = $authManager->createPermission(StorageMode::PERMISSION_LIST);
        $authManager->add($listStorageModes);

        $manageStorageModes = $authManager->createPermission(StorageMode::PERMISSION_MANAGE);
        $authManager->add($manageStorageModes);
        $authManager->addChild($manageStorageModes, $listStorageModes);

        // Suppliers
        $listSuppliers = $authManager->createPermission(Supplier::PERMISSION_LIST);
        $authManager->add($listSuppliers);

        $manageSuppliers = $authManager->createPermission(Supplier::PERMISSION_MANAGE);
        $authManager->add($manageSuppliers);
        $authManager->addChild($manageSuppliers, $listSuppliers);

        // Users
        $manageUsers = $authManager->createPermission(User::PERMISSION_MANAGE);
        $authManager->add($manageUsers);

        $accountant = $authManager->createRole(User::ROLE_ACCOUNTANT);
        $accountant->description = 'Бухгалтер';
        $authManager->add($accountant);
        $authManager->addChild($accountant, $listCustomers);
        $authManager->addChild($accountant, $listProducers);
        $authManager->addChild($accountant, $listProducts);
        $authManager->addChild($accountant, $listProductsStorageCells);
        $authManager->addChild($accountant, $listReceipts);
        $authManager->addChild($accountant, $listShipments);
        $authManager->addChild($accountant, $listStorageCells);
        $authManager->addChild($accountant, $listStorageModes);
        $authManager->addChild($accountant, $listSuppliers);
        $authManager->addChild($accountant, $statistics);

        $warehouseManager = $authManager->createRole(User::ROLE_WAREHOUSE_MANAGER);
        $warehouseManager->description = 'Заведующий складом';
        $authManager->add($warehouseManager);
        $authManager->addChild($warehouseManager, $listProductsStorageCells);
        $authManager->addChild($warehouseManager, $listShipments);
        $authManager->addChild($warehouseManager, $manageProducers);
        $authManager->addChild($warehouseManager, $manageProducts);
        $authManager->addChild($warehouseManager, $manageReceipts);
        $authManager->addChild($warehouseManager, $manageStorageCells);
        $authManager->addChild($warehouseManager, $manageStorageModes);
        $authManager->addChild($warehouseManager, $manageSuppliers);

        $orderCollector = $authManager->createRole(User::ROLE_ORDER_COLLECTOR);
        $orderCollector->description = 'Сборщик';
        $authManager->add($orderCollector);
        $authManager->addChild($orderCollector, $listShipments);

        $manager = $authManager->createRole(User::ROLE_MANAGER);
        $manager->description = 'Менеджер';
        $authManager->add($manager);
        $authManager->addChild($manager, $listProducers);
        $authManager->addChild($manager, $listProducts);
        $authManager->addChild($manager, $listProductsStorageCells);
        $authManager->addChild($manager, $listStorageModes);
        $authManager->addChild($manager, $manageCustomers);
        $authManager->addChild($manager, $manageShipments);
        $authManager->addChild($manager, $statistics);

        $associateDirector = $authManager->createRole(User::ROLE_ASSOCIATE_DIRECTOR);
        $associateDirector->description = 'Заместитель директора';
        $authManager->add($associateDirector);
        $authManager->addChild($associateDirector, $listProductsStorageCells);
        $authManager->addChild($associateDirector, $manageCustomers);
        $authManager->addChild($associateDirector, $manageProducers);
        $authManager->addChild($associateDirector, $manageProducts);
        $authManager->addChild($associateDirector, $manageReceipts);
        $authManager->addChild($associateDirector, $manageShipments);
        $authManager->addChild($associateDirector, $manageStorageCells);
        $authManager->addChild($associateDirector, $manageStorageModes);
        $authManager->addChild($associateDirector, $manageSuppliers);
        $authManager->addChild($associateDirector, $manageUsers);
        $authManager->addChild($associateDirector, $statistics);

        $generalDirector = $authManager->createRole(User::ROLE_GENERAL_DIRECTOR);
        $generalDirector->description = 'Директор';
        $authManager->add($generalDirector);
        $authManager->addChild($generalDirector, $associateDirector);

        return true;
    }

    public function safeDown(): bool
    {
        return true;
    }
}
