<?php
/*
 * @Description: Config for Hyperf Server
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-03-06 20:17:04
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-03-08 20:34:33
 */

declare(strict_types=1);
namespace PianZhou\Hyperf\Serve;

use PianZhou\Hyperf\Serve\Command\ReloadServe;
use PianZhou\Hyperf\Serve\Command\StopServe;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'listeners' => [
            ],
            'commands' => [
                ReloadServe::class,
                StopServe::class,
            ],
            'annotations' => [
            ],
            'publish' => [
                $this->publishWatch(),
            ],
        ];
    }

    /**
     * publish watch
     *
     * @return array
     */
    protected function publishWatch()
    {
        $config = [
            'id' => 'watcher',
            'description' => 'The code watcher for server.',
            'source' => __DIR__ . '/../publish/watcher/',
            'destination' => BASE_PATH . '/bin/watcher',
        ];

        $os = $this->os();
        
        $config['source']   = $config['source'] . $os . '-' . $this->arch();

        if ($os == 'WINDOWS') {
            $config['source']       = $config['source'].'.exe';
            $config['destination']   = $config['destination'].'.exe';
        }

        return $config;
    }

    /**
     * get os type
     *
     * @return string
     */
    protected function os()
    {
        if (stristr(PHP_OS, 'DAR')) {
            return 'MAC';
        } elseif(stristr(PHP_OS, 'WIN')) {
            return 'WINDOWS';
        } elseif(stristr(PHP_OS, 'LINUX')) {
            return 'LINUX';
        } else {
            return 'FREEBSD';
        }
    }

    /**
     * get server arch
     *  
     * x86_64 i386 ARM
     * @return string
     */
    protected function arch()
    {
        return strtoupper(php_uname('m'));
    }
}
