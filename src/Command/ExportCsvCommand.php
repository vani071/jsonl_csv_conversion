<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\FileService;

class ExportCsvCommand extends Command
{

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:export-csv';

    private $fileService;

    public function __construct(FileService $fileService){
        $this->fileService = $fileService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try{
            $output->writeln($this->fileService->fetchFileJsonLine('https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl'));
            return Command::SUCCESS;    
        }catch(Exception $e){
            $output->writeln($e->getMessage());
            return Command::FAILURE;   
        }

    }
}
