<?php

namespace Ebl\Logs;

use Exception;

class LogsDelete
{

    public function __construct()
    {
    }

    public function logDelete($path, $type = "", $value)
    {
        if (is_dir($path)) {
            try {
                if ($handle = opendir($path)) {
                    while (false !== ($file = readdir($handle))) {
                        if (preg_match("/^.*\.(log)$/i", $file)) {
                            $fpath = $path . $file;
                            if (file_exists($fpath)) {
                                // that filemtime returns the time of last modification of the file, instead of creation date.
                                // if ((time() - $filelastmodified) > 2 * 24 * 3600) {
                                if ($type == 'day') {
                                    $filelastmodified = filemtime($fpath);
                                    if ((time() - $filelastmodified) > 24 * 3600) {
                                        unlink($fpath);
                                    }
                                }
                                if ($type == 'size') {
                                    $size = filesize($fpath);
                                    if ($size > $value) {
                                        unlink($fpath);
                                    }
                                }
                            }
                        }
                    }
                    closedir($handle);
                }
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            }
        } else {
            echo 'Message: Path Not Found';
        }
    }
}