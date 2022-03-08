<?php
/*
 * @Description: Config for Hyperf Server
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-03-06 20:17:04
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-03-07 22:30:23
 */

declare(strict_types=1);
namespace Hyperf\Serve;

use Hyperf\Serve\Command\ReloadServe;
use Hyperf\Serve\Command\StopServe;

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
            'id' => 'code watch',
            'description' => 'The code watch for server.',
            'source' => __DIR__ . '/../publish/watch/',
            'destination' => BASE_PATH . '/bin/watch',
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
