<?php
/**
 * Uruguay Regions
 *
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author     Damián Culotta (http://www.damianculotta.com.ar/)
 */

namespace Barbanet\UruguayRegions\Setup;

use Magento\Directory\Helper\Data;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class InstallData implements InstallDataInterface
{

    /**
     * Directory data
     *
     * @var Data
     */
    protected $directoryData;

    /**
     * Init
     *
     * @param Data $directoryData
     */
    public function __construct(Data $directoryData)
    {
        $this->directoryData = $directoryData;
    }


    /**
     * Install Data
     *
     * @param ModuleDataSetupInterface $setup   Module Data Setup
     * @param ModuleContextInterface   $context Module Context
     *
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Fill table directory/country_region
         * Fill table directory/country_region_name for en_US locale
         */
        $data = [
            ['UY', 'AR', 'Artigas'],
            ['UY', 'CA', 'Canelones'],
            ['UY', 'CL', 'Cerro Largo'],
            ['UY', 'CO', 'Colonia'],
            ['UY', 'DU', 'Durazno'],
            ['UY', 'FS', 'Flores'],
            ['UY', 'FD', 'Florida'],
            ['UY', 'LA', 'Lavalleja'],
            ['UY', 'MA', 'Maldonado'],
            ['UY', 'MO', 'Montevideo'],
            ['UY', 'PA', 'Paysandu'],
            ['UY', 'RN', 'Río Negro'],
            ['UY', 'RV', 'Rivera'],
            ['UY', 'RO', 'Rocha'],
            ['UY', 'SA', 'Salto'],
            ['UY', 'SJ', 'San José'],
            ['UY', 'SO', 'Soriano'],
            ['UY', 'TA', 'Tacuarembó'],
            ['UY', 'TT', 'Treinta y Tres']
        ];

        foreach ($data as $row) {
            $bind = ['country_id' => $row[0], 'code' => $row[1], 'default_name' => $row[2]];
            $setup->getConnection()->insert($setup->getTable('directory_country_region'), $bind);
            $regionId = $setup->getConnection()->lastInsertId($setup->getTable('directory_country_region'));

            $bind = ['locale' => 'en_US', 'region_id' => $regionId, 'name' => $row[2]];
            $setup->getConnection()->insert($setup->getTable('directory_country_region_name'), $bind);
        }
    }
}