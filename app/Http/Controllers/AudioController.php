<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AudioController extends Controller
{
    public function stream(Request $request, $path)
    {
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

        $size = filesize($fullPath);
        $mimeType = mime_content_type($fullPath);
        $start = 0;
        $end = $size - 1;

        $headers = [
            'Content-Type'              => $mimeType,
            'Accept-Ranges'             => 'bytes',
            'Content-Length'            => $size,
        ];

        // ✅ Maneja Range requests para que el seek funcione
        if ($request->hasHeader('Range')) {
            $range = $request->header('Range');
            preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);
            $start = intval($matches[1]);
            $end   = isset($matches[2]) && $matches[2] !== '' ? intval($matches[2]) : $size - 1;

            $headers['Content-Length'] = $end - $start + 1;
            $headers['Content-Range']  = "bytes {$start}-{$end}/{$size}";

            return response()->stream(function () use ($fullPath, $start, $end) {
                $fp = fopen($fullPath, 'rb');
                fseek($fp, $start);
                $remaining = $end - $start + 1;
                while (!feof($fp) && $remaining > 0) {
                    $chunk = min(8192, $remaining);
                    echo fread($fp, $chunk);
                    $remaining -= $chunk;
                    flush();
                }
                fclose($fp);
            }, 206, $headers);
        }

        return response()->stream(function () use ($fullPath) {
            $fp = fopen($fullPath, 'rb');
            while (!feof($fp)) {
                echo fread($fp, 8192);
                flush();
            }
            fclose($fp);
        }, 200, $headers);
    }
}