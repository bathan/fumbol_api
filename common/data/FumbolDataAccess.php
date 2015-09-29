<?php
namespace fumbol\common\data;

class FumbolDataAccess extends BaseDataAccess {

    public function __construct() {
        parent::__construct();
    }

    protected function connect() {
        $link = mysqli_connect(_DB_HOST, _DB_USER, _DB_PASS, _DB_NAME);
        return $link;
    }
}
