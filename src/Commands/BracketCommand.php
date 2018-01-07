<?php
namespace CLI\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BracketCommand extends Command {

    protected function configure()
    {
        $this
            ->setName('correct-brackets')
            ->setDescription('Return true if the line is correct - all brackets are correctly opened and closed, or false otherwise.')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Enter filename with the extension')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try
        {
            if(empty($input->getArgument('filename')))
                throw new \Exception('No filename received.');

            $line = $this->getContent($input->getArgument('filename'));
            $bkt = new \Library\Services\BracketService($line);

            $this->delimiter($output, 'Input string: '.$line, 'Result: '.(($bkt->check())?'OK':'Error'));

        }catch (\Exception $e){
            $this->delimiter($output, 'ERROR! '.$e->getMessage());
        }

    }

    protected function getContent(string $path):string
    {
        if (!file_exists($path))
            throw new \Exception('File ' . $path . ' not found');

        $content = file_get_contents($path);

        if (empty($content))
            throw new \Exception('Content is empty');

        return $content;
    }

    protected function delimiter(OutputInterface $output, ...$args){
        $output->writeln('--------------------------------');
        foreach($args as $item) {
            $output->writeln($item);
            $output->writeln('--------------------------------');
        }
    }


}