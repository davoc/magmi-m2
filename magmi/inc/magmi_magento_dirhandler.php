<?php

namespace Magmi\Inc;

use Magmi\Inc\RemoteFileGetter;

/**
 * Magento Directory Handler
 *
 * Provides methods for filesystem operations & command execution
 * Mother abstract class to be derived either for local operation or remote (for performing operations on remote systems)
 *
 * @author dweeves
 *
 */
abstract class MagentoDirHandler
{
    protected $_magdir;
    protected $_lasterror;
    protected $_exec_mode;

    /**
     * Constructor from a magento directory url
     *
     * @param unknown $magurl
     *            magento base directory url
     */
    public function __construct($magurl)
    {
        $this->_magdir = $magurl;
        $this->_lasterror = array();
        $this->_exec_mode = FSHelper::getExecMode();
    }

    /**
     * Returns magento directory
     *
     * @return string
     */
    public function getMagentoDir()
    {
        return $this->_magdir;
    }

    /**
     * Returns available execution mode
     *
     * @return Ambigous <string, NULL>
     */
    public function getexecmode()
    {
        return $this->_exec_mode;
    }

    /**
     * Wether current handler is compatible with given url
     *
     * @param unknown $url
     */
    abstract public function canhandle($url);

    /**
     * File exists
     *
     * @param unknown $filepath
     */
    abstract public function file_exists($filepath);

    /**
     * Mkdir
     *
     * @param unknown $path
     * @param string $mask
     * @param string $rec
     */
    abstract public function mkdir($path, $mask = null, $rec = false);

    /**
     * File Copy
     *
     * @param unknown $srcpath
     * @param unknown $destpath
     */
    abstract public function copy($srcpath, $destpath);

    /**
     * File Deletion
     *
     * @param unknown $path
     */
    abstract public function unlink($path);

    /**
     * Chmod
     *
     * @param unknown $path
     * @param unknown $mask
     */
    abstract public function chmod($path, $mask);

    /**
     * Check if we can execute processes
     *
     * @return boolean
     */
    public function isExecEnabled()
    {
        return $this->_exec_mode != null;
    }

    /**
     * Executes a process
     *
     * @param unknown $cmd
     * @param unknown $params
     * @param string $workingdir
     */
    abstract public function exec_cmd($cmd, $params, $workingdir = null);
}