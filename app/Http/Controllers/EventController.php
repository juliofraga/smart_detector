<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    private $model;
    private $jsonFiles;

    public function __construct(Event $event)
    {
        $this->model = $event;
    }

    public function index(Request $request)
    {
        $this->listJsonFiles();
        $this->lerJsonFiles();
    }

    public function listJsonFiles()
    {
        $files = Storage::files('public');
        $this->jsonFiles = array_filter($files, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'json';
        });
    }

    public function lerJsonFiles()
    {
        foreach ($this->jsonFiles as $file) {
            $content = Storage::get($file);
            $data = json_decode($content, true);
            foreach ($data as $d) {
                foreach ($d as $key => $value) {
                    
                }
            }
        }
    }
}
