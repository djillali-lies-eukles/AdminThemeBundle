<?php
/**
 * CompactVendorCommand.php
 * avanzu-admin
 * Date: 15.02.14
 */

namespace Avanzu\AdminThemeBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Shell\Command as FCommand;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;

class CompactVendorCommand extends Command
{
    protected static $defaultName = 'avanzu:admin:compact-vendor';
    protected static $defaultDescription = 'Compact vendor assets';

    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct();
        $this->kernel = $kernel;
    }

    protected function configure(): void
    {
        $this->addArgument('theme', InputArgument::OPTIONAL, 'Which theme?', 'modern-touch')
            ->addOption('nojs', null, InputOption::VALUE_NONE, 'will skip js compression')
            ->addOption('nocss', null, InputOption::VALUE_NONE, 'will skip css compression')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$input->getOption('nojs')) {
            $this->compressVendorJs($output);
            $this->compressThemeJs($input, $output);
        }

        if (!$input->getOption('nocss')) {
            $this->compressThemeCss($input, $output);
        }

        $this->copyFonts($input, $output);
        $this->copyImages($input, $output);
        return Command::SUCCESS;
    }

    protected function getThemePath(string $type, InputInterface $input): string
    {
        $theme = $input->getArgument('theme');
        $themedir = strtr('@AvanzuAdminThemeBundle/Resources/vendor/bootflat/{type}',
                          [
                              '{theme}' => $theme,
                              '{type}' => $type,
                          ]);
        $vendors = $this->kernel->locateResource($themedir);

        return $vendors;
    }

    protected function copyFonts(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelperSet()->get('formatter');
        /** @var $helper FormatterHelper */
        $vendors = $this->getThemePath('fonts', $input);
        $target = $this->kernel->locateResource('@AvanzuAdminThemeBundle/Resources/public/fonts');

        $process = new Process(['rm', '-rf', $target . '/*']);
        $output->writeln($helper->formatSection('Executing', $process->getCommandLine(), 'comment'));
        $process->run();

        $process = new Process(['cp', '-R', $vendors . '/*', $target]);
        $output->writeln($helper->formatSection('Executing', $process->getCommandLine(), 'comment'));
        $process->run();
    }

    protected function copyImages(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelperSet()->get('formatter');
        /** @var $helper FormatterHelper */
        $vendors = $this->getThemePath('img', $input);
        $target = $this->kernel->locateResource('@AvanzuAdminThemeBundle/Resources/public/img');

        $process = new Process(['rm', '-rf', $target . '/*']);
        $output->writeln($helper->formatSection('Executing', $process->getCommandLine(), 'comment'));
        $process->run();

        $process = new Process(['cp', '-R', $vendors . '/*', $target]);
        $output->writeln($helper->formatSection('Executing', $process->getCommandLine(), 'comment'));
        $process->run();
    }

    protected function compressThemeCss(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelperSet()->get('formatter');
        /** @var $helper FormatterHelper */
        $vendors = $this->getThemePath('css', $input);

        $public = dirname(dirname(dirname($vendors))) . '/public';
        $script = $public . '/css/theme.min.css';

        $files = [
            dirname($vendors) . '/bootstrap/bootstrap.css',
            'font-awesome.css',
            'bootflat.css',
            'bootflat-extensions.css',
            'bootflat-square.css',
        ];

        $process = new Process(['/usr/local/share/npm/bin/uglifycss', implode(' ', $files), '> ' . $script]);
        $output->writeln($helper->formatSection('Executing', $process->getCommandLine(), 'comment'));
        $process->setWorkingDirectory($vendors);

        $process->run(function ($type, $buffer) use ($output, $helper) {
            if (Process::ERR == $type) {
                $output->write($helper->formatSection('Error', $buffer, 'error'));
            } else {
                $output->write($helper->formatSection('Progress', $buffer, 'info'));
            }
        });
    }

    protected function compressThemeJs(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelperSet()->get('formatter');
        /** @var $helper FormatterHelper */
        $vendors = $this->getThemePath('js', $input);

        $public = dirname(dirname(dirname($vendors))) . '/public';
        $script = $public . '/js/theme.min.js';

        $files = [
            'bootstrap.js',
            'jquery.icheck.js',
        ];

        $process = new Process(['/usr/local/bin/uglifyjs', implode(' ', $files), '-c', '-m', '-o', $script]);
        $output->writeln($helper->formatSection('Executing command', 'Compressing theme vendor scripts'));
        $output->writeln($helper->formatSection('Compressing', $process->getCommandLine(), 'comment'));
        $process->setWorkingDirectory($vendors);

        $process->run(function ($type, $buffer) use ($output, $helper) {
            if (Process::ERR == $type) {
                $output->write($helper->formatSection('Error', $buffer, 'error'));
            } else {
                $output->write($helper->formatSection('Progress', $buffer, 'info'));
            }
        });
    }

    protected function compressVendorJs(OutputInterface $output): void
    {
        $helper = $this->getHelperSet()->get('formatter');
        /** @var $helper FormatterHelper */
        $vendors = $this->kernel->locateResource('@AvanzuAdminThemeBundle/Resources/vendor/');

        $public = dirname($vendors) . '/public';
        $script = $public . '/js/vendors.js';

        $files = [
            'jquery/dist/jquery.js',
            'jquery-ui/jquery-ui.js',
            'fastclick/lib/fastclick.js',
            'jquery.cookie/jquery.cookie.js',
            'jquery-placeholder/jquery.placeholder.js',
            'underscore/underscore.js',
            'backbone/backbone.js',
            'backbone.babysitter/lib/backbone.babysitter.js',
            'backbone.wreqr/lib/backbone.wreqr.js',
            'marionette/lib/backbone.marionette.js',
            'momentjs/moment.js',
            'momentjs/lang/de.js',
            'spinjs/spin.js',
            'spinjs/jquery.spin.js',
            'holderjs/holder.js',
        ];

        $process = new Process(['/usr/local/bin/uglifyjs', implode(' ', $files), '-c', '-m', '-o', $script]);
        $output->writeln($helper->formatSection('Executing command', 'Compressing general vendor scripts'));
        $output->writeln($helper->formatSection('Compressing', $process->getCommandLine(), 'comment'));
        $process->setWorkingDirectory($vendors);

        $process->run(function ($type, $buffer) use ($output, $helper) {
            if (Process::ERR == $type) {
                $output->write($helper->formatSection('Error', $buffer, 'error'));
            } else {
                $output->write($helper->formatSection('Progress', $buffer, 'info'));
            }
        });
    }
}
