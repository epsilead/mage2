<?php
/**
 * AR_Epistolary module
 *
 * @category  AR
 * @package   AR_Epistolary
 * @copyright 2018 Artem Rotmistrenko
 * @license
 * @author    Artem Rotmistrenko
 */
namespace AR\Epistolary\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $tableName = $setup->getTable('ar_epmessage');
        if ($setup->getConnection()->isTableExists($tableName) == true) {
            $data = [
                [
                    'subject' => 'Fixture Subject №1',
                    'email' => 'test1@test.test',
                    'message' => 'Fixture Message №1',
                ],
                [
                    'subject' => 'Fixture Subject №2',
                    'email' => 'test2@test.test',
                    'message' => 'Fixture Message №2',
                ]
            ];
            foreach ($data as $item) {
                $setup->getConnection()->insert($tableName, $item);
            }
        }
        $setup->endSetup();
    }
}