<?php

namespace YooMoney;

use YooMoney\Updater\Archive\BackupZip;
use YooMoney\Updater\Archive\RestoreZip;
use YooMoney\Updater\GitHubConnector;
use YooMoney\Updater\ProjectStructure\ProjectStructureReader;

define('ROOT_DIRECTORY', realpath(dirname(__FILE__) . '/../../../..'));
define('DIR_DOWNLOAD', ROOT_DIRECTORY . '/download');

class Updater
{
    /**
     * @var \YooMoney
     */
    private $module;

    private $backupDirectory = 'yoomoney/backup';
    private $versionDirectory = 'yoomoney/updates';
    private $downloadDirectory = 'yoomoney';
    private $repository = 'yoomoney/cms-oscommerce';

    public function __construct(\YooMoney $module)
    {
        $this->module = $module;
    }

    public function getVersionInfo($force = false)
    {
        $versionInfo = $this->checkModuleVersion($force);
        $result = array();
        if (version_compare($versionInfo['version'], \YooMoney::MODULE_VERSION) > 0) {
            $result['new_version_available'] = true;
            $result['changelog'] = $this->getChangeLog(\YooMoney::MODULE_VERSION, $versionInfo['version']);
            $result['newVersion'] = $versionInfo['version'];
        } else {
            $result['new_version_available'] = false;
            $result['changelog'] = '';
            $result['newVersion'] = \YooMoney::MODULE_VERSION;
        }
        $result['currentVersion'] = \YooMoney::MODULE_VERSION;
        $result['newVersionInfo'] = $versionInfo;

        return $result;
    }

    public function updateVersion()
    {
        $versionInfo = $this->checkModuleVersion(false);
        $fileName = $this->downloadLastVersion($versionInfo['tag']);
        if (!empty($fileName)) {
            if ($this->createBackup(\YooMoney::MODULE_VERSION)) {
                $this->module->log('info', 'Unpack file ' . $fileName);
                if ($this->unpackLastVersion($fileName)) {
                    $result = array(
                        'message' => 'Версия модуля ' . $versionInfo['version'] . ' (' . $fileName . ') была успешно загружена и установлена',
                        'success' => true,
                    );
                } else {
                    $result = array(
                        'message' => 'Не удалось распаковать загруженный архив ' . $fileName . ', подробную информацию о произошедшей ошибке можно найти в <a href="">логах модуля</a>',
                        'success' => false,
                    );
                }
            } else {
                $result = array(
                    'message' => 'Не удалось создать бэкап установленной версии модуля, подробную информацию о произошедшей ошибке можно найти в <a href="' . $logs . '">логах модуля</a>',
                    'success' => false,
                );
            }
        } else {
            $result = array(
                'message' => 'Не удалось загрузить архив с новой версией, подробную информацию о произошедшей ошибке можно найти в <a href="' . $logs . '">логах модуля</a>',
                'success' => false,
            );
        }
        return $result;
    }

