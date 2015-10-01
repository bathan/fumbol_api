<?php

namespace fumbol\common\resources {
    use Slim\Slim;

    class ResourceFactory
    {
        private $app;

        function __construct(Slim $app)
        {
            $this->app = $app;
        }

        public function getUserResource(){
            return new UserResource($this->app);
        }

        public function getGroupResource() {
            return new GroupResource($this->app);
        }

        public function getMatchResource() {
            return new MatchResource($this->app);
        }

    }

}

