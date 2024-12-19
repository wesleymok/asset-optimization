// Hook into widget rendering process
add_action('enqueue_block_assets', function() {
    if (!is_admin() && !is_widgets_block_editor()) {
        return;
    }
    
    // Implement asset deduplication
    $loaded_assets = array();
    
    // Track and manage asset loading
    add_filter('script_loader_tag', function($tag, $handle) use (&$loaded_assets) {
        if (strpos($handle, 'widget-meta') !== false) {
            if (isset($loaded_assets[$handle])) {
                return ''; // Prevent duplicate loading
            }
            $loaded_assets[$handle] = true;
        }
        return $tag;
    }, 10, 2);
});