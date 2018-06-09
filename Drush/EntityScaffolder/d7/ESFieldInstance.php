<?php

namespace Drush\EntityScaffolder\d7;

use Drush\EntityScaffolder\Utils;

class ESFieldInstance extends ESField {

  public function generateCode($info) {
    $module = 'fe_es';
    $filename = 'fe_es.features.field_instance.inc';
    // Add File header.
    $block = Scaffolder::HEADER;
    $key = 0;
    $template = '/field_instance/feature.header';
    $code = $this->scaffolder->render($template, $info);
    $this->scaffolder->setCode($module, $filename, $block, $key, $code);

    // Add Code block.
    $block = Scaffolder::CONTENT;
    $key = Scaffolder::CONTENT . ' : ' .$info['field_name'];
    $template = '/field_instance/' . $info['type'] . '/feature.content';
    $code = $this->scaffolder->render($template, $info);
    $this->scaffolder->setCode($module, $filename, $block, $key, $code);

    // Add translation info.
    $block = Scaffolder::CONTENT;
    $key = Scaffolder::FOOTER . ' : ' . Scaffolder::HEADER;
    $code = "\n\n  // Translatables
  // Included for use with string extractors like potx.";
    $this->scaffolder->setCode($module, $filename, $block, $key, $code);

    $key = Scaffolder::FOOTER . ' : ' . Scaffolder::CONTENT . ' : ' . $info['label'];
    $code = "\n  t('{$info['label']}');";
    $this->scaffolder->setCode($module, $filename, $block, $key, $code);

    // Add file footer.
    $block = Scaffolder::FOOTER;
    $key = 0;
    $template = '/field_instance/feature.footer';
    $code = $this->scaffolder->render($template, $info);
    $this->scaffolder->setCode($module, $filename, $block, $key, $code);

    // Add entry to info file.
    $code = "\nfeatures[field_instance][] = {$info['entity_type']}-{$info['bundle']}-{$info['field_name']}";
    $module = 'fe_es';
    $filename = 'fe_es.info';
    $block = Scaffolder::CONTENT;
    $key = $code;
    $this->scaffolder->setCode($module, $filename, $block, $key, $code);
  }

  /**
   * Helper function to load config and defaults.
   */
  public function getConfig($config, $field_key, $field_info) {
    $info = $field_info;
    $info['_file'] = $config['_file'];
    $info['entity_type'] = $config['entity_type'];
    $info['bundle'] = $config['bundle'];
    $info['field_name'] = $this->getFieldName($config, $field_key);
    $info['cardinality'] = empty($info['cardinality']) ? 1 : $info['cardinality'];
    $this->setTemplateDir('/field_instance/' . $info['type']);
    $local_config_file = $this->scaffolder->getTemplatedir() . $this->getTemplateDir() . '/config.yaml';
    $info['local_config'] = Utils::getConfig($local_config_file);
    return $this->processConfigData($info);
  }

}