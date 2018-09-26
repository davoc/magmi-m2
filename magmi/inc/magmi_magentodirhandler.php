<?php

namespace Magmi\Inc;

use Magmi\Inc\RemoteFileGetter;

/**
 * Factory for magento directory handle
 *
 * @author dweeves
 *
 */
class MagentoDirHandlerFactory 
{
    protected $_handlers = array();
    protected static $_instance;

    public function __construct()
    {
    }

    /**
     * Singleton getInstance method
     *
     * @return MagentoDirHandlerFactory
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new MagentoDirHandlerFactory();
        }
        return self::$_instance;
    }

    /**
     * Registers a new object to handle magento directory
     *
     * @param unknown $obj
     */
    public function registerHandler($obj)
    {
        $cls = get_class($obj);
        if (!isset($this->_handlers[$cls])) {
            $this->_handlers[$cls] = $obj;
        }
    }

    /**
     * Return a handler for a given url
     *
     * @param unknown $url
     * @return unknown
     */
    public function getHandler($url)
    {
        // Iterates on declared handlers , return first matching url
        foreach ($this->_handlers as $cls => $handler) {
            if ($handler->canHandle($url)) {
                return $handler;
            }
        }
    }
}