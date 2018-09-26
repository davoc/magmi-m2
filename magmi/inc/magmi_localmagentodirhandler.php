<?php 

namespace Magmi\Inc;

use Magmi\Inc\MagentoDirHandler;
use Magmi\Inc\RemoteFileGetterFactory;

/**
 * Local Magento Dir Handler.
 *
 * Handle Magento related filesystem operations for a given local directory
 *
 * @author dweeves
 *
 */
class LocalMagentoDirHandler extends MagentoDirHandler
{
    protected $_rfgid;

    /**
     * Constructor
     *
     * @param unknown $magdir
     */
    public function __construct($magdir)
    {
        parent::__construct($magdir);
        // Registers itself in the factory
        MagentoDirHandlerFactory::getInstance()->registerHandler($this);
        $this->_rfgid = "default";
    }

    /**
     * Can Handle any non remote urls
     *
     * @param unknown $url
     * @return boolean
     */
    public function canHandle($url)
    {
        return (preg_match("|^.*?://.*$|", $url) == false);
    }

    /**
     * Cleans a bit input filename, ensures filename will be located under magento directory if not already
     *
     * @see MagentoDirHandler::file_exists()
     */
    public function file_exists($filename)
    {
        $mp = str_replace("//", "/", $this->_magdir . "/" . str_replace($this->_magdir, '', $filename));

        return file_exists($mp);
    }

    /**
     * Specific, set remote operation credentials for local file download
     */
    public function setRemoteCredentials($user, $passwd)
    {
        $fginst = RemoteFileGetterFactory::getFGInstance($this->_rfgid);
        $fginst->setCredentials($user, $passwd);
    }

    /**
     * Handles a remote file getter id
     *
     * @param unknown $rfgid
     */
    public function setRemoteGetterId($rfgid)
    {
        $this->_rfgid = $rfgid;
    }

    /**
     * ensures dirname will be located under magento directory if not already
     *
     * @see MagentoDirHandler::mkdir()
     */
    public function mkdir($path, $mask = null, $rec = false)
    {
        $mp = str_replace("//", "/", $this->_magdir . "/" . str_replace($this->_magdir, '', $path));

        if ($mask == null) {
            $mask = octdec('755');
        }
        $ok = @mkdir($mp, $mask, $rec);
        if (!$ok) {
            $this->_lasterror = error_get_last();
        }
        return $ok;
    }

    /**
     * ensures path will be located under magento directory if not already
     *
     * @see MagentoDirHandler::chmod()
     */
    public function chmod($path, $mask)
    {
        $mp = str_replace("//", "/", $this->_magdir . "/" . str_replace($this->_magdir, '', $path));

        if ($mask == null) {
            $mask = octdec('755');
        }
        $ok = @chmod($mp, $mask);
        if (!$ok) {
            $this->_lasterror = error_get_last();
        }
        return $ok;
    }

    /**
     * Returns last error
     *
     * @return Ambigous <multitype:, multitype:string multitype: >
     */
    public function getLastError()
    {
        return $this->_lasterror;
    }

    /**
     * ensures filename will be located under magento directory if not already
     *
     * @see MagentoDirHandler::unlink()
     */
    public function unlink($path)
    {
        $mp = str_replace("//", "/", $this->_magdir . "/" . str_replace($this->_magdir, '', $path));
        return @unlink($mp);
    }

    /**
     * Download a file into local filesystem
     * ensures local filename will be located under magento directory if not already
     *
     * @param unknown $remoteurl
     * @param unknown $destpath
     * @return unknown
     */
    public function copyFromRemote($remoteurl, $destpath)
    {
        $rfg = RemoteFileGetterFactory::getFGInstance($this->_rfgid);
        $mp = str_replace("//", "/", $this->_magdir . "/" . str_replace($this->_magdir, '', $destpath));
        $ok = $rfg->copyRemoteFile($remoteurl, $mp);
        if (!$ok) {
            $this->_lasterror = $rfg->getErrors();
        }
        return $ok;
    }

    /**
     * ensures filename will be located under magento directory if not already
     *
     * @see MagentoDirHandler::copy()
     */
    public function copy($srcpath, $destpath)
    {
        $result = false;
        $destpath = str_replace("//", "/", $this->_magdir . "/" . str_replace($this->_magdir, '', $destpath));
        if (preg_match('|^.*?://.*$|', $srcpath)) {
            $result = $this->copyFromRemote($srcpath, $destpath);
        } else {
            $result = @copy($srcpath, $destpath);
            if (!$result) {
                $this->_lasterror = error_get_last();
            }
        }
        return $result;
    }

    /**
     * execute command, performs some execution directory check
     * uses available command execution method
     *
     * @see MagentoDirHandler::exec_cmd()
     */
    public function exec_cmd($cmd, $params, $working_dir = null)
    {
        $full_cmd = $cmd . " " . $params;
        $curdir = false;
        $precmd = "";
        // If a working directory has been specified, switch to it
        // before running the requested command
        if (!empty($working_dir)) {
            $curdir = getcwd();
            $wdir = realpath($working_dir);
            // get current directory
            if ($curdir != $wdir && $wdir !== false) {
                // trying to change using chdir
                if (!@chdir($wdir)) {
                    // if no success, use cd from shell
                    $precmd = "cd $wdir && ";
                }
            }
        }
        $full_cmd = $precmd . $full_cmd;
        // Handle Execution
        $emode = $this->getexecmode();
        switch ($emode) {
            case "popen":
                $x = popen($full_cmd, "r");
                $out = "";
                while (!feof($x)) {
                    $data = fread($x, 1024);
                    $out .= $data;
                    usleep(100000);
                }
                fclose($x);
                break;
            case "shell_exec":
                $out = shell_exec($full_cmd);
                break;
        }

        // restore old directory if changed
        if ($curdir) {
            @chdir($curdir);
        }

        if ($out == null) {
            $this->_lasterror = array("type"=>" execution error","message"=>error_get_last());
            return false;
        }
        return $out;
    }
}
