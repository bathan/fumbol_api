<?php
namespace fumbol\common\resources {


    class ScoreBoardResource extends AbstractResource
    {

        /*
         * Creates a new Group
         */
        public function update()
        {
            try {

                $score = $this->getApp()->request()->post("score");
                $description = $this->getApp()->request()->post("description");

                $response_data = ['wasaa'=>[$score
                ,$description]];

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
