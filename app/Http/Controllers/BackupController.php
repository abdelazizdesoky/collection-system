<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    protected $backupPath = 'backups';

    public function index()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $files = Storage::files($this->backupPath);
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => round(Storage::size($file) / 1024, 2) . ' KB',
                'date' => date('Y-m-d H:i:s', Storage::lastModified($file)),
            ];
        }

        // Sort by date descending
        usort($backups, fn($a, $b) => strcmp($b['date'], $a['date']));

        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $filename = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $filePath = Storage::disk('local')->path($this->backupPath . '/' . $filename);

        // Ensure directory exists
        if (!Storage::disk('local')->exists($this->backupPath)) {
            Storage::disk('local')->makeDirectory($this->backupPath);
        }

        $dbHost = config('database.connections.mysql.host');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Using mysqldump command
        $command = sprintf(
            'mysqldump --user=%s %s %s --host=%s > %s',
            $dbUser,
            (!empty($dbPass) ? "--password={$dbPass}" : ""),
            $dbName,
            $dbHost,
            $filePath
        );

        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            return redirect()->back()->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح: ' . $filename);
        }

        return redirect()->back()->with('error', 'فشل إنشاء النسخة الاحتياطية. تأكد من إعدادات النظام.');
    }

    public function download($filename)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $path = $this->backupPath . '/' . $filename;
        if (!Storage::exists($path)) {
            abort(404);
        }

        return Storage::download($path);
    }

    public function destroy($filename)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        Storage::delete($this->backupPath . '/' . $filename);
        return redirect()->back()->with('success', 'تم حذف ملف النسخة الاحتياطية بنجاح.');
    }

    public function restore($filename)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $path = $this->backupPath . '/' . $filename;
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }
        
        $filePath = Storage::disk('local')->path($path);

        $dbHost = config('database.connections.mysql.host');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Using mysql command for restore
        $command = sprintf(
            'mysql --user=%s %s %s --host=%s < %s',
            $dbUser,
            (!empty($dbPass) ? "--password={$dbPass}" : ""),
            $dbName,
            $dbHost,
            $filePath
        );

        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            return redirect()->back()->with('success', 'تم استعادة قاعدة البيانات بنجاح من الملف: ' . $filename);
        }

        return redirect()->back()->with('error', 'فشل استعادة قاعدة البيانات.');
    }
}
