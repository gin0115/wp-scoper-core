<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Helper;

use Closure;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;

trait QuestionTrait
{
    /**
     * Prompts for a path and offers basic auto complete
     *
     * @see https://symfony.com/doc/current/components/console/helpers/questionhelper.html
     * @param string $questionText
     * @return \Closure(InputInterface $input, OutputInterface $output): string
     */
    private function requestPath(string $questionText): Closure
    {

        $helper = new QuestionHelper();
        $callback = function (string $userInput): array {

            // If string starts with ~, replace with user home directory
            if (strpos($userInput, '~') === 0) {
                $userInput = str_replace('~', $_SERVER['HOME'], $userInput);
            }


            // If user userInput start with a / or \
            if (preg_match('/^[\/\\\\]/', $userInput)) {
                $inputPath = preg_replace('/[^\/\\\\]*$/', '', $userInput);
            } else {
                $inputPath = $userInput;
            }

            $inputPath = \realpath($inputPath);
            $foundFilesAndDirs = is_string($inputPath) ? @scandir($inputPath) : [];

            return array_map(function ($dirOrFile) use ($inputPath) {
                return $inputPath . $dirOrFile;
            }, $foundFilesAndDirs);
        };

        $question = new Question($questionText);
        $question->setAutocompleterCallback($callback);

        /**
         * The Closure that can be triggered by passing input and output
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return string
         */
        return static function (InputInterface $input, OutputInterface $output) use ($helper, $question): ?string {
            return  $helper->ask($input, $output, $question);
        };
    }

    /**
     * Request a valid file path, will keep looping round until valid path supplied.
     *
     * @param string $questionText
     * @return \Closure
     */
    private function requestValidFileName(string $questionText): Closure
    {

        $question = $this->requestPath($questionText);
        return function (InputInterface $input, OutputInterface $output) use ($question, $questionText): ?string {
            $path = $question($input, $output);

            // If path is empty, abort command.
            if (empty($path)) {
                $output->writeln('<error>User chose to exit by entering blank string.</error>');
                return null;
            }

            if (!is_string($path) || !is_file($path)) {
                // Output error message for invalid file name
                $output->writeln("<error>{$path} is not a valid file name</error>");
                $output->writeln("<info>Please try agai or press enter to cancel.</info>");
                return $this->requestValidFileName($questionText)($input, $output);
            }
            return $path;
        };
    }

    public function repeatAsk(string $questionText, ?Closure $validator = null): Closure
    {
        $helper = new QuestionHelper();
        $question = new Question($questionText);

        /**
         * The Closure that can be triggered by passing input and output
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return string
         */
        return static function (InputInterface $input, OutputInterface $output)
 use ($question, $questionText, $validator): ?string {
            $answer = $question($input, $output);

            // IF answer is blank, return null
            if (empty($answer)) {
                return null;
            }
        };
    }
}
