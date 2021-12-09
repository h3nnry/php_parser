<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Parser\Formats;
use App\Parser\ParserFactory;
use App\Parser\Exceptions\MissingParserException;


/**
 * Class ChangeFileFormatCommand
 * @package App\Commands
 */
class ChangeFileFormatCommand extends Command
{
    protected string $path;
    protected string $output;
    protected string $input = "xml";

    /**
     * Configure current command.
     */
    protected function configure(): void
    {
        $this->setName("change_file_format")
            ->setDescription("Change file format command.")
            ->setHelp("Provide file path(initial format is xml) and output format(csv, xlsx, json) in order to 
                change file format.")
            ->addArgument("path", InputArgument::REQUIRED)
            ->addArgument("output", InputArgument::REQUIRED);
    }

    /**
     * Initializes the command after the input has been bound and before the input
     * is validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @see InputInterface::bind()
     * @see InputInterface::validate()
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->path = $this->loadArgument("path", $input);
        $this->output = $this->loadArgument("output", $input);

        if (!file_exists($this->path)) {
            throw new InvalidArgumentException("File doesn't exists in provided path.");
        }

        $fileInfo = pathinfo($this->path);
        if ($fileInfo["extension"] !== Formats::FORMAT_XML) {
            throw new InvalidArgumentException("File extension must be xml.");
        }
    }

    /**
     * @param string $name
     * @param InputInterface $input
     * @return string|null
     */
    protected function loadArgument(string $name, InputInterface $input): ?string
    {
        foreach ($input->getArguments() as $key => $value) {
            if ($name == $key) {
                if (isset($value)) {
                    return $value;
                } else {
                    throw new InvalidArgumentException("Value for {$key} is missing.");
                }
            }
        }
        return null;
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @return int 0 if everything went fine, or an exit code
     *
     * @throws LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $parser = ParserFactory::convert($this->input, $this->output);
        } catch (MissingParserException $e) {
            throw new LogicException($e->getMessage());
        }

        $parser->convert($this->path);

        $output->writeln("File successfully exported.");
        return 0;
    }
}