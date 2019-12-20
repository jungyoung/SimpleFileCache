<?php
/*
    Manage as one cache file per person
*/

class FileCache
{
    // 파일저장 경로
    const PATH = '/home/cache';
    // 파일저장 확장자
    const EXTENSION = '.cache';

    // person id
    private $id = '';
    private $filePath = '';

    public function __construct($id)
    {
        if ($id) {
            $this->init($id);
        } else {
            echo 'cache not found id!';
        }
    }

    private function init($id)
    {
        $this->id = trim($id);
        $this->initFilePath();
    }

    public function get($cacheName)
    {
        $cacheName = trim($cacheName);

        $cache = $this->getCache();

        if (
            $cache === null ||
            !is_array($cache) ||
            !array_key_exists($cacheName, $cache)
        ) {
            $cacheData = false;
        } else {
            $cacheData = $cache[$cacheName];
            // 기간 만료됬으면 데이터 삭제함
            if ($this->isExpired($cacheData)) {
                unset($cache[$cacheName]);
                $this->setCache($cache);
                $cacheData = false;
            } else {
                $cacheData = unserialize($cacheData['data']);
            }
        }

        return $cacheData;
    }

    // time : 1 = 1 sec
    public function set($cacheName, $value, $time = 60)
    {
        $cacheName = trim($cacheName);

        $cacheData = [
            'setTime' => time(),
            'cacheTime' => (int) $time,
            'data' => serialize($value),
        ];

        $cache = $this->getCache();
        if (!$cache) {
            $cache = [];
        }
        $cache[$cacheName] = $cacheData;
        $this->setCache($cache);
    }

    private function getCache()
    {
        if (file_exists($this->filePath)) {
            $data = file_get_contents($this->filePath);
            $cache = json_decode($data, true);
        } else {
            $cache = null;
        }

        return $cache;
    }

    private function setCache($cache)
    {
        file_put_contents($this->filePath, json_encode($cache));
    }

    private function isExpired($cacheData)
    {
        $expiresOn = $cacheData['setTime'] + $cacheData['cacheTime'];

        return $expiresOn < time();
    }

    private function initFilePath()
    {
        $filePrefix = 'fileCache_';
        $this->filePath = self::PATH . '/' . $filePrefix . $this->id . self::EXTENSION;
    }
}
