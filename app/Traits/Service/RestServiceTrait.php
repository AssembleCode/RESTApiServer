<?php

namespace App\Traits\Service;

use App\Traits\Service\Functions\TraitRestDeleteData;
use App\Traits\Service\Functions\TraitRestIndex;
use App\Traits\Service\Functions\TraitRestShowData;
use App\Traits\Service\Functions\TraitRestStoreData;
use App\Traits\Service\Functions\TraitRestUpdateData;
use App\Traits\Response\TraitRestResponse;

trait RestServiceTrait
{
    use TraitRestResponse;
    use TraitRestIndex;
    use TraitRestStoreData;
    use TraitRestShowData;
    use TraitRestUpdateData;
    use TraitRestDeleteData;
}
