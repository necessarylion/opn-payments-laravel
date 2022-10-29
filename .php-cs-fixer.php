<?php

$finder = Symfony\Component\Finder\Finder::create()
  ->notPath('vendor')
  ->in(__DIR__)
  ->name('*.php');

return (new PhpCsFixer\Config())
  ->setRules([
    '@PSR2'                                  => false,
    'array_indentation'                      => true,
    'array_syntax'                           => ['syntax' => 'short'],
    'combine_consecutive_unsets'             => true,
    'class_attributes_separation'            => ['elements' => ['method' => 'one']],
    'multiline_whitespace_before_semicolons' => false,
    'single_quote'                           => true,
    'binary_operator_spaces' => [
      'operators' => [
        '=>' => 'align_single_space_minimal',
        '='  => 'align_single_space_minimal',
        '+=' => 'align_single_space_minimal',
        '-=' => 'align_single_space_minimal',
        '>'  => 'align_single_space_minimal',
        '<'  => 'align_single_space_minimal',
        '<=' => 'align_single_space_minimal',
        '>=' => 'align_single_space_minimal',
        '||' => 'align_single_space_minimal',
        '&&' => 'align_single_space_minimal',
      ],
    ],
    'braces' => [
      'allow_single_line_closure'                   => true,
      'position_after_functions_and_oop_constructs' => 'same',
    ],
    'method_chaining_indentation' => true,
    'cast_spaces'                 => true,
    'concat_space'              => ['spacing' => 'one'],
    'declare_equal_normalize'   => true,
    'function_typehint_space'   => true,
    'single_line_comment_style' => ['comment_types' => ['hash']],
    'include'                   => true,
    'lowercase_cast'            => true,
    'no_blank_lines_before_namespace' => true,
    'no_extra_blank_lines' => [
      'tokens' => [
        'curly_brace_block',
        'extra',
        'throw',
        'use',
      ],
    ],
    'no_leading_import_slash'         => true,
    'no_leading_namespace_whitespace' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_spaces_around_offset' => true,
    'no_unused_imports'                   => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line'         => true,
    'object_operator_without_whitespace' => true,
    'phpdoc_align' => true,
    'phpdoc_indent' => true,
    'ternary_operator_spaces'         => true,
    'trailing_comma_in_multiline'     => true,
    'trim_array_spaces'               => true,
    'unary_operator_spaces'           => true,
    'whitespace_after_comma_in_array' => true,
    'space_after_semicolon'           => true,
  ])
  ->setIndent(str_pad('', 4))
  ->setLineEnding("\n")
  ->setFinder($finder);
