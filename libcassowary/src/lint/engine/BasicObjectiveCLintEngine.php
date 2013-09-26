<?php

/**
 * Basic lint engine which applies several simple linters that work with
 * Objective-C.
 *
 * @group linter
 */
final class BasicObjectiveCLintEngine extends ArcanistLintEngine {

  const MAX_LINE_LENGTH = 120;

  public function buildLinters() {
    $linters = array();

    $paths = $this->getPaths();

    foreach ($paths as $key => $path) {
      if (preg_match('@^External/@', $path)) {
        // Third-party stuff lives in /External/; don't run lint engines
        // against it.
        unset($paths[$key]);
      }
    }

    $linters[] = id(new ArcanistFilenameLinter())->setPaths($paths);

    $text_paths = preg_grep('/\.(h|m)$/', $paths);
    $linters[] = id(new ArcanistGeneratedLinter())->setPaths($text_paths);
    $linters[] = id(new ArcanistNoLintLinter())->setPaths($text_paths);
    $linters[] = id(new ArcanistTextLinter())
      ->setMaxLineLength(self::MAX_LINE_LENGTH)
      ->setPaths($text_paths);

    return $linters;
  }
}
