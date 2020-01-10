<?php

namespace GreenBeans\Router;

class RoutesTokenizer
{

    /**
     * The results from the lexer
     * @var array
     */
    private $results = [];

    /**
     * RoutesTokenizer constructor.
     * @param string $source
     */
    public function __construct(string $source)
    {
        $tokens = token_get_all($source);
        $this->results = $this->interpret($tokens)->getResults();
    }

    /**
     * @param array|null $tokens
     * @return TokenLexer
     */
    private function interpret(?array $tokens): TokenLexer
    {
        return new TokenLexer($tokens);
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

}