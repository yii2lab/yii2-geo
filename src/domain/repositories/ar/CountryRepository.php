<?php

namespace yii2lab\geo\domain\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;

class CountryRepository extends ActiveArRepository {

    public function uniqueFields() {
        return [
            ['name'],
        ];
    }

}
