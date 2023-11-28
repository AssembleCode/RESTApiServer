<?php

namespace App\Traits\Controller;

use App\Traits\Controller\Functions\TraitRestDestroy;
use App\Traits\Controller\Functions\TraitRestIndex;
use App\Traits\Controller\Functions\TraitRestShow;
use App\Traits\Controller\Functions\TraitRestStore;
use App\Traits\Controller\Functions\TraitRestUpdate;
use App\Traits\Response\TraitRestResponse;

trait RestControllerTrait
{
    use TraitRestResponse;
    use TraitRestIndex;
    use TraitRestStore;
    use TraitRestShow;
    use TraitRestUpdate;
    use TraitRestDestroy;
}
