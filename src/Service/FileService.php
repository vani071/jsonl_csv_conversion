<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\ProcessingService;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class FileService 
{

    private $client;
    private $processingService;
    private $projectDir;

    public function __construct(HttpClientInterface $client,ProcessingService $processingService,KernelInterface $kernel )
    {
        $this->client = $client;
        $this->processingService = $processingService;
        $this->projectDir = $kernel->getProjectDir();
    }

    public function fetchFileJsonLine(string $url): string
    {
        $response = $this->client->request(
            'GET',
            $url
        );

        if($response->getStatusCode() >= 500 || $response->getStatusCode() >= 400 ) return "Can't get json lines file";
        
        $content = $response->getContent();

        $response = $this->createFileCsv($content);
        return "success create file . file location = ".$response;
    }

    public function createFileCsv(string $data){

        $filesystem = new Filesystem();
        $reportPath = $this->projectDir.'/report';
        $header = ['order_id','order_datetime','total_order_value','average_unit_price','distinct_unit_count','total_units_count','customer_state'];

        try {
            if(!$filesystem->exists($reportPath)) 
                $filesystem->mkdir($reportPath);
            $currentTime = time();
            $filePath = $reportPath.'/'.$currentTime.'-report.csv';
            $filesystem->touch($filePath,0777);

            $outputBuffer = fopen($filePath, 'w');
            
            fputcsv($outputBuffer,$header);

            $arr = explode(PHP_EOL, $data);
            foreach($arr as $row) {
                if(empty($row)) continue;
                
                $rowData=$this->processingService->generateRowData($row);

                if(count( $rowData) == 0 ) continue;
                
                fputcsv($outputBuffer,$rowData);
            }
            fclose($outputBuffer);

            return $filePath;

        } catch (IOExceptionInterface $exception) {
           throw new \Exception("An error occurred while creating your directory at ".$exception->getPath(), 1); 
        }
    }

    
}
