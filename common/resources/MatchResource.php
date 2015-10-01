<?php
namespace fumbol\common\resources {


    use fumbol\common\logic\MatchLogic;

    class MatchResource extends AbstractResource
    {

        /*
         * Creates a new Group
         */
        public function check()
        {
            try {

                $ml = new MatchLogic();
                $current_match = $ml->getCurrentMatch()->toArray();

                $response_data = ['current_match'=>$current_match];

                $this->getApp()->render(
                    200,
                    ['data' => $response_data]
                );
            } catch (\Exception $e) {

                $this->getApp()->render(
                    200,
                    ['error' => $e->getMessage()]
                );
            }
        }
    }

}
