<?hh // strict
/**
 * Copyright (c) 2016, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional
 * grant of patent rights can be found in the PATENTS file in the same
 * directory.
 *
 */

namespace Facebook\HHAST\Migrations;

use namespace Facebook\HHAST;
use namespace HH\Lib\{C, Str, Vec};
use namespace Facebook\TypeAssert;

final class OptionalShapeFieldsMigration extends BaseMigration {
  private static function makeNullableFieldsOptional(
    HHAST\ListItem $node,
  ): HHAST\ListItem {
    $field = $node->getItem();

    if (!$field instanceof HHAST\FieldSpecifier) {
      return $node;
    }

    if ($field->hasQuestion()) {
      return $node;
    }

    $type = $field->getType();
    if (!$type instanceof HHAST\NullableTypeSpecifier) {
      return $node;
    }

    $name = $field->getName()->getLastTokenx();
    $field = $field->withQuestion(
      new HHAST\QuestionToken(
        $name->getLeading(),
        HHAST\Missing(),
      ),
    )->withName(
      $name->withLeading(HHAST\Missing()),
    );

    return $node->withItem($field);
  }

  <<__Override>>
  final public function getSteps(
  ): Traversable<IMigrationStep> {
    return vec[
      new TypedMigrationStep(
        'make nullable fields optional',
        HHAST\ListItem::class,
        HHAST\ListItem::class,
        $node ==> self::makeNullableFieldsOptional($node),
      ),
    ];
  }
}
