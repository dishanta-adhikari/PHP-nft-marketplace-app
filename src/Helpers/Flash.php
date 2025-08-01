<?php

namespace App\Helpers;

class Flash
{
    public static function render(): void
    {
        if (isset($_SESSION['success']) || isset($_SESSION['error']) || isset($_SESSION['info'])) {
            echo <<<SVG
            <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
                <!-- Success Icon -->
                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </symbol>

                <!-- Error Icon -->
                <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                </symbol>

                <!-- Info Icon -->
                <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                </symbol>
            </svg>
            SVG;
        }

        if (isset($_SESSION['success'])) {
            echo '<div class="container alert alert-success d-flex align-items-center alert-dismissible fade show container mt-3" role="alert">';
            echo '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use href="#check-circle-fill"/></svg>';
            echo '<div>' . htmlspecialchars($_SESSION['success']) . '</div>';
            echo '<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo '<div class="container alert alert-danger d-flex align-items-center alert-dismissible fade show container mt-3" role="alert">';
            echo '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Error:"><use href="#exclamation-triangle-fill"/></svg>';
            echo '<div>' . htmlspecialchars($_SESSION['error']) . '</div>';
            echo '<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['info'])) {
            echo '<div class="alert alert-info d-flex align-items-center alert-dismissible fade show container mt-3" role="alert">';
            echo '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use href="#info-fill"/></svg>';
            echo '<div>' . htmlspecialchars($_SESSION['info']) . '</div>';
            echo '<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['info']);
        }
    }
}
