<?php
declare(strict_types=1);

namespace PianZhou\Hyperf\Serve\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use Hyperf\Contract\ConfigInterface;

/**
 * @Command
 */
#[Command]
class RestartServe extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct('serve:restart');
        $this->setDescription('Restart Hyperf Servers.');
    }

    /**
     * main
     *
     * @return void
     */
    public function handle()
    {
        $config = $this->container->get(ConfigInterface::class);
        $daemonize = $config->get('server.settings.'. \Swoole\Constant::OPTION_DAEMONIZE, false);
        
        $this->info("send server stop command at " . date("Y-m-d H:i:s"));
        $this->call("serve:stop");
        $this->info("send server start command at " . date("Y-m-d H:i:s"));

        $php    = 'php';
        if (strtolower(substr($_SERVER['_'], -3)) == 'php' || strtolower(substr($_SERVER['_'], -6)) == 'php.exe') {
            $php = $_SERVER['_'];
        }
        
        $command    = "{$php} " . realpath($_SERVER['SCRIPT_NAME']) . " start";
        if (!$daemonize) {
            $command    = $command . " &";
        }

        proc_open($command, [
            0 => STDIN,
            1 => STDOUT,
            2 => STDERR,
        ], $pipes);
    }
}
