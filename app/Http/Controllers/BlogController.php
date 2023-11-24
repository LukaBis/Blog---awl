<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class BlogController extends Controller
{

    public function articles(Request $request)
    {
        $path = base_path('content/collections/blog');
        $files = File::allFiles($path);

        $articles = collect($files)->map(function ($file) {
            $content = File::get($file);

            // remove last 3 hyphens
            $content = rtrim($content, "-\r\n ");

            $articleArr = Yaml::parse($content);

            return [
                'metadata' => $articleArr,
            ];
        });

        return response()->json($articles);
    }

    public function article($slug)
    {
        $path = base_path('content/collections/blog');
        $files = File::allFiles($path);

        foreach ($files as $file) {
            if (strpos($file->getFilename(), $slug . '.md') !== false) {
                $content = File::get($file);

                // remove last 3 hyphens
                $content = rtrim($content, "-\r\n ");

                $articleArr = Yaml::parse($content);

                return response()->json($articleArr); 
            }
        }

        return response()->json([
            'error' => 'No such article'
        ], 404); 
    }
}
