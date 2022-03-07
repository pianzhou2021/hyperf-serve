<?php
/*
 * @Description: 
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-03-06 20:17:04
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-03-07 22:30:23
 */

declare(strict_types=1);
namespace Hyperf\Serve;

use Swoole\Server as SwooleServer;
use Hyperf\Serve\Command\ReloadServe;
use Hyperf\Serve\Command\StopServe;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                SwooleServer::class => SwooleServerFactory::class,
            ],
            'listeners' => [
            ],
            'commands' => [
                ReloadServe::class,
                StopServe::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                $this->publishNotify(),
            ],
        ];
    }

    /**
     * 发布notify
     *
     * @return array
     */
    protected function publishNotify()
    {
        $config = [
            'id' => 'config',
            'description' => 'The notify for server.',
            'source' => __DIR__ . '/../publish/notify/',
            'destination' => BASE_PATH . '/bin/notify',
        ];

        $os = $this->os();
        
        $config['source']   = $config['source'] . $os . '-' . $this->arch() . '-' . 'notify';

        if ($os == 'WINDOWS') {
            $config['source']       = $config['source'].'.exe';
            $config['destination']   = $config['destination'].'.exe';
        }

        return $config;
    }

    /**
     * 判断操作系统类型
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
     * 操作系统架构
     *  
     * x86_64 i386 ARM
     * @return string
     */
    protected function arch()
    {
        return strtoupper(php_uname('m'));
    }
}
