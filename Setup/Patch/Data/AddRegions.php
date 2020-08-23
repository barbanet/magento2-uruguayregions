<?php
/**
 * Uruguay Regions
 *
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author     Damián Culotta (http://www.damianculotta.com.ar/)
 */

declare(strict_types=1);

namespace Barbanet\UruguayRegions\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddRegions implements DataPatchInterface, PatchRevertableInterface
{
    const COUNTRY_CODE = 'UY';

    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /**
         * Fill table directory/country_region
         * Fill table directory/country_region_name for en_US locale
         */
        $data = [
            [self::COUNTRY_CODE, 'AR', 'Artigas'],
            [self::COUNTRY_CODE, 'CA', 'Canelones'],
            [self::COUNTRY_CODE, 'CL', 'Cerro Largo'],
            [self::COUNTRY_CODE, 'CO', 'Colonia'],
            [self::COUNTRY_CODE, 'DU', 'Durazno'],
            [self::COUNTRY_CODE, 'FS', 'Flores'],
            [self::COUNTRY_CODE, 'FD', 'Florida'],
            [self::COUNTRY_CODE, 'LA', 'Lavalleja'],
            [self::COUNTRY_CODE, 'MA', 'Maldonado'],
            [self::COUNTRY_CODE, 'MO', 'Montevideo'],
            [self::COUNTRY_CODE, 'PA', 'Paysandu'],
            [self::COUNTRY_CODE, 'RN', 'Río Negro'],
            [self::COUNTRY_CODE, 'RV', 'Rivera'],
            [self::COUNTRY_CODE, 'RO', 'Rocha'],
            [self::COUNTRY_CODE, 'SA', 'Salto'],
            [self::COUNTRY_CODE, 'SJ', 'San José'],
            [self::COUNTRY_CODE, 'SO', 'Soriano'],
            [self::COUNTRY_CODE, 'TA', 'Tacuarembó'],
            [self::COUNTRY_CODE, 'TT', 'Treinta y Tres']
        ];

        foreach ($data as $row) {
            $bind = ['country_id' => $row[0], 'code' => $row[1], 'default_name' => $row[2]];
            $this->moduleDataSetup->getConnection()->insert(
                $this->moduleDataSetup->getTable('directory_country_region'),
                $bind
            );

            $regionId = $this->moduleDataSetup->getConnection()->lastInsertId(
                $this->moduleDataSetup->getTable('directory_country_region')
            );

            $bind = ['locale' => 'en_US', 'region_id' => $regionId, 'name' => $row[2]];
            $this->moduleDataSetup->getConnection()->insert(
                $this->moduleDataSetup->getTable('directory_country_region_name'),
                $bind
            );
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Revert patch
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $tableDirectoryCountryRegionName = $this->moduleDataSetup->getTable('directory_country_region_name');
        $tableDirectoryCountryRegion = $this->moduleDataSetup->getTable('directory_country_region');

        $where = [
            'region_id IN (SELECT region_id FROM ' . $tableDirectoryCountryRegion . ' WHERE country_id = ?)' => self::COUNTRY_CODE
        ];
        $this->moduleDataSetup->getConnection()->delete(
            $tableDirectoryCountryRegionName,
            $where
        );

        $where = ['country_id = ?' => self::COUNTRY_CODE];
        $this->moduleDataSetup->getConnection()->delete(
            $tableDirectoryCountryRegion,
            $where
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
