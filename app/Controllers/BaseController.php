<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['common'];

    function __construct()
    {
        // Check if this is a cron execution BEFORE doing anything else
        if ($this->isCronExecution()) {
            $this->db = \Config\Database::connect();
            $this->session = \Config\Services::session();
            $this->session->start();

            // Set dummy session for cron
            $this->session->set([
                'login' => true,
                'role' => 1, // Admin role
                'user_id' => 0, // System user
                'username' => 'CronSystem',
                'is_cron' => true
            ]);

            $this->json_resp = array();
            $this->json_resp['session'] = $_SESSION;

            // Skip language switcher for cron
            return;
        }

        // Normal initialization for non-cron requests
        $this->db = \Config\Database::connect();
        $config = null; // Define config if needed
        $this->session = \Config\Services::session($config);
        $this->session->start();
        $this->json_resp = array();
        $this->json_resp['session'] = $_SESSION;

        // Only call language switcher for non-cron requests
        global $set_lang;
        if (isset($set_lang) && is_object($set_lang)) {
            $set_lang->switcher();
        }
    }

    /**
     * Check if this is a cron execution
     */
    protected function isCronExecution()
    {
        // Check multiple indicators for cron execution
        if (defined('IS_CRON_EXECUTION') && IS_CRON_EXECUTION === true) {
            return true;
        }

        if (isset($_SESSION['is_cron_execution']) && $_SESSION['is_cron_execution'] === true) {
            return true;
        }

        if (isset($_SERVER['IS_CRON_EXECUTION']) && $_SERVER['IS_CRON_EXECUTION'] === true) {
            return true;
        }

        // Check if the request is coming from CronMaster or CronAdmin with valid key
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';

        // Check for CronMaster endpoints
        if (
            strpos($requestUri, '/CronMaster/run') !== false ||
            strpos($requestUri, '/CronMaster/test') !== false ||
            strpos($requestUri, '/cronMaster/run') !== false ||
            strpos($requestUri, '/cronMaster/test') !== false
        ) {

            $key = $_GET['key'] ?? $_POST['key'] ?? '';
            if ($key === 'temple_master_cron_2024_secure') {
                return true;
            }
        }

        // Check if called from CLI
        if (php_sapi_name() === 'cli') {
            return true;
        }

        return false;
    }

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();
    }
}
