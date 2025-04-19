<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\FileManagerSystem\FilemanagersystemService;

class FilemanagersystemPermission
{
    protected $filemanagersystemService;

    public function __construct(FilemanagersystemService $filemanagersystemService)
    {
        $this->filemanagersystemService = $filemanagersystemService;
    }

    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!$this->filemanagersystemService->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Bu işlem için yetkiniz bulunmuyor'], 403);
            }
            return redirect()->back()->with('error', 'Bu işlem için yetkiniz bulunmuyor');
        }

        return $next($request);
    }
} 