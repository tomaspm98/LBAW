<?php

namespace App\Http\Controllers;

use App\Events\NotificationsUpdated;
use App\Models\Answer;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    static $default = 'default_profile.png';
    static $diskName = 'Tutorial02';

    static $systemTypes = [
        'profile' => ['png'],
        'badge' => ['jpg'],
    ];

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }
    
    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }
    
    private static function getFileName (String $type, int $id) {
        $fileName =null;
        switch($type) {
            case 'profile':
                $fileName = Member::find($id)->picture;
                if (!$fileName)
                    $filename = self::$default;
                break;
            case 'badge':
                $fileName = "badge_" . $id. ".jpg"; 
                break; 
            }
        return $fileName;
    }
    
    static function get(String $type, int $userId) {

        \Log::debug('get file: ' . $type . '/' . $userId);
    
        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }
    
        // Validation: file exists
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type . '/' . $fileName);
        }
    
        // Not found: returns default asset
        return self::defaultAsset($type);
    }

    function upload(Request $request) {

        $file = $request->file('picture');
        $id = $request->id;
        $extension = $file->getClientOriginalExtension();
        
        $username = $request->username;
        // Hashing
        $fileName = 'profile_' . $username . '.' . $extension; // generate a random unique id
        $type = 'profile';

        // Save in correct folder and disk
        $request->picture->storeAs($type, $fileName, self::$diskName);
        return redirect()->back();
    }
    
    
}