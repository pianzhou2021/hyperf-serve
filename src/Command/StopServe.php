<?php
/*
 * @Description: 
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
use Symfony\Component\Console\Input\InputArgument;

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
    /**
     * 执行的命令行
     *
     * @var string
     */
    protected $name = 'serve:stop';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('serve:stop');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Stop Hyperf Servers.');
    }

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
                $force = $this->input->getArgument('force');
                if ($force) {
                    \Swoole\Process::kill($pid, SIGKILL);
                } else {
                    \Swoole\Process::kill($pid);
                }
                //等待5秒
                $time = time();
                while (true) {
                    usleep(1000);
                    if (!\Swoole\Process::kill($pid, 0)) {
                        if (is_file($pidFile)) {
                            unlink($pidFile);
                        }
                        $this->info("server stop for pid {$pid} at " . date("Y-m-d H:i:s"));
                        break;
                    } else {
                        if (time() - $time > 15) {
                            $this->error("stop server fail for pid:{$pid} , try [php bin/hypfer serve:stop -force] again");
                            break;
                        }
                    }
                }

            }
        } else {
            $this->error("pid file does not exist, please check whether to run in the daemon mode!");
        }
    }

    protected function getArguments()
    {
        return [
            ['force', InputArgument::OPTIONAL, 'force stop']
        ];
    }
}