    public function getBackupList()
    {
        $result = array();

        $this->preventDirectories();
        $dir = DIR_DOWNLOAD . '/' . $this->backupDirectory;

        $handle = opendir($dir);
        while (($entry = readdir($handle)) !== false) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $ext = pathinfo($entry, PATHINFO_EXTENSION);
            if ($ext === 'zip') {
                $backup = array(
                    'name'    => pathinfo($entry, PATHINFO_FILENAME) . '.zip',
                    'size'    => $this->formatSize(filesize($dir . '/' . $entry)),
                );
                $parts = explode('-', $backup['name'], 3);
                $backup['version'] = $parts[0];
                $backup['time'] = $parts[1];
                $backup['date'] = date('d.m.Y H:i:s', $parts[1]);
                $backup['hash'] = $parts[2];
                $result[] = $backup;
            }
        }
        return $result;
    }

    public function removeBackup($fileName)
    {
        if (!empty($fileName)) {
            $fileName = DIR_DOWNLOAD . '/' . $this->backupDirectory . '/' . str_replace(array('/', '\\'), array('', ''), $fileName);
            if (!file_exists($fileName)) {
                $this->module->log('error', 'File "' . $fileName . '" not exists');
                return array(
                    'message' => 'Файл бэкапа ' . $fileName . ' не найден',
                    'success' => false,
                );
            }

            if (!unlink($fileName) || file_exists($fileName)) {
                $this->module->log('error', 'Failed to unlink file "' . $fileName . '"');
                return array(
                    'message' => 'Не удалось удалить файл бэкапа ' . $fileName,
                    'success' => false,
                );
            }
            return array(
                'message' => 'Файл бэкапа ' . $fileName . ' был успешно удалён',
                'success' => true,
            );
        }
        return array(
            'message' => 'Не был передан удаляемый файл бэкапа',
            'success' => false,
        );
    }

    public function restoreBackup($fileName)
    {
        if (!empty($fileName)) {
            $fileName = DIR_DOWNLOAD . '/' . $this->backupDirectory . '/' . $fileName;
            if (!file_exists($fileName)) {
                $this->module->log('error', 'File "' . $fileName . '" not exists');
                return array(
                    'message' => 'Файл бэкапа ' . $fileName . ' не найден',
                    'success' => false,
                );
            }
            try {
                $sourceDirectory = ROOT_DIRECTORY;
                $archive = new RestoreZip($fileName);
                $archive->restore('file_map.map', $sourceDirectory);
            } catch (\Exception $e) {
                $this->module->log('error', $e->getMessage());
                if ($e->getPrevious() !== null) {
                    $this->module->log('error', $e->getPrevious()->getMessage());
                }
                return array(
                    'message' => 'Не удалось восстановить модуль из бэкапа: ' . $e->getMessage(),
                    'success' => false,
                );
            }
            return array(
                'message' => 'Модуль был успешно восстановлен из бэкапа: ' . $fileName,
                'success' => true,
            );
        }
        return array(
            'message' => 'Не был передан удаляемый файл бэкапа',
            'success' => false,
        );
    }

    private function checkModuleVersion($useCache = true)
    {
        $this->preventDirectories();

        $file = DIR_DOWNLOAD . '/' . $this->downloadDirectory . '/version_log.txt';

        if ($useCache) {
            if (file_exists($file) && is_readable($file)) {
                $content = preg_replace('/\s+/', '', file_get_contents($file));
                if (!empty($content)) {
                    $parts = explode(':', $content);
                    if (count($parts) === 2) {
                        if (time() - $parts[1] < 3600 * 8) {
                            return array(
                                'tag'     => $parts[0],
                                'version' => preg_replace('/[^\d\.]+/', '', $parts[0]),
                                'time'    => $parts[1],
                                'date'    => $this->dateDiffToString($parts[1]),
                            );
                        }
                    }
                }
            }
        }

        $connector = new GitHubConnector();
        $version = $connector->getLatestRelease($this->repository);
        if (empty($version)) {
            return array();
        }

        $cache = $version . ':' . time();
        if (is_writable($file)) {
            file_put_contents($file, $cache);
        }

        return array(
            'tag'     => $version,
            'version' => preg_replace('/[^\d\.]+/', '', $version),
            'time'    => time(),
            'date'    => $this->dateDiffToString(time()),
        );
    }

    private function getChangeLog($currentVersion, $newVersion)
    {
        $connector = new GitHubConnector();

        $dir = DIR_DOWNLOAD . '/' . $this->downloadDirectory;
        $newChangeLog = $dir . '/CHANGELOG-' . $newVersion . '.md';
        if (!file_exists($newChangeLog)) {
            $fileName = $connector->downloadLatestChangeLog($this->repository, $dir);
            if (!empty($fileName)) {
                rename($dir . '/' . $fileName, $newChangeLog);
            }
        }

        $oldChangeLog = $dir . '/CHANGELOG-' . $currentVersion . '.md';
        if (!file_exists($oldChangeLog)) {
            $fileName = $connector->downloadLatestChangeLog($this->repository, $dir);
            if (!empty($fileName)) {
                rename($dir . '/' . $fileName, $oldChangeLog);
            }
        }

        $result = '';
        if (file_exists($newChangeLog)) {
            $result = $connector->diffChangeLog($oldChangeLog, $newChangeLog);
        }
        return $result;
    }

    private function downloadLastVersion($tag, $useCache = true)
    {
        $this->preventDirectories();

        $dir = DIR_DOWNLOAD . '/' . $this->versionDirectory;
        if ($useCache) {
            $fileName = $dir . '/' . $tag . '.zip';
            if (file_exists($fileName)) {
                return $fileName;
            }
        }

        $connector = new GitHubConnector();
        $fileName = $connector->downloadRelease($this->repository, $tag, $dir);
        if (empty($fileName)) {
            $this->module->log('error', 'Не удалось загрузить архив с обновлением');
            return false;
        }

        return $fileName;
    }

    public function createBackup($version)
    {
        $this->preventDirectories();

        $sourceDirectory = ROOT_DIRECTORY;
        $reader = new ProjectStructureReader();
        $root = $reader->readFile(dirname(__FILE__) . '/oscommerce.map', $sourceDirectory);

        $rootDir = $version . '-' . time();
        $fileName = $rootDir . '-' . uniqid('', true) . '.zip';
        $dir = DIR_DOWNLOAD . '/' . $this->backupDirectory;
        try {
            $fileName = $dir . '/' . $fileName;
            $archive = new BackupZip($fileName, $rootDir);
            $archive->backup($root);
        } catch (\Exception $e) {
            $this->module->log('error', 'Failed to create backup: ' . $e->getMessage());
            return false;
        }
        return true;
    }

    private function unpackLastVersion($fileName)
    {
        if (!file_exists($fileName)) {
            $this->module->log('error', 'File "' . $fileName . '" not exists');
            return false;
        }

        try {
            $sourceDirectory = ROOT_DIRECTORY;
            $archive = new RestoreZip($fileName, $this->module);
            $archive->restore('oscommerce.map', $sourceDirectory);
        } catch (\Exception $e) {
            $this->module->log('error', $e->getMessage());
            if ($e->getPrevious() !== null) {
                $this->module->log('error', $e->getPrevious()->getMessage());
            }
            return false;
        }
        return true;
    }

    private function preventDirectories()
    {
        $this->checkDirectory(DIR_DOWNLOAD . '/' . $this->downloadDirectory);
        $this->checkDirectory(DIR_DOWNLOAD . '/' . $this->backupDirectory);
        $this->checkDirectory(DIR_DOWNLOAD . '/' . $this->versionDirectory);
    }

    private function checkDirectory($directoryName)
    {
        if (!file_exists($directoryName)) {
            mkdir($directoryName);
        }
        if (!is_dir($directoryName)) {
            throw new \RuntimeException('Invalid configuration: "' . $directoryName . '" is not directory');
        }
    }

    private function formatSize($size)
    {
        static $sizes = array(
            'B', 'kB', 'MB', 'GB', 'TB',
        );

        $i = 0;
        while ($size > 1024) {
            $size /= 1024.0;
            $i++;
        }
        return number_format($size, 2, '.', ',') . '&nbsp;' . $sizes[$i];
    }

    private function dateDiffToString($timestamp)
    {
        /*
        $diff = time() - $timestamp;
        if ($diff < 60) {
            return 'только что';
        } elseif ($diff < 120) {
            return 'минуту назад';
        } elseif ($diff < 180) {
            return 'две минуты назад';
        } elseif ($diff < 300) {
            return 'пару минут назад';
        }
        */
        return date('d.m.Y H:i', $timestamp);
    }
}
