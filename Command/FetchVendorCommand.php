<?php
/**
 * @class FetchVendorCommand
 *
 */
namespace Avanzu\AdminThemeBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;

class FetchVendorCommand extends Command
{
    protected static $defaultName = 'avanzu:admin:fetch-vendor';
    protected static $defaultDescription = 'Fetch vendor assets';

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(KernelInterface $kernel, ParameterBagInterface $params)
    {
        parent::__construct();
        $this->kernel = $kernel;
        $this->params = $params;
    }

    protected function configure(): void
    {
        $this->addOption('update', 'u', InputOption::VALUE_NONE, 'perform update instead of install')
            ->addOption('root', 'r', InputOption::VALUE_NONE, 'allow bower to run as root');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bowerResource = $this->kernel->locateResource('@AvanzuAdminThemeBundle/Resources/bower');
        $helper = $this->getHelperSet()->get('formatter');
        /** @var $helper FormatterHelper */
        $bower = $this->params->get('avanzu_admin_theme.bower_bin');

        $action = $input->getOption('update') ? 'update' : 'install';
        $asRoot = $input->getOption('root') ? '--allow-root' : '';
        $process = new Process([$bower, $action, $asRoot]);
        $process->setTimeout(600);

        $output->writeln($helper->formatSection('Executing', $process->getCommandLine(), 'comment'));
        $process->setWorkingDirectory($bowerResource);
        $process->run(function ($type, $buffer) use ($output, $helper)
        {
            if(Process::ERR == $type)
            {
                $output->write($helper->formatSection('Error', $buffer, 'error'));
            }
            else
            {
                $output->write($helper->formatSection('Progress', $buffer, 'info'));
            }
        });

        $output->writeln($helper->formatSection('Done. You should now execute', 'php bin/console assets:install', 'comment'));
        return Command::SUCCESS;
    }
}
