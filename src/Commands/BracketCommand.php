<?php

namespace CLI\Commands;

use CLI\Exceptions\FileException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BracketCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('correct-brackets')
            ->setDescription(
                'Return true if the line is correct - all brackets'.
                'are correctly opened and closed, or false otherwise.'
            )
            ->addArgument(
                'filename',
                InputArgument::OPTIONAL,
                'Enter filename with the extension'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            if (empty($input->getArgument('filename')))
                throw new \Exception('No filename received.');

            $line = $this->getContent($input->getArgument('filename'));

            $bracketLibrary = new \Library\Command\BracketCommand($line);
            $isCorrect = $bracketLibrary->execute();

            $io->section(sprintf('Input string: %s', $line));

            if ($isCorrect)
                $io->success("Brackets are opened and closed correctly");
            else
                $io->warning("Brackets are open and closed incorrectly");

        } catch (\Library\Exceptions\InvalidArgumentException $e) {
            $io->warning(sprintf("ERROR! %s", $e->getMessage()));
        } catch (\Exception $e) {
            $io->warning(sprintf("ERROR! %s", $e->getMessage()));
        }
    }

    /**
     * @param string $path
     * @return string
     *
     * @throws FileException
     */
    protected function getContent(string $path): string
    {
        if (!file_exists($path))
            throw new FileException(sprintf("File %s not found", $path));

        $content = file_get_contents($path);

        if (empty($content))
            throw new FileException('Content is empty');

        return $content;
    }
}