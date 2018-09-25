<?php

namespace Magmi\Integration\Inc;

use Magmi\Inc\Properties;
use Magmi\Integration\Inc\Magmi_ProductImport_DataPump;

class Magmi_DataPumpFactory
{
    protected static $_factoryprops = null;

    /**
     * Undocumented variable
     *
     * @var \Magento\Framework\App\ProductMetadataInterface 
     */
    protected $_productMetadata;

    /**
     * Constructor
     */
    public function __construct(\Magento\Framework\App\ProductMetadataInterface $productMetadata)
    {
        $this->_productMetadata = $productMetadata;
    }

    public static function getDataPumpInstance($pumptype)
    {
        if (self::$_factoryprops == null) {
            self::$_factoryprops = new Properties();
            self::$_factoryprops->load(dirname(__FILE__) . DIRSEP . "pumpfactory.ini");
        }
        $pumpinfo = self::$_factoryprops->get("DATAPUMPS", $pumptype, "");
        $arr = explode("::", $pumpinfo);
        if (count($arr) == 2) {
            $pumpfile = $arr[0];
            $pumpclass = $arr[1];

            try {
                //require_once(dirname(__FILE__) . DIRSEP . "$pumpfile.php");
                $pumpinst = new $pumpclass($this->_productMetadata);
            } catch (Exception $e) {
                $pumpinst = null;
            }
        } else {
            echo "Invalid Pump Type";
        }
        return $pumpinst;
    }
}
