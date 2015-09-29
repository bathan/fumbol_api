<?php
namespace fumbol\common\resources {

    use Slim\Slim;

    abstract class AbstractResource {
        private $app;

        function __construct(Slim $app)
        {
            $this->app = $app;
        }

        /**
         * @return Slim
         */
        public function getApp()
        {
            return $this->app;

        }
    }



}

