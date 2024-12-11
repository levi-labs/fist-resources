<?php

use Illuminate\Support\Facades\Storage;

function formatNumber($number)
{
    $formattedRupiah = 'Rp.' . number_format($number, 0, ',', '.');
    return  $formattedRupiah;
}

function handleSanitize($string)
{
    if ($string) {
        return strip_tags($string);
    }
    return false;
}
function get_file_path_from_url($url)
{
    return str_replace(url('http://localhost:8000/storage/'), '', $url);
}

function handle_delete_file($path)
{
    $filePath = get_file_path_from_url($path);
    if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($filePath);

        return true;
    }

    return false;
}
