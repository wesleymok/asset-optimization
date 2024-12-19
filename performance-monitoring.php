public function start_performance_monitoring() {
        $this->start_time = microtime(true);
        $this->start_memory = memory_get_usage();
        
        $this->log_performance("Page Load Started", [
            'url' => $_SERVER['REQUEST_URI'],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    public function end_performance_monitoring() {
        $total_time = microtime(true) - $this->start_time;
        $total_memory = memory_get_usage() - $this->start_memory;
        
        $this->log_performance("Page Load Completed", [
            'url' => $_SERVER['REQUEST_URI'],
            'total_time_ms' => round($total_time * 1000, 2),
            'total_memory' => size_format($total_memory),
            'widget_count' => $this->count_active_widgets()
        ]);
    }

    public function monitor_widget_render($block_content, $block) {
        if (!is_admin() && strpos($block['blockName'] ?? '', 'core/widget') !== false) {
            $start_time = microtime(true);
            $start_memory = memory_get_usage();
            
            // Process widget content
            $result = $block_content;
            
            $time_taken = microtime(true) - $start_time;
            $memory_used = memory_get_usage() - $start_memory;
            
            $this->log_performance("Widget Rendered", [
                'widget_type' => $block['blockName'],
                'render_time_ms' => round($time_taken * 1000, 2),
                'memory_used' => size_format($memory_used)
            ]);
            
            return $result;
        }
        return $block_content;
    }