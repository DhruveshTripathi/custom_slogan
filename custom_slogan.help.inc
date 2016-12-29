<?php
/**
 * Help text.
 *
 * @file
 * custin slogan help function
 */

/**
 * Handle help calls.
 * @see hook_help()
 */
function _custom_slogan_help($path, $arg) {
  switch($path) {
    case ('admin/help#custom_slogan'):
      return '<p>'.  t('This is a module to support custom slogans on each node, node type, taxonomy. Tokens can be used to define pattern.') . '</p>';
      break;
  }
}
