<?php

namespace App\Utilities;
include "../bootstrap/constants.php";

class CacheUtility
{
    protected static $cache_file;
    protected static $cache_enabled = CACHE_ENABLED;


    public static function init()
    {
        self::$cache_file = CACHE_DIR . md5($_SERVER['REQUEST_URI']) . ".json";
        if ($_SERVER['REQUEST_METHOD'] != 'GET')
            self::$cache_enabled = 0;
    }

    public static function cache_exits()
    {
        return (file_exists(self::$cache_file) && (time() - EXPIRE_TIME) < filemtime(self::$cache_file));
    }

    public static function start()
    {
        self::init();
        if (!self::$cache_enabled)
            return;
        if (self::cache_exits()) {
            Response::setHeaders();
            readfile(self::$cache_file);

            exit;
        }

        ob_start();
    }

    public static function end()
    {
        if (!self::$cache_enabled)
            return;

        # Cache the contents to a cache file_exists
        $cachedfile = fopen(self::$cache_file, 'w');

        fwrite($cachedfile, ob_get_contents());
        fclose($cachedfile);
        
        # Send the output to the browser
        ob_end_flush();
    }

    public static function flush()
    {
        $files = glob(CACHE_DIR . "*");         // get all file names
        foreach ($files as $file)               // iterate files
            if (is_file($file))
                unlink($file);
    }
}
