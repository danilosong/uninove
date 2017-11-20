<?php

/*
 * License
 */

namespace Application\Controller;

/**
 * Description of AppLogRepository
 *
 * @author Allan Davini <allan@tcmed.com.br>
 */


class AppLogsController extends CrudController
{

    public function __construct() {
        parent::__construct('appLog');
    }

}

