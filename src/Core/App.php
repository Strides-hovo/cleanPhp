<?php

namespace App\Core;

use App\Core\Traits\BaseInstance;
use App\Helpers\AndataExeption;
use RedBeanPHP\RedException\SQL;
use RedBeanPHP\ToolBox;

class App
{
    use BaseInstance;

    private ToolBox $db;


    /**
     * @throws AndataExeption
     */
    private function __construct()
    {
        $db = new Database();
        $this->db = $db->connect();
    }


    public function run(): void
    {
        try {
            Router::dispatch(__DIR__ . '/../../routes/web.php');
        } catch (AndataExeption $e) {
            if ($e->getParams()) {
                echo json_encode($e->getParams(), JSON_UNESCAPED_UNICODE);
            } else {
                echo $e->getMessage();
            }
        } catch (\ReflectionException $e) {
            echo $e->getMessage();
        } catch (SQL $e) {
            echo($e->getCode());
        }
    }


    public function getDb(): ToolBox
    {
        return $this->db;
    }

}


