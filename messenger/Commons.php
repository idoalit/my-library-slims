<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 10/06/20 18.14
 * @File name           : Commons.php
 */

namespace Messenger;

trait Commons
{
    protected $limit = 10;
    protected $offset = 0;
    protected $page = 1;

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    public function config($key)
    {
        global $sysconf;
        $keys = explode('.', $key);
        $config = null;
        foreach ($keys as $index => $key) {
            if ($index < 1 && isset($sysconf[$key])) {
                $config = $sysconf[$key];
                continue;
            }
            if (is_array($config) && isset($config[$key]))
                $config = $config[$key];
        }
        return $config;
    }
}