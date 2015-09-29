<?php
namespace fumbol\common\resources {

    use fumbol\common\data\entities\Group;

    class GroupResource extends AbstractResource {

        /*
         * Creates a new Group
         */
        public function addGroup() {
            try {
                $form = $this->getApp()->request()->post();

                $new_group = new Group();

                $group_description = $form["group_description"];
                $group_name = $form["group_name"];

                //-- Check if group name provided already exists.
                $g = Group::getByName($group_name);

                if(is_null($g)) {

                    $new_group->setGroupDescription($group_description);
                    $new_group->setGroupName($group_name);
                    $new_group->persist();

                    $this->getApp()->render(
                        200,
                        ['data' => $new_group->toArray()]
                    );

                }else{
                    throw new \Exception("Group name '".$group_name."' already exists");
                }


            }catch(\Exception $e) {

                $this->getApp()->render(
                    200,
                    ['error' => $e->getMessage()]
                );
            }
        }

        /*
         * Updates an existing Group
         */
        public function updateGroup($groupId) {
            try {
                $form = $this->getApp()->request()->post();

                $group = Group::getById($groupId);

                $group->setGroupDescription($form["group_description"]);
                $group->setGroupName($form["group_name"]);
                $group->persist();

                $this->getApp()->render(
                    200,
                    ['data' => $group->toArray()]
                );

            }catch(\Exception $e) {

                $this->getApp()->render(
                    500,
                    ['error' => $e->getMessage()]
                );
            }
        }

        public function getGroup($groupId) {

            try {
                $group = Group::getById($groupId);

                if(is_null($group)) {
                    throw new \Exception("Group ".$groupId." does not exist");
                }

                $this->getApp()->render(
                    200,
                    ['data' => $group->toArray()]
                );

            }catch(\Exception $e) {

                $this->getApp()->render(
                    500,
                    ['error' => $e->getMessage()]
                );
            }
        }
    }

}
