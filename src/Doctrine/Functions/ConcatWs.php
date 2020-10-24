<?php
declare(strict_types=1);

namespace App\Doctrine\Functions;

use Doctrine\ORM\Query\AST\ASTException;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Literal;
use Doctrine\ORM\Query\AST\PathExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class ConcatWs
 * @package App\Doctrine\Functions
 */
class ConcatWs extends FunctionNode
{
    public ?Literal $concatString = null;

    /** @var PathExpression[] */
    public array $fields = [];

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     * @throws ASTException
     */
    public function getSql(SqlWalker $sqlWalker): string
    {
        $fields = array_map(static function ($field) use ($sqlWalker) {
            return $field->dispatch($sqlWalker);
        }, $this->fields);

        return 'concat_ws(' . $this->concatString->dispatch($sqlWalker) . ',' . implode(',', $fields) . ')';
    }

    /**
     * @param Parser $parser
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->concatString = $parser->ArithmeticPrimary();

        while (true) {
            try {
                $parser->match(Lexer::T_COMMA);
            } catch (QueryException $e) {
                break;
            }
            $this->fields[] = $parser->ArithmeticPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
