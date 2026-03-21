<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceController
{
    #[Route('/maintenance', name: 'app_maintenance')]
    public function index(): Response
    {
        return new Response(
            '<html>
                <body style="text-align:center;">
                    <img style="width:100%;" src="/img/maintenance.png" alt="Maintenance">
                    <h2>Site en maintenance</h2>
                </body>
            </html>'
        );
    }
}