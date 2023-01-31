<?php
register_nav_menus(
    array('primary-menu'=> ' Header Menu')
) ?>
<?php 
register_nav_menus(
    array('primary-menu'=> ' Top Menu')
)
?>
<?php
add_theme_support('post-thumbnails');
 
add_theme_support('custom-header');
?>
 

 


 <?php 

register_nav_menus(array(
   'primary' => __('Primary Menu', 'iptmenu')
));
register_nav_menus(array(
   'footermenu' => __('Footer Menu', 'iptmenu')
));
// Walker Menu Configuration
class My_Walker_Nav_Menu extends Walker_Nav_Menu
{
   function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
   {
       $GLOBALS['dd_children'] = (isset($children_elements[$element->ID])) ? 1 : 0;
       $GLOBALS['dd_depth'] = (int) $depth;
       parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
   }
   //function start_lvl(&$output, $depth) {
   function start_lvl(&$output, $depth = 0, $args = array())
   {
       $indent = str_repeat("\t", $depth);
       $output .= "\n$indent<ul class=\"sub-menu\">\n";
   }
   function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
   {
       global $wp_query, $wpdb;
       $indent = ($depth) ? str_repeat("\t", $depth) : '';
       $li_attributes = '';
       $class_names = $value = '';
       $classes = empty($item->classes) ? array() : (array) $item->classes;
       //Add class and attribute to LI element that contains a submenu UL.
       if (isset($args->has_children)) {
           $classes[]    = 'dropdown';
           $li_attributes .= 'data-dropdown="dropdown"';
       }
       $classes[] = 'dropdown drop-down menu-item-' . $item->ID;
       $classes[] = 'menu-item-has-children';
       //If we are on the current page, add the active class to that menu item.
       $classes[] = ($item->current) ? 'active' : '';
       //Make sure you still add all of the WordPress classes.
       $class_names = join(' ', apply_filters('nav_menu_css_class',     array_filter($classes), $item, $args));
       $class_names = ' class="' . esc_attr($class_names) . '"';
       $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
       $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';
       $has_children = $wpdb->get_var(
           $wpdb->prepare("
      SELECT COUNT(*) FROM $wpdb->postmeta
      WHERE meta_key = %s
      AND meta_value = %d
      ", '_menu_item_menu_item_parent', $item->ID)
       );
       //$output .= $indent . '<li' . $id . $value . $class_names .'>';
       $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';
       //Add attributes to link element.
       $attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
       $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
       $attributes .= !empty($item->xfn) ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
       $attributes .= !empty($item->url) ? ' href="'   . esc_attr($item->url) . '"' : '';
       // Check if menu item is in main menu
       if ($depth == 0 && $has_children > 0) {
           // These lines adds your custom class and attribute
           $attributes .= ' class="dropdown-toggle drop-down"';
           $attributes .= ' data-toggle="dropdown"';
       }
       $item_output = $args->before;
       $item_output .= '<a class="drop-down"' . $attributes . '>';
       $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
       // Add the caret if menu level is 0
       if ($depth == 0 && $has_children > 0) {
           $item_output .= '<i class="bi bi-chevron-down dropdown-icon"></i>';
       }
       $item_output .= '</a>';
       $item_output .= $args->after;
       $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
   }

}
?>


 