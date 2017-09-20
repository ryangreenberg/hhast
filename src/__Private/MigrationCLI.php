<?hh // strict
/**
 * Copyright (c) 2016, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional
 * grant of patent rights can be found in the PATENTS file in the same
 * directory.
 */

namespace Facebook\HHAST\__Private;

use function Facebook\HHAST\from_file as ast_from_file;
use namespace HH\Lib\C;
use type Facebook\HHAST\Migrations\{
  BaseMigration,
  ImplicitShapeSubtypesMigration,
  OptionalShapeFieldsMigration
};

final class MigrationCLI extends CLIBase {
  private keyset<classname<BaseMigration>> $migrations = keyset[];

  public static function acceptsArguments(): bool {
    return true;
  }

  protected function getSupportedOptions(): vec<CLIOptions\CLIOption> {
    return vec[
      CLIOptions\flag(
        () ==> { $this->migrations[] = ImplicitShapeSubtypesMigration::class; },
        'Allow implicit structural subtyping of all shapes',
        'implicit-shape-subtypes',
      ),
      CLIOptions\flag(
        () ==> { $this->migrations[] = OptionalShapeFieldsMigration::class; },
        'Migrate nullable shape fields to be both nullable and optional',
        'optional-shape-fields',
      ),
      CLIOptions\flag(
        () ==> {
          $this->migrations[] = OptionalShapeFieldsMigration::class;
          $this->migrations[] = ImplicitShapeSubtypesMigration::class;
        },
        'Apply all migrations for moving from 3.22 to 3.23',
        'for-hhvm-3.23',
      ),
    ];
  }

  private function migrateFile(
    string $file,
  ): void {
    $ast = ast_from_file($file);
    foreach ($this->migrations as $migration) {
      $ast = (new $migration())->migrateAst($ast);
    }
    file_put_contents($file, $ast->getCode());
  }

  private function migrateDirectory(
    string $directory,
  ): void {
    $it = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($directory),
    );
    foreach ($it as $info) {
      if (!$info->isFile()) {
        continue;
      }
      $this->migrateFile($info->getPathname());
    }
  }

  public async function mainAsync(): Awaitable<int> {
    if (C\is_empty($this->migrations)) {
      fprintf(STDERR, "You must specify at least one migration!\n\n");
      $this->displayHelp(STDERR);
      return 1;
    }

    $args = $this->getArguments();
    if (C\is_empty($args)) {
      fprintf(STDERR, "You must specify at least one path!\n\n");
      $this->displayHelp(STDERR);
      return 1;
    }
    foreach ($args as $path) {
      if (is_file($path)) {
        $this->migrateFile($path);
        continue;
      }
      if (is_dir($path)) {
        $this->migrateDirectory($path);
        continue;
      }

      fprintf(STDERR, "Don't know how to process path: %s\n", $path);
      return 1;
    }
    return 0;
  }
}
