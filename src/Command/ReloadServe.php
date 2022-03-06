<?php
/*
 * @Description: 
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-03-05 22:34:25
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-03-06 20:37:56
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
class ReloadServe extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('serve:reload');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Reload Hyperf Servers.');
    }

    public function handle()
    {
        $config = $this->container->get(ConfigInterface::class);
        $pidFile = $config->get('server.settings.pid_file', BASE_PATH . '/runtime/hyperf.pid');
        
        if (file_exists($pidFile)) {
            self::opCacheClear();
            $pid = intval(file_get_contents($pidFile));
            if (!$pid || !\Swoole\Process::kill($pid, 0)) {
                $this->error("pid :{$pid} not exist ");
            } else {
                \Swoole\Process::kill($pid, SIGUSR1);
                $this->info("send server reload command to pid:{$pid} at " . date("Y-m-d H:i:s"));
            }
        } else {
            $this->error("pid file does not exist, please check whether to run in the daemon mode!");
        }
    }

    public static function opCacheClear()
    {
        if (function_exists('apc_clear_cache')) {
            apc_clear_cache();
        }
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }
}
