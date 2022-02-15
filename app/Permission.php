<?php

namespace App;
use Zizaco\Entrust\EntrustPermission;
use OwenIt\Auditing\Contracts\Auditable;
class Permission extends EntrustPermission  implements Auditable
{
    use \OwenIt\Auditing\Auditable;  
     

     
}
