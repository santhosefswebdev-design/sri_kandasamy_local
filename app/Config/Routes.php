<?php

namespace Config;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// ========================================
// CRON ROUTES - MUST BE FIRST
// ========================================

// $routes->get('cron-master/run', 'CronPublic::run');
// $routes->get('cron-master/test', 'CronPublic::test');


// WhatsApp Template Management Routes
$routes->group('whatsapp', function ($routes) {
    $routes->get('templates', 'WhatsAppTemplate::index');
    $routes->post('get-templates', 'WhatsAppTemplate::get_templates');
    $routes->post('save-template', 'WhatsAppTemplate::save_template');
    $routes->post('delete-template', 'WhatsAppTemplate::delete_template');
    $routes->post('save-settings', 'WhatsAppTemplate::save_settings');
    $routes->post('update-job-mapping', 'WhatsAppTemplate::update_job_mapping');
    $routes->post('test-template', 'WhatsAppTemplate::test_whatsapp_template');
});
// Cron Admin routes
$routes->group('cron', function ($routes) {
    $routes->group('cron-admin', function ($routes) {
        $routes->get('/', 'CronAdmin::index');
        $routes->get('jobs', 'CronAdmin::jobs');
        $routes->get('logs', 'CronAdmin::logs');
        $routes->get('logs/(:num)', 'CronAdmin::logs/$1');
        $routes->get('queue', 'CronAdmin::queue');
        $routes->post('toggle-job', 'CronAdmin::toggle_job');
        $routes->post('trigger', 'CronAdmin::trigger');
        $routes->post('update-job', 'CronAdmin::update_job');
        $routes->post('clear-logs', 'CronAdmin::clear_logs');
        $routes->post('get_log_details', 'CronAdmin::get_log_details');
        $routes->get('export-logs', 'CronAdmin::export_logs');
        $routes->post('retry_item', 'CronAdmin::retry_item');
        $routes->post('remove_item', 'CronAdmin::remove_item');
        $routes->post('retry_failed', 'CronAdmin::retry_failed');
        $routes->post('clear_completed', 'CronAdmin::clear_completed');
    });
});

// $routes->get('cronAdmin', 'CronAdmin::index');
// $routes->get('cronAdmin/logs', 'CronAdmin::logs');
// $routes->get('cronAdmin/logs/(:num)', 'CronAdmin::logs/$1');
// $routes->get('cronAdmin/jobs', 'CronAdmin::jobs');
// $routes->post('cronAdmin/trigger', 'CronAdmin::trigger');
// $routes->post('cronAdmin/get_log_details', 'CronAdmin::get_log_details');




$routes->get('/', 'Login::index');
$routes->get('donation_hy', 'Donation_hy::index');
$routes->post('donation_hy/save', 'Donation_hy::save');
$routes->post('donation_hy/check_phone_availability', 'Donation_hy::check_phone_availability');
$routes->post('donation_hy/search_family', 'Donation_hy::search_family');
$routes->get('donation_hy/print_receipt/(:num)', 'Donation_hy::print_receipt/$1');

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
