<?php declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\FileService;


class FileServiceTest extends WebTestCase
{
    public function testFileJsonNotFound(): void
    {
        $fileService = static::getContainer()->get(FileService::class);
        $response = $fileService->fetchFileJsonLine('https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-3-in.jsonl');
        $this->assertEquals($response,"Can't get json lines file");
        
    }

    public function testBestCaseScenario(): void
    {
        $fileService = static::getContainer()->get(FileService::class);
        $response = $fileService->fetchFileJsonLine('https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl');
        $this->assertMatchesRegularExpression('/success/', $response);
    }
}