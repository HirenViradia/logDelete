<?php

namespace Ebl\Logs;

use Exception;

class LogsDelete
{

    public function logSave($path, $key, $value)
    {
        try {
            $log = '["' . date('r\"') . "] " . $key . " : " . $value . "\n";
            if (is_dir($path)) {
                $filesArr = scandir($path);
                $count = count($filesArr) - 2;
                if ($handle = opendir($path)) {
                    while (false !== ($file = readdir($handle))) {
                        if (preg_match("/^.*\.(log)$/i", $file)) {
                            $fpath = $path . $file;
                            $size = filesize($fpath);
                            if (file_exists($fpath) && $size > 1024 && ($fpath == $path . 'logs.log')) {
                                rename($fpath, $path . "logs." . $count . '.log');
                                file_put_contents($path . '/logs.log', '');
                            }
                            file_put_contents($path . '/logs.log', $log, FILE_APPEND);
                        }
                    }
                    closedir($handle);
                }
            } else {
                mkdir($path);
                file_put_contents($path . '/logs.log', $log, FILE_APPEND);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logDelete($path, $type, $value, $day = 1)
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
                                if ($type == 'both') {
                                    $filelastmodified = filemtime($fpath);
                                    $size = filesize($fpath);
                                    if ($size > $value && (time() - $filelastmodified) > $day * 24 * 3600) {
                                        unlink($fpath);
                                    }
                                }

                                if ($type == 'day') {
                                    $filelastmodified = filemtime($fpath);
                                    if ((time() - $filelastmodified) > $value * 24 * 3600) {
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
