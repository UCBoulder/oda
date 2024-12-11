<?php

/**
 * @file
 * Contains oda_deploy.install.
 */

/**
 * Add role name to oda_access_control terms.
 */
function oda_deploy_10000_accesscontrol() {
  $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('oda_access_control');
  $oda_access_terms = [];
  foreach ($terms as $term) {
    $query = \Drupal::database()->select('permissions_by_term_role', 'p');
    $query->fields('p', ['rid']);
    $query->condition('p.tid', $term->tid);
    $query->condition('p.rid', 'oit_oda%', 'LIKE');
    $results = $query->execute()->fetchAll();
    $term_name = $term->name;
    $tid = $term->tid;
    if ($tid == 1229) {
      $key = 'anonymous';
    }
    elseif ($tid == 1228) {
      $key = 'authenticated';
    }
    elseif ($tid == 1227) {
      $key = 'dl_student';
    }
    else {
      $key = $results[0]->rid;
    }
    $oda_access_terms[$term_name] = [
      'role' => $key,
      'tid' => $tid,
    ];
  }
  foreach ($oda_access_terms as $term_name => $term) {
    $tid = $term['tid'];
    $role = $term['role'];
    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid);
    $term->field_oda_role_machine_name->value = $role;
    $term->save();
  }
}
