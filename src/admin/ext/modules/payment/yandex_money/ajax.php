<?php

chdir(dirname(__FILE__) . '/../../../..');
include 'includes/application_top.php';

if (!tep_session_is_registered('admin')) {
    header('Location: /index.php');
    exit();
}

$result = array('success' => false);
if (isset($_GET['action'])) {
    include DIR_FS_CATALOG . '/includes/modules/payment/yandex_money.php';
    $module = new Yandex_Money();
    switch ($_GET['action']) {
        case 'update':
            $result = $module->getUpdater()->updateVersion();
            break;
        case 'backup_list':
            $list = $module->getUpdater()->getBackupList();
            if (is_array($list)) {
                $result['success'] = true;
                $result['list'] = $list;
            }
            break;
        case 'remove_backup':
            ob_start();
            if (isset($_POST['file_name'])) {
                $result = $module->getUpdater()->removeBackup($_POST['file_name']);
            } else {
                $result['message'] = 'Не было передано имя файла';
            }
            $data = ob_get_clean();
            if (!empty($data)) {
                $result['output'] = $data;
            }
            break;
        case 'restore_backup':
            ob_start();
            if (isset($_POST['file_name'])) {
                $result = $module->getUpdater()->restoreBackup($_POST['file_name']);
            } else {
                $result['message'] = 'Не было передано имя файла';
            }
            $data = ob_get_clean();
            if (!empty($data)) {
                $result['output'] = $data;
            }
            break;
        default:
            break;
    }
}

header('Content-type: application/json');
echo json_encode($result);
exit();