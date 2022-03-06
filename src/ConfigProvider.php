<?php
/*
 * @Description: 
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-03-06 20:17:04
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-03-06 21:28:28
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
            ],
        ];
    }
}
