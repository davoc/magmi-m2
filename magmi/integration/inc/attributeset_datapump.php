<?php

namespace Magmi\Integration\Inc;

use Magmi_ProductImport_DataPump;
use Magmi\Inc\ArrayReader;

class Magmi_AttributeSet_DataPump extends Magmi_ProductImport_DataPump
{
    /**
     * Constructor
     */
    public function __construct(\Magento\Framework\App\ProductMetadataInterface $productMetadata)
    {
        parent::__construct($productMetadata);
    }
    
    public function ingestAttributes($items = array())
    {
        $reader = new ArrayReader();
        $reader->initialize($items);
        $this->_engine->callPlugins("general", "importAttributes", $reader);
    }

    public function ingestAttributeSets($items = array())
    {
        $reader = new ArrayReader();
        $reader->initialize($items);
        $this->_engine->callPlugins("general", "importAttributeSets", $reader);
    }

    public function ingestAttributeAsociations($items = array())
    {
        $reader = new ArrayReader();
        $reader->initialize($items);
        $this->_engine->callPlugins("general", "importAttributeAssociations", $reader);
    }

    public function cleanupAttributes()
    {
        $this->_engine->callPlugins("general", "deleteUnreferencedAttributes");
    }
}
