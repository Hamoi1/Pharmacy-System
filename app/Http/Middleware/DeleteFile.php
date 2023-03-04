<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class DeleteFile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = new User();
        // get all file in folder logs and every 1 month delete file by date file
        // gerate a date for one month after one month check file date if true delete file else nothing
        $SubDate = date('Y.m.d', strtotime('-1 month'));
        $files = $this->files();
        foreach ($files as $file) {
            $fileName = explode('-', $file); // change to array  by remove a -
            $user_id = $fileName[1];
            $date = $fileName[2]; // get date  by index 
            $date = explode('.', $date); // change to array by remove a .
            $date = $date[0] . '.' . $date[1] . '.' . $date[2]; // concatenate a Year and month and day by index 
            // check if date is equal to date variable
            if ($date <= $SubDate) {
                // delete a file
                $user->CreateFile($user_id);
                unlink('logs/' . $file);
            }
        }

        return $next($request);
    }
    public function files()
    {
        $files = scandir('logs');
        // remove . and .. from array
        array_shift($files);
        array_shift($files);
        return $files;
    }
}
