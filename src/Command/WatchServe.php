<?php
/*
 * @Description: 
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-03-05 22:34:25
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-03-06 21:36:02
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

        parent::__construct('serve:watch');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Watch Hyperf Servers.');
    }

    public function handle()
    {
        $command = 'foo';

        $params = ["command" => $command, "--foo" => "foo", "--bar" => "bar"];

        // 可以根据自己的需求, 选择使用的 input/output
        $input = new ArrayInput($params);
        $output = new NullOutput();

        /** @var \Psr\Container\ContainerInterface $container */
        $container = \Hyperf\Utils\ApplicationContext::getContainer();

        /** @var \Symfony\Component\Console\Application $application */
        $application = $container->get(\Hyperf\Contract\ApplicationInterface::class);
        $application->setAutoExit(false);

        // 这种方式: 不会暴露出命令执行中的异常, 不会阻止程序返回
        $exitCode = $application->run($input, $output);
    }
}
