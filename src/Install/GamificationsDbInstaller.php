<?php

/**
 * Class GamificationsDbInstaller
 */
class GamificationsDbInstaller
{
    /**
     * @var Gamifications
     */
    private $module;

    /**
     * GamificationDbInstaller constructor.
     *
     * @param Gamifications $module
     */
    public function __construct(Gamifications $module)
    {
        $this->module = $module;
    }

    /**
     * Install database
     *
     * @return bool
     *
     * @throws Exception
     */
    public function install()
    {
        $installSqlFiles = glob($this->module->getLocalPath().'sql/install/*.sql');

        if (empty($installSqlFiles)) {
            return true;
        }

        foreach ($installSqlFiles as $sqlFile) {
            $sqlStatements = $this->getSqlStatements($sqlFile);

            if (!$this->execute($sqlStatements)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Uninstall database
     *
     * @return bool
     *
     * @throws Exception
     */
    public function uninstall()
    {
        $uninstallSqlFileName = $this->module->getLocalPath().'sql/uninstall/uninstall.sql';
        $sqlStatements = $this->getSqlStatements($uninstallSqlFileName);

        return (bool) $this->execute($sqlStatements);
    }

    /**
     * Execute SQL statements
     *
     * @param $sqlStatements
     *
     * @return bool
     *
     * @throws Exception
     */
    private function execute($sqlStatements)
    {
        try {
            $result = Db::getInstance()->execute($sqlStatements);
        } catch (Exception $e) {
            throw new Exception('Invalid SQL statements.');
        }

        return (bool) $result;
    }

    /**
     * Format and get sql statements from file
     *
     * @param string $fileName
     *
     * @return string
     */
    private function getSqlStatements($fileName)
    {
        $sqlStatements = file_get_contents($fileName);
        $sqlStatements = str_replace('PREFIX_', _DB_PREFIX_, $sqlStatements);
        $sqlStatements = str_replace('ENGINE_TYPE', _MYSQL_ENGINE_, $sqlStatements);

        return $sqlStatements;
    }
}
