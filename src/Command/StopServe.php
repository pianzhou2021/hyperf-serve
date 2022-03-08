<?php
/*
 * @Description: Stop Hyper Server Command
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-03-05 22:34:25
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-03-06 21:18:25
 */

declare(strict_types=1);

namespace Hyperf\Serve\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use Hyperf\Contract\ConfigInterface;

/**
 * @Command
 */
#[Command]
class StopServe extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('serve:stop');
        $this->setDescription('Stop Hyperf Servers.');
    }
    
    /**
     * main
     *
     * @return void
     */
    public function handle()
    {
        $config = $this->container->get(ConfigInterface::class);
        $pidFile = $config->get('server.settings.pid_file', BASE_PATH . '/runtime/hyperf.pid');
        
        if (file_exists($pidFile)) {
            $pid = intval(file_get_contents($pidFile));
            if (!$pid || !\Swoole\Process::kill($pid, 0)) {
                $this->error("pid :{$pid} not exist ");
                unlink($pidFile);
            } else {
                \Swoole\Process::kill($pid);
            }
        } else {
            $this->error("pid file does not exist, please check whether to run in the daemon mode!");
        }
    }
}
