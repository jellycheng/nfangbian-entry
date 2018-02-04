<?php
namespace nfangbian\entry;

class App {

    protected $isInit = false;
    protected $entryType;
    protected $startTime = 0;

    protected $basePath;
    protected $config = [];

    protected $version = '1.0.0';

    protected function __construct()
    {
        $this->startTime = microtime(true);
    }

    public static function getInstance() {
        static $instance;
        if(!$instance) {
            $instance = new static;
        }
        return $instance;
    }


    public function init($config = []) {
        if(!$this->isInit) {
            //初始化
            if(isset($config['nfangbian_path_root']) && $config['nfangbian_path_root']) {
                $this->basePath = rtrim($config['nfangbian_path_root'], '\\\/') . DIRECTORY_SEPARATOR;
                //$this->setConfig('nfangbian_path_root', $config['nfangbian_path_root']);
            } else {
                $this->basePath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
            }
            $this->setConfig('nfangbian_path_root', $this->basePath);
            $this->buildPath();
        }
        return $this;
    }

    protected function setConfig($key, $value = null) {
        if(is_array($key)) {
            foreach($key as $k=>$v) {
                $this->config[$k] = $v;
            }
        } else {
            $this->config[$key] = $value;
        }
    }

    protected function buildPath() {
        foreach (['config', 'entrycode', 'projectcode', 'console', 'public', 'lib'] as $path)
        {
            $this->setConfig('nfangbian_path_'.$path, $this->{$path.'Path'}());
        }
    }

    protected function configPath() {
        return $this->basePath . 'config' . DIRECTORY_SEPARATOR;
    }

    protected function entrycodePath() {
        return $this->basePath . 'entrycode' . DIRECTORY_SEPARATOR;
    }

    protected function projectcodePath() {
        return $this->basePath . 'projectcode' . DIRECTORY_SEPARATOR;
    }

    public function getProjectcodePath() {
        $projectPath = $this->env('NFANGBIAN_PATH_APP', '');
        if(!$projectPath) {
            $projectPath = $this->getConfig('nfangbian_path_projectcode');
        }
        return rtrim($projectPath, '\\\/') . DIRECTORY_SEPARATOR;
    }

    protected function consolePath() {
        return $this->basePath . 'entrycode' . DIRECTORY_SEPARATOR . 'console' . DIRECTORY_SEPARATOR;
    }

    protected function publicPath() {
        return $this->basePath . 'entrycode' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
    }

    protected function libPath() {
        return $this->basePath . 'entrycode' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
    }

    public function basePath()
    {
        return $this->basePath;
    }

    public function getConfig($key = null, $default = null) {
        $config = $this->config;
        if(is_null($key)) {
            return $config;
        }
        if(isset($config[$key])) {
            return $config[$key];
        }
        return $this->value($default);
    }

    public function getEntryType() {
        if(!$this->entryType && defined('NFANGBIAN_ENTRY_TYPE')) {
            $this->entryType = NFANGBIAN_ENTRY_TYPE;
        }
        return $this->entryType;
    }


    public function getStartTime() {
        return $this->startTime;
    }


    public function loadLibClass($name) {
        $file = $this->getConfig('nfangbian_path_lib') . $name . '.class.php';
        if(file_exists($file)) {
            require_once $file;
        }
    }


    public function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $this->value($default);
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
            case 'empty':
            case '(empty)':
                return '';
        }
        return $value;
    }

    //获取当前环境
    public function environment() {
        static $appEnv;
        if($appEnv) {
            return $appEnv;
        }
        $appEnv = $this->env('NFANGBIAN_APP_ENV', '');
        if(!$appEnv) {
            $appEnv = getenv('APP_ENV');
            if(!$appEnv) {
                $appEnv = get_cfg_var('env.name');
            }
        }

        return $appEnv?:'production';
    }

    //开发者
    public function getCloudName() {
        $cloudName = getenv('CLOUD_NAME');//nginx
        if(!$cloudName) {
            $cloudName = get_cfg_var('cloud.name');//php.ini
        }
        return $cloudName;
    }

    public function getAppName() {
        $appName = getenv('APP_NAME')?:'defaultItem'; //nginx传来的项目名

        return $appName;
    }

    protected function value($val) {
        return $val instanceof \Closure ? $val() : $val;
    }

    public function getVersion() {
        return $this->version;
    }

}