/*
 *  Copyright (c) 2017-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HHAST;

use function Facebook\FBExpect\expect;

final class AncestryTest extends TestCase {
  public async function testAncestry(): Awaitable<void> {
    $code = '<?hh function simple(int $bar): int { $baz = $bar; return $bar; }';
    $ast = await from_file_async(File::fromPathAndContents('/dev/null', $code));

    $func = $ast->getDescendantsOfType(FunctionDeclaration::class)[0];
    $body = $func->getBody();
    $variable = $func->getDescendantsOfType(VariableToken::class)[0];

    expect($func->isAncestorOf($variable))->toBeTrue('func is ancestor');
    expect($body->isAncestorOf($variable))->toBeTrue('body is ancestor');
  }
}
