<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class WhatsAppTemplate extends BaseController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    /**
     * Main template management page
     */
    public function index()
    {
        $data = [];

        // Check if tables exist first
        $tables = $this->db->listTables();

        // Get templates if table exists
        if (in_array('whatsapp_templates', $tables)) {
            $result = $this->db->table('whatsapp_templates')
                ->orderBy('module', 'ASC')
                ->orderBy('template_type', 'ASC')
                ->get();

            $data['templates'] = $result ? $result->getResultArray() : [];
        } else {
            $data['templates'] = [];
            // Table doesn't exist, show warning
            $data['warning'] = 'WhatsApp templates table not found. Please run the database migration.';
        }

        // Get settings if table exists
        if (in_array('whatsapp_settings', $tables)) {
            $result = $this->db->table('whatsapp_settings')
                ->where('id', 1)
                ->get();

            $data['settings'] = $result ? $result->getRowArray() : null;
        } else {
            $data['settings'] = null;
        }

        // Get job mappings if tables exist
        if (in_array('cron_jobs', $tables) && in_array('whatsapp_template_mapping', $tables)) {
            $builder = $this->db->table('cron_jobs cj')
                ->select('cj.id as job_id, cj.job_name, cj.module, cj.action');

            // Only join if whatsapp_template_mapping exists
            if (in_array('whatsapp_template_mapping', $tables)) {
                $builder->select('wtm.template_id, wt.name as template_name, wtm.is_active')
                    ->join('whatsapp_template_mapping wtm', 'wtm.job_id = cj.id', 'left')
                    ->join('whatsapp_templates wt', 'wt.id = wtm.template_id', 'left');
            } else {
                $builder->select('NULL as template_id, NULL as template_name, 0 as is_active');
            }

            $result = $builder->whereIn('cj.module', ['prasadam', 'donation', 'ubayam', 'annathanam'])
                ->get();

            $data['job_mappings'] = $result ? $result->getResultArray() : [];
        } else {
            $data['job_mappings'] = [];
        }

        echo view('template/header');
        echo view('template/sidebar');
        echo view('whatsapp/template_management', $data);
        echo view('template/footer');
    }

    /**
     * Get all templates (AJAX)
     */
    public function get_templates()
    {
        $module = $this->request->getGet('module');

        // Check if table exists
        $tables = $this->db->listTables();
        if (!in_array('whatsapp_templates', $tables)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Templates table not found',
                'templates' => []
            ]);
        }

        $builder = $this->db->table('whatsapp_templates');

        if (!empty($module)) {
            $builder->where('module', $module);
        }

        $result = $builder->get();
        $templates = $result ? $result->getResultArray() : [];

        // Extract variables from template content
        foreach ($templates as &$template) {
            preg_match_all('/:(\w+)/', $template['content'], $matches);
            $template['variables'] = $matches[0];
        }

        return $this->response->setJSON([
            'status' => true,
            'templates' => $templates
        ]);
    }

    /**
     * Save or update template
     */
    public function save_template()
    {
        // Check if table exists
        $tables = $this->db->listTables();
        if (!in_array('whatsapp_templates', $tables)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Templates table not found. Please run database migration.'
            ]);
        }

        $template_id = $this->request->getPost('template_id');

        $data = [
            'name' => $this->request->getPost('template_name'),
            'module' => $this->request->getPost('template_module'),
            'template_type' => $this->request->getPost('template_type'),
            'content' => $this->request->getPost('template_content'),
            'is_active' => $this->request->getPost('template_active') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            if (!empty($template_id)) {
                // Update existing template
                $this->db->table('whatsapp_templates')
                    ->where('id', $template_id)
                    ->update($data);

                $message = 'Template updated successfully';
            } else {
                // Insert new template
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->db->table('whatsapp_templates')->insert($data);
                $template_id = $this->db->insertID();

                $message = 'Template created successfully';
            }

            // Log the change if log table exists
            if (in_array('whatsapp_template_logs', $tables)) {
                $this->logTemplateChange($template_id, $template_id ? 'update' : 'create');
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => $message,
                'template_id' => $template_id
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to save template: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete template
     */
    // In WhatsAppTemplate.php controller, update the delete_template method:

    public function delete_template()
    {
        $template_id = $this->request->getPost('template_id');

        if (empty($template_id)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Template ID is required'
            ]);
        }

        try {
            // Check if template exists
            $template = $this->db->table('whatsapp_templates')
                ->where('id', $template_id)
                ->get()
                ->getRowArray();

            if (!$template) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Template not found'
                ]);
            }

            // Check if template is in use
            $inUse = $this->db->table('whatsapp_template_mapping')
                ->where('template_id', $template_id)
                ->countAllResults();

            if ($inUse > 0) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Cannot delete template. It is currently mapped to job(s).'
                ]);
            }

            // Delete template
            $deleted = $this->db->table('whatsapp_templates')
                ->where('id', $template_id)
                ->delete();

            if ($deleted) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Template deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Failed to delete template from database'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Delete template error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Save WhatsApp API settings
     */
    public function save_settings()
    {
        // Check if table exists
        $tables = $this->db->listTables();
        if (!in_array('whatsapp_settings', $tables)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Settings table not found. Please run database migration.'
            ]);
        }

        $data = [
            'instance_id' => $this->request->getPost('instance_id'),
            'api_token' => $this->request->getPost('api_token'),
            'api_url' => $this->request->getPost('api_url'),
            'country_code' => $this->request->getPost('country_code'),
            'rate_limit_seconds' => $this->request->getPost('rate_limit'),
            'enable_logging' => $this->request->getPost('enable_logging') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            // Check if settings exist
            $exists = $this->db->table('whatsapp_settings')->countAllResults();

            if ($exists > 0) {
                $this->db->table('whatsapp_settings')
                    ->where('id', 1)
                    ->update($data);
            } else {
                $data['id'] = 1;
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->db->table('whatsapp_settings')->insert($data);
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Settings saved successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to save settings: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update job template mapping
     */
    public function update_job_mapping()
    {
        $job_id = $this->request->getPost('job_id');
        $template_id = $this->request->getPost('template_id');
        $is_active = $this->request->getPost('is_active') ? 1 : 0;

        if (empty($job_id)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid job ID'
            ]);
        }

        // Check if table exists
        $tables = $this->db->listTables();
        if (!in_array('whatsapp_template_mapping', $tables)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Template mapping table not found. Please run database migration.'
            ]);
        }

        try {
            // Check if mapping exists
            $exists = $this->db->table('whatsapp_template_mapping')
                ->where('job_id', $job_id)
                ->countAllResults();

            if ($exists > 0) {
                $this->db->table('whatsapp_template_mapping')
                    ->where('job_id', $job_id)
                    ->update([
                        'template_id' => $template_id,
                        'is_active' => $is_active,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            } else {
                $this->db->table('whatsapp_template_mapping')->insert([
                    'job_id' => $job_id,
                    'template_id' => $template_id,
                    'is_active' => $is_active,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Job mapping updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update mapping: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get template by ID or name for use in cron jobs
     */
    public function get_template_content($identifier)
    {
        // Check if table exists
        $tables = $this->db->listTables();
        if (!in_array('whatsapp_templates', $tables)) {
            return null;
        }

        if (is_numeric($identifier)) {
            $result = $this->db->table('whatsapp_templates')
                ->where('id', $identifier)
                ->where('is_active', 1)
                ->get();
        } else {
            $result = $this->db->table('whatsapp_templates')
                ->where('name', $identifier)
                ->where('is_active', 1)
                ->get();
        }

        if ($result) {
            $template = $result->getRowArray();
            return $template ? $template['content'] : null;
        }

        return null;
    }

    /**
     * Send WhatsApp message using dynamic template
     */
    public function send_whatsapp_message($numbers, $template_name, $params, $media = [])
    {
        // Get template content
        $template_content = $this->get_template_content($template_name);

        if (!$template_content) {
            log_message('error', 'WhatsApp template not found: ' . $template_name);
            return ['status' => false, 'error' => 'Template not found'];
        }

        // Check if settings table exists
        $tables = $this->db->listTables();
        if (!in_array('whatsapp_settings', $tables)) {
            log_message('error', 'WhatsApp settings table not found');
            return ['status' => false, 'error' => 'Settings table not found'];
        }

        // Get API settings
        $result = $this->db->table('whatsapp_settings')
            ->where('id', 1)
            ->get();

        $settings = $result ? $result->getRowArray() : null;

        if (!$settings) {
            log_message('error', 'WhatsApp API settings not configured');
            return ['status' => false, 'error' => 'API settings not configured'];
        }

        // Replace variables in template
        $message = strtr($template_content, $params);

        // Prepare API data
        $postData = [
            'token' => $settings['api_token'],
            'to' => implode(",", $numbers),
            'body' => $message
        ];

        $url = "chat";
        if (!empty($media["url"])) {
            $url = "document";
            $postData["document"] = $media["url"];
            $postData["filename"] = $media["filename"] ?? "document.pdf";
            $postData["caption"] = $message;
        }

        // Log if enabled
        if ($settings['enable_logging'] && in_array('whatsapp_message_logs', $tables)) {
            log_message('info', 'WhatsApp sending to: ' . implode(',', $numbers));
            log_message('info', 'WhatsApp template: ' . $template_name);
        }

        // Send via UltraMsg API
        $api_endpoint = $settings['api_url'] . '/instance' . $settings['instance_id'] . '/messages/' . $url;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $api_endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);

        // Log response if enabled
        if ($settings['enable_logging']) {
            log_message('info', 'WhatsApp response: ' . $response);
            if ($err) {
                log_message('error', 'WhatsApp error: ' . $err);
            }
        }

        // Log message sent
        if (in_array('whatsapp_message_logs', $tables)) {
            $this->logMessageSent($template_name, $numbers, $response);
        }

        $result = json_decode($response, true);
        if (!$result) {
            $result = ['status' => false, 'error' => 'Invalid API response'];
        }

        return $result;
    }

    /**
     * Log template changes for audit
     */
    private function logTemplateChange($template_id, $action)
    {
        // Check if log table exists
        $tables = $this->db->listTables();
        if (!in_array('whatsapp_template_logs', $tables)) {
            return;
        }

        $this->db->table('whatsapp_template_logs')->insert([
            'template_id' => $template_id,
            'action' => $action,
            'user_id' => session()->get('user_id') ?? session()->get('login_id') ?? 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Log messages sent
     */
    private function logMessageSent($template_name, $numbers, $response)
    {
        // Check if log table exists
        $tables = $this->db->listTables();
        if (!in_array('whatsapp_message_logs', $tables)) {
            return;
        }

        $this->db->table('whatsapp_message_logs')->insert([
            'template_name' => $template_name,
            'recipients' => json_encode($numbers),
            'response' => $response,
            'status' => strpos($response, '"sent":true') !== false ? 'sent' : 'failed',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    /**
     * Get all WhatsApp-enabled modules with strict deduplication
     */
    public function get_modules()
    {
        try {
            $modulesMap = []; // Use associative array to auto-deduplicate
            $tables = $this->db->listTables();

            // Method 1: Detect from whatsapp_log tables
            foreach ($tables as $table) {
                if (preg_match('/^(.+)_whatsapp_log$/', $table, $matches)) {
                    $module = $matches[1];

                    // Skip if already exists
                    if (!isset($modulesMap[$module])) {
                        $modulesMap[$module] = [
                            'name' => $module,
                            'label' => ucfirst(str_replace('_', ' ', $module)),
                            'has_log_table' => true,
                            'has_template' => false,
                            'has_job' => false
                        ];
                    }
                }
            }

            // Method 2: Check which have templates
            if (in_array('whatsapp_templates', $tables)) {
                $result = $this->db->table('whatsapp_templates')
                    ->select('module')
                    ->distinct()
                    ->where('module IS NOT NULL')
                    ->where('module !=', '')
                    ->get();

                if ($result) {
                    foreach ($result->getResultArray() as $row) {
                        $module = $row['module'];

                        if (isset($modulesMap[$module])) {
                            $modulesMap[$module]['has_template'] = true;
                        } else {
                            // Add module found in templates but no log table
                            $modulesMap[$module] = [
                                'name' => $module,
                                'label' => ucfirst(str_replace('_', ' ', $module)),
                                'has_log_table' => false,
                                'has_template' => true,
                                'has_job' => false
                            ];
                        }
                    }
                }
            }

            // Method 3: Check which have cron jobs
            if (in_array('cron_jobs', $tables)) {
                $result = $this->db->table('cron_jobs')
                    ->select('module')
                    ->distinct()
                    ->where('module IS NOT NULL')
                    ->where('module !=', '')
                    ->get();

                if ($result) {
                    foreach ($result->getResultArray() as $row) {
                        $module = $row['module'];

                        if (isset($modulesMap[$module])) {
                            $modulesMap[$module]['has_job'] = true;
                        } else {
                            // Add module with job but no log table
                            $modulesMap[$module] = [
                                'name' => $module,
                                'label' => ucfirst(str_replace('_', ' ', $module)),
                                'has_log_table' => false,
                                'has_template' => false,
                                'has_job' => true
                            ];
                        }
                    }
                }
            }

            // Convert associative array to indexed array
            $modules = array_values($modulesMap);

            // Sort alphabetically by name
            usort($modules, function ($a, $b) {
                return strcmp($a['name'], $b['name']);
            });

            // Log for debugging
            log_message('info', 'Detected ' . count($modules) . ' modules: ' .
                implode(', ', array_column($modules, 'name')));

            return $this->response->setJSON([
                'status' => true,
                'modules' => $modules,
                'count' => count($modules)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Get modules error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to fetch modules',
                'modules' => []
            ]);
        }
    }


    /**
     * Get available variables for each module by reading table columns
     */
    public function get_module_variables()
    {
        try {
            $tables = $this->db->listTables();
            $moduleVariables = [];

            // Find all whatsapp_log tables
            foreach ($tables as $table) {
                if (preg_match('/^(.+)_whatsapp_log$/', $table, $matches)) {
                    $module = $matches[1];

                    // Get columns from this table
                    $fields = $this->db->getFieldNames($table);

                    // Convert to variables (exclude system fields)
                    $excludeFields = ['id', 'created_at', 'updated_at', 'sent_at', 'status', 'response', 'created_by'];
                    $variables = [];

                    foreach ($fields as $field) {
                        if (!in_array($field, $excludeFields)) {
                            $variables[] = ':' . $field;
                        }
                    }

                    $moduleVariables[$module] = $variables;
                }
            }

            // Sort variables alphabetically within each module
            foreach ($moduleVariables as &$vars) {
                sort($vars);
            }

            log_message('info', 'Module variables detected: ' . json_encode($moduleVariables));

            return $this->response->setJSON([
                'status' => true,
                'variables' => $moduleVariables
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Get module variables error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to fetch module variables',
                'variables' => []
            ]);
        }
    }
}
