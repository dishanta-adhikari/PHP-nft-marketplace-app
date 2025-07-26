<?php

namespace App\Helpers;

class Cache
{
    private static function path(string $key): string
    {
        $dir = __DIR__ . '/../cache';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir . '/' . md5($key) . '.cache';
    }


    public static function get(string $key, int $ttl = 300)
    {
        $file = self::path($key);

        if (!file_exists($file)) return false;

        $data = unserialize(file_get_contents($file));

        if (time() - $data['timestamp'] > $ttl) {
            unlink($file); // expired
            return false;
        }

        return $data['value'];
    }

    public static function set(string $key, $value)
    {
        $file = self::path($key);
        $data = ['timestamp' => time(), 'value' => $value];
        file_put_contents($file, serialize($data));
    }

    public static function delete(string $key)
    {
        $file = self::path($key);
        if (file_exists($file)) unlink($file);
    }
}
