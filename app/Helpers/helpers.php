<?php

if (!function_exists('extractYouTubeId')) {
    function extractYouTubeId($url) {
        if (!$url) return null;
        
        // If it's already just an ID (11 characters)
        if (strlen($url) === 11 && !str_contains($url, '/') && !str_contains($url, '?')) {
            return $url;
        }
        
        // Handle various YouTube URL formats
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',
            '/youtube\.com\/embed\/([^&]+)/',
            '/youtube\.com\/v\/([^&]+)/',
            '/youtu\.be\/([^&]+)/',
            '/youtube\.com\/.*[?&]v=([^&]+)/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
}