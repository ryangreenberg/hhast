<?hh
/**
 * This file is generated. Do not modify it manually!
 *
 * @generated SignedSource<<872bfdb757a7e5f9dcdca069a62242f1>>
 */
namespace Facebook\HHAST;
use type Facebook\TypeAssert\TypeAssert;

final class ParenthesizedExpression extends EditableSyntax {

  private EditableSyntax $_left_paren;
  private EditableSyntax $_expression;
  private EditableSyntax $_right_paren;

  public function __construct(
    EditableSyntax $left_paren,
    EditableSyntax $expression,
    EditableSyntax $right_paren,
  ) {
    parent::__construct('parenthesized_expression');
    $this->_left_paren = $left_paren;
    $this->_expression = $expression;
    $this->_right_paren = $right_paren;
  }

  public static function from_json(
    array<string, mixed> $json,
    int $position,
    string $source,
  ): this {
    $left_paren = EditableSyntax::from_json(
      /* UNSAFE_EXPR */ $json['parenthesized_expression_left_paren'],
      $position,
      $source,
    );
    $position += $left_paren->width();
    $expression = EditableSyntax::from_json(
      /* UNSAFE_EXPR */ $json['parenthesized_expression_expression'],
      $position,
      $source,
    );
    $position += $expression->width();
    $right_paren = EditableSyntax::from_json(
      /* UNSAFE_EXPR */ $json['parenthesized_expression_right_paren'],
      $position,
      $source,
    );
    $position += $right_paren->width();
    return new self($left_paren, $expression, $right_paren);
  }

  public function children(): KeyedTraversable<string, EditableSyntax> {
    yield 'left_paren' => $this->_left_paren;
    yield 'expression' => $this->_expression;
    yield 'right_paren' => $this->_right_paren;
  }

  public function rewrite(
    self::TRewriter $rewriter,
    ?Traversable<EditableSyntax> $parents = null,
  ): EditableSyntax {
    $parents = $parents === null ? vec[] : vec($parents);
    $child_parents = $parents;
    $child_parents[] = $this;
    $left_paren = $this->_left_paren->rewrite($rewriter, $child_parents);
    $expression = $this->_expression->rewrite($rewriter, $child_parents);
    $right_paren = $this->_right_paren->rewrite($rewriter, $child_parents);
    if (
      $left_paren === $this->_left_paren &&
      $expression === $this->_expression &&
      $right_paren === $this->_right_paren
    ) {
      $node = $this;
    } else {
      $node = new self($left_paren, $expression, $right_paren);
    }
    return $rewriter($node, $parents);
  }

  public function left_paren(): LeftParenToken {
    return $this->left_parenx();
  }

  public function left_parenx(): LeftParenToken {
    return TypeAssert::isInstanceOf(LeftParenToken::class, $this->_left_paren);
  }

  public function raw_left_paren(): EditableSyntax {
    return $this->_left_paren;
  }

  public function with_left_paren(EditableSyntax $value): this {
    return new self($value, $this->_expression, $this->_right_paren);
  }

  public function expression(): EditableSyntax {
    return $this->expressionx();
  }

  public function expressionx(): EditableSyntax {
    return TypeAssert::isInstanceOf(EditableSyntax::class, $this->_expression);
  }

  public function raw_expression(): EditableSyntax {
    return $this->_expression;
  }

  public function with_expression(EditableSyntax $value): this {
    return new self($this->_left_paren, $value, $this->_right_paren);
  }

  public function right_paren(): ?RightParenToken {
    return $this->_right_paren->is_missing()
      ? null
      : TypeAssert::isInstanceOf(RightParenToken::class, $this->_right_paren);
  }

  public function right_parenx(): RightParenToken {
    return
      TypeAssert::isInstanceOf(RightParenToken::class, $this->_right_paren);
  }

  public function raw_right_paren(): EditableSyntax {
    return $this->_right_paren;
  }

  public function with_right_paren(EditableSyntax $value): this {
    return new self($this->_left_paren, $this->_expression, $value);
  }
}