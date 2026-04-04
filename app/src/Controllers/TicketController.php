<?php

namespace App\Controllers;

use App\Models\PersonalProgram;
use App\Repositories\TicketRepository;
use App\Services\Interfaces\ITicketService;

use App\Exceptions\CapacityException;


class TicketController
{

    private TicketRepository $ticketRepository;
    private ITicketService $ticketService;
    

    public function __construct(TicketRepository $ticketRepository,  ITicketService $ticketService)
    {
        $this->ticketRepository = $ticketRepository;
        $this->ticketService = $ticketService;
    }

    public function index(): void
    {
        $program = $_SESSION['program'] ?? new PersonalProgram();
        $tickets = $program->getTickets();

        $tickets = $this->ticketService->hydrateTickets($tickets);

        require __DIR__ . '/../Views/personalProgram/personalProgram.php';
    
    }

    public function addTicket(): void
    {
        $data = $_POST; 
        
        try {
            $this->ticketService->addToProgram($data, $_SESSION['user']['id'] ?? null);
            $_SESSION['flash_success'] = "Ticket was successfully added to your Personal Program!";
        } catch (CapacityException $e) {
            //if it's a capacity issue, progrram will sned user back to the event page to pick a smaller number
            $_SESSION['flash_error'] = $e->getMessage();
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Something went wrong.";
        }

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }


    public function removeTicket(): void
    {
        $ticketId = (int)($_POST['ticket_id'] ?? 0);

        if ($ticketId <= 0) {
            $_SESSION['error'] = "Invalid item selected.";
            header('Location: /personalProgram');
            exit;
        }

        $program = $_SESSION['program'] ?? new PersonalProgram();
        $program->removeTicket($ticketId);

        $_SESSION['program'] = $program;
        $_SESSION['flash_success'] = "Item removed from your Personal Program.";

        header('Location: /personalProgram');
        exit;
    }

    /**
 * Simple ticket scan method for employees
 */
public function scan(): void
{
    $this->requireEmployee();

    try {
        $token = $_GET['token'] ?? '';
        if ($token === '') {
            $status = 'error';
            $message = 'No ticket token provided.';
            $ticket = null;

            require __DIR__ . '/../Views/employee/scanResult.php';
            return;
        }
        $ticket = $this->ticketRepository->getByToken($token);

        if (!$ticket) {
            $status = 'error';
            $message = 'Invalid ticket.';
            $ticket = null;

            require __DIR__ . '/../Views/employee/scanResult.php';
            return;
        }

        if ($ticket['is_scanned'] == 1) {
            $status = 'warning';
            $message = 'This ticket has already been scanned.';

            require __DIR__ . '/../Views/employee/scanResult.php';
            return;
        }

        $this->ticketRepository->markAsScanned($token);

        $status = 'success';
        $message = 'Ticket is valid. Entry allowed.';

        require __DIR__ . '/../Views/employee/scanResult.php';

    } catch (\Exception $e) {
        error_log("Scan error: " . $e->getMessage());

        $status = 'error';
        $message = 'Something went wrong.';
        $ticket = null;

        require __DIR__ . '/../Views/employee/scanResult.php';
    }
}

    private function requireEmployee(): void //checks if current user is employee
    {
        try {
            if (!isset($_SESSION['user'])) {
                throw new \Exception("User not logged in.");
            }
            if (($_SESSION['user']['role'] ?? '') !== 'Employee') {
                throw new \Exception("User is not an employee.");
            }
        } catch (\Exception $e) {
            error_log("Access error: " . $e->getMessage());
            echo "Access denied. Employees only.";
            exit;
        }
    }
    
    public function scanPage(): void
    {
        $this->requireEmployee();

        require __DIR__ . '/../Views/employee/scan.php';
    }

      public function adminIndex()
    {
        $tickets = $this->ticketRepository->getAllWithDetails();

        require __DIR__ . '/../Views/admin/dashboard.php';
    }
  public function exportCsv(): void
{
    $tickets = $this->ticketRepository->getAllWithDetails();

    $selectedColumns = array_intersect(
        $_POST['columns'] ?? [],
        array_keys($tickets[0] ?? [])
    );
    if (empty($selectedColumns)) {
        header("Location: /admin");
        exit;
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="tickets.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, $selectedColumns);

    foreach ($tickets as $row) {
        fputcsv(
            $output,
            array_map(fn($col) => $row[$col] ?? '', $selectedColumns)
        );
    }

    fclose($output);
    exit;
}//import to excel
}
