<?php
function has_child($data_src, $cat_id)
{
  foreach ($data_src as $item) {
    if ($item['parent_id'] == $cat_id) {
      return true;
    }
  }
  return false;
}

function render_menu($data_src, $parent_id = 0, $level = 0)
{
  if ($level == 0) {
    $result = "<ul class='list-item' id='main-menu-respon'>";
  } else {
    $result = "<ul class='sub-menu'>";
  }
  foreach ($data_src as $item) {
    $url = route('product.searchCat', ['slug' => $item['slug']]);
    if ($item['parent_id'] == $parent_id) {
      $result .= "<li>";
      $result .= "<a href='$url' title=''>{$item['name']}</a>";
      if (has_child($data_src, $item['id'])) {
        $result .= render_menu($data_src, $item['id'], $level + 1);
      }
      $result .= "</li>";
    }
  }
  $result .= "</ul>";
  return $result;
}

function data_tree_cat($data, $parent_id = 0)
{
  $list = array();
  foreach ($data as $item) {
    if ($item['parent_id'] == $parent_id) {
      $list[] = $item['id'];
      $child = data_tree_cat($data, $item['id']);
      $list = array_merge($list, $child);
    }
  }
  return $list;
}
