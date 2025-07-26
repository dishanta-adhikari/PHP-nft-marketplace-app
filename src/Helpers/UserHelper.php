<?php

namespace App\Helpers;

use App\Controllers\LabController;

class UserHelper
{
    // public static function checkLabOwnership($lab_id, $user_id, $con)
    // {
    //     $labController = new LabController($con);
    //     $lab = $labController->getLabById($lab_id);

    //     if (!$lab || $lab['user_id'] != $user_id) {
    //         http_response_code(403);
    //         include __DIR__ . '/../views/errors/404.php';
    //         exit;
    //     }

    //     return $lab; // return lab row for further use
    // }

    public static function checkLimit(int $limitInMB = 40, $path)
    {
        $limit = $limitInMB * 1024 * 1024;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && $_SERVER['CONTENT_LENGTH'] > $limit) {
            $_SESSION['error'] = "Your submission exceeds the maximum allowed size.";
            header("Location: " . $path);
            exit;
        }
    }

    public static function clearInstitutionCache(int $inst_id)
    {
        Cache::delete('institution_all');
        Cache::delete("institution_instid_$inst_id");
        Cache::delete("institution_lab_available");
        Cache::delete("institution_lab_not_available");
    }

    public static function clearSubjectCache(): void
    {
        Cache::delete('subjects_all');
    }
}
