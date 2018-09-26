<?php

namespace Magmi\Inc;

use Magmi\Inc\RemoteFileGetter;

/**
 * Class FSHelper
 *
 * File System Helper
 * Gives several utility methods for filesystem testing
 *
 * @author dweeves
 *
 */
class FSHelper
{
    /**
     * Checks if a directory has write rights
     *
     * @param string $dir
     *            directory to test
     * @return boolean wether directory is writable
     */
    public static function isDirWritable($dir)
    {
        // try to create a new file
        $test = @fopen("$dir/__testwr__", "w");
        if ($test == false) {
            return false;
        } else {
            // if succeeded, remove test file
            fclose($test);
            unlink("$dir/__testwr__");
        }
        return true;
    }

    /**
     * Tries to find a suitable way to execute processes
     *
     * @return string NULL method to execute process
     */
    public static function getExecMode()
    {
        $is_disabled = array();
        // Check for php disabled functions
        $disabled = explode(',', ini_get('disable_functions'));
        foreach ($disabled as $disableFunction) {
            $is_disabled[] = trim($disableFunction);
        }
        // try the following if not disabled,return first non disabled
        foreach (array("popen", "shell_exec") as $func) {
            if (!in_array($func, $is_disabled)) {
                return $func;
            }
        }
        return null;
    }
}





