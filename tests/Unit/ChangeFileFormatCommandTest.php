<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Command\ChangeFileFormatCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 *
 */
class ChangeFileFormatCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCheckOffer(): void
    {
        $command = new ChangeFileFormatCommand();
        $mockInput = $this->createMock(InputInterface::class);
        $mockOutput = $this->createMock(OutputInterface::class);
        $method = $mockInput->method('getArguments');
        $method->willReturn(['path' => 'NonExistentFile', 'output' => 'json']);

        $method = $mockInput->method('getArguments');
        $method->willReturn(['path' => 'NonExistentFile', 'output' => 'json']);

        $this->expectException(InvalidArgumentException::class);
        $res = $command->run($mockInput, $mockOutput);
    }
}