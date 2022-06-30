<?php

/**
 * Extracts all Identifiers from a stub file.
 *
 * File taken from pxlrbt's PHP-Scoper toolset.
 * @source https://github.com/pxlrbt/php-scoper-prefix-remover
 */

declare(strict_types=1);

namespace Gin0115\WpScoper\Patcher\Compiler;

use Exception;
use LogicException;
use PhpParser\ParserFactory;

class IdentifierExtractor
{
    /**
     * All identifiers to extract from stub files.
     *
     * @var string[]
     */
    private array $extractStatements = [];

    /**
     * Array of all files to extract identifiers from.
     *
     * @var string[]
     */
    private array $stubFiles = [];

    /**
     * Creates an instance of the IdentifierExtractor.
     *
     * @param string[]|null $statements
     */
    public function __construct($statements = null)
    {
        $this->extractStatements = $statements ?? [
        'Stmt_Class',
        'Stmt_Interface',
        'Stmt_Trait',
        'Stmt_Function',
        ];
    }

    /**
     * Adds a single file to the collection of files to extract identifiers from.
     *
     * @param string $file
     * @return self
     * @throws Exception If file does not exist.
     */
    public function addStubFile(string $file): self
    {
        // If file doesnt exist, throw exception.
        if (!file_exists($file)) {
            throw new Exception('File does not exist: ' . $file);
        }

        $this->stubFiles[] = $file;
        return $this;
    }

    /**
     * Extracts all identifies from the defined stub source files.
     *
     * @return string[]
     */
    public function extract(): array
    {
        $identifiers = array();
        foreach ($this->stubFiles as $file) {
            $content     = file_get_contents($file);
            $ast         = $this->generateAst($content);
            $identifiers = array_merge($identifiers, $this->extractIdentifiersFromAst($ast));
        }

        return $identifiers;
    }

    /**
     * Generates an array of the passed source contents as AST statements.
     * @param string $source
     * @return Node\Stmt[]
     * @throws LogicException
     */
    private function generateAst(string $source): array
    {
        $parser = ( new ParserFactory() )->create(ParserFactory::PREFER_PHP7);
        return $parser->parse($source) ?? [];
    }

    /**
     * Extracts all valid identifiers from the given AST.
     *
     * @param Node\Stmt[] $ast
     * @return string[]
     */
    protected function extractIdentifiersFromAst($ast): array
    {
        $globals = array();
        $items   = $ast;

        while (count($items) > 0) {
            $item = array_pop($items);

            if (isset($item->stmts)) {
                $items = array_merge($items, $item->stmts);
            }

            if (in_array($item->getType(), $this->extractStatements)) {
                // dd($item, $item->name->name/* , $items */);
                $globals[] = $item->name->name;
            }
        }
        // dump($globals);
// dump([$ast,$globals, $items])    ;
        return $globals;
    }
}
